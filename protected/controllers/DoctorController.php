<?php

class DoctorController extends MobiController
{

    public $limit;
    public $category='';
    public $subway='';
    public $district='';
    public $category_title='';
    public $subway_title='';
    public $district_title='';
    public $title_h1='';
    public $name_spec;
    public $name_service;
    public $ctype='Врачи';

    /**
     * Список клиник
     * @param type $category
     * @param type $subway
     * @param type $district
     * @param type $house
     * @throws CHttpException
     */
    public function actionIndex($category=null,$subway=null,$district=null,$house=null)
    {
        $this->getAError(array('category','subway','district','house','page','sort'));
        $this->layout="//layouts/column/column_list_doctor";
        $this->link='Специальности';
        $this->blogInitAlias(); // SEO вставка
        $this->category=$category;
        $this->subway=$subway;
        $this->district=$district;
        $saveCriteria='';
        $saveParams=array();
        $criteria=new CDbCriteria();
        $saveCriteria['status']="t.status=1";
        $sort=new CSort('AdminDoctor');
        $sort->defaultOrder="CASE WHEN t.image IS NULL THEN 1 ELSE 0 END, CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC, CASE WHEN doctor_category_s.price IS NULL THEN 1 ELSE 0 END, doctor_category_s.price ASC";
        $sort->attributes=array(
            'title_desc'=>array(
                'asc'=>'CONCAT_WS(" ",t.lname,t.fname,t.sname) DESC',
                'desc'=>'CONCAT_WS(" ",t.lname,t.fname,t.sname) DESC',
                'label'=>'Ю - А',
            ),
            'title_asc'=>array(
                'asc'=>'CONCAT_WS(" ",t.lname,t.fname,t.sname) ASC',
                'desc'=>'CONCAT_WS(" ",t.lname,t.fname,t.sname) ASC',
                'label'=>'А - Ю',
            ),
            'rating_asc'=>array(
                'asc'=>'CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.rate10 ASC',
                'desc'=>'CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.rate10 ASC',
                'label'=>'Рейтинг по возрастанию',
            ),
            'rating_desc'=>array(
                'asc'=>'CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.rate10 DESC',
                'desc'=>'CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.rate10 DESC',
                'label'=>'Рейтинг по убыванию',
            ),
        );
        $_category='';
        if(!empty($category)){
            $_category=AdminCategory::model()->findByAttributes(array('translit'=>$category));
            if(empty($_category->translit)||(!empty($_category->level)&&$_category->level>2)){
                throw new CHttpException(404,"Категории с URL: {$category} на сайте не обнаружено.");
            }
            $this->name_service=!empty($_category->name_service)?$_category->name_service:$_category->name;
            $this->name_spec=!empty($_category->name_spec)?$_category->name_spec:'';
            $saveCriteria['category']="catd_category.lft>=:lft AND catd_category.rgt<=:rgt AND catd_category.root=:root";
            $saveParams['category']=array(":lft"=>$_category->lft,":rgt"=>$_category->rgt,":root"=>$_category->root);
        }
        if(!empty($district)){
            $_district=AdminDistrict::model()->findByAttributes(array('translit'=>$district));
            if(empty($_district->translit)){
                $_district=AdminDistrictLine::model()->findByAttributes(array('translit'=>$district));
                if(empty($_district->translit)){
                    throw new CHttpException(404,"Района или округа с URL: {$district} на сайте не обнаружено.");
                }
                $saveCriteria['district']="c.district_line_id=:district_id";
                $saveParams['district']=array(":district_id"=>$_district->id);
            } else{
                $saveCriteria['district']="c.district_id=:district_id";
                $saveParams['district']=array(":district_id"=>$_district->id);
            }
        }
        $subways_title=array();
        if(!empty($subway)){
            $subways_data=explode("_",$subway);
            foreach($subways_data as $value){
                $_defaultOrder=array();
                $_subway=AdminSubway::model()->findByAttributes(array('translit'=>$value));
                if(empty($_subway->id)){
                    throw new CHttpException(404,"Станции метро с URL: {$subway} на сайте не обнаружено.");
                }
                $subways_title[$value]=$_subway->title;
                if(!empty($_subway->lat)&&!empty($_subway->lng)){
                    $lat=str_replace(",",".",CHtml::encode($_subway->lat));
                    $lng=str_replace(",",".",CHtml::encode($_subway->lng));
                    $_defaultOrder[]="CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(sub.lat))*cos(radians({$lat}))*cos(radians({$lng})-radians(sub.lng))+sin(radians(sub.lat))*sin(radians({$lat}))) ASC";
                }
                $_defaultOrder[]="CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC, CASE WHEN doctor_category_s.price IS NULL THEN 1 ELSE 0 END, doctor_category_s.price ASC";
                $_defaultOrder[]="CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(c.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(c.lng))+sin(radians(c.lat))*sin(radians(sub.lat))) ASC";
            }
            if(!empty($_defaultOrder)){
                $sort->defaultOrder=implode(",",$_defaultOrder);
            }
        }
        $criteria->with=array(
            'doctor_price_s'=>array('select'=>false),
            'doctor_clinic_s'=>array('select'=>false,'with'=>array('c'=>array('select'=>false,'with'=>array('clinic_district'=>array('select'=>'title, translit'),'clinic_district_line'=>array('select'=>'title, translit'),'clinic_subway_s'=>array('select'=>false,'with'=>array('sub'=>array('select'=>'title, translit'))))))),
            'doctor_category_s'=>array('select'=>false,'with'=>array('catd_category'=>array('select'=>'name, translit, lft, rgt, root'))),
        );
        $criteria->together=true;
        $criteria->group="t.id";
        if(!empty($saveCriteria)){
            foreach($saveCriteria as $value){
                $criteria->addCondition($value);
            }
        }
        if(!empty($house)){
            $criteria->addCondition("house=1");
        }
        if(!empty($saveParams)){
            $params=array();
            foreach($saveParams as $value){
                $params=array_merge($params,$value);
            }
            $criteria->params=$params;
        }
        $count=AdminDoctor::model()->count($criteria);
        if(!empty($subway)){
            if($count>10){
                $count=10;
            }
        }
        $sort->applyOrder($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $data=AdminDoctor::model()->findAll($criteria);
        $this->data=$this->categories($category);
        $this->dataSubway=$this->subways($subway);
        $this->initAlias();
        if(empty($this->pageH1)){
            $this->pageH1='Список докторов - '.(!empty($this->category_title)?$this->category_title.', ':'').(!empty($this->subway_title)?$this->subway_title:'Москва');
        }
        $this->render('list',array('data'=>$data,'pages'=>$pages,'count'=>$count,'sort'=>$sort,'category'=>$category,'subways_title'=>$subways_title,'subway'=>$subway,'district'=>$district,'house'=>$house));
    }

    /**
     * Карточка специалиста
     * @param type $id
     * @throws CHttpException
     */
    public function actionSingle($id)
    {
        $this->getAError(array('id'));
        $this->layout='//layouts/column/column_single_doctor';
        $this->link='Все доктора';
        $model=AdminDoctor::model()->findByAttributes(array('translit'=>$id));
        if(!empty($model->translit)){
            $limit=Yii::app()->db->createCommand("SELECT COUNT(*) FROM reviews WHERE clinic_id='{$model->id}' AND status=1")->queryScalar();
            if(!empty($limit)&&$limit>5){
                $this->limit=1;
            }
            if(isset($model->status)){
                if($model->status==1||$model->status==0||$model->status==2){
                    $this->render('single',array('model'=>$model));
                } else{
                    throw new CHttpException(404,"Запрашиваемого доктора на сайте не обнаружено.");
                }
            }
        } else{
            throw new CHttpException(404,"Запрашиваемого доктора на сайте не обнаружено.");
        }
    }

    /**
     * Отзывы и комментарии доктора
     * @param type $id
     * @param type $limit
     */
    public function actionComment($id,$limit=2)
    {
        if(Yii::app()->request->isPostRequest){
            $data=AdminReview::model()->findAllByAttributes(array('doctor_id'=>$id,'status'=>1),array('limit'=>$limit*5));
            $doctor=AdminDoctor::model()->findByPk($id);
            $count_rating=AdminReview::model()->countByAttributes(array('doctor_id'=>$id,'status'=>1));
            if(($count_rating-$limit*5)>0){
                $this->limit=$limit;
            }
            $this->renderPartial('//doctor/elements/_comment',array('model'=>$doctor,'doctor'=>$doctor,'data'=>$data,'limit'=>($limit+1),'count_rating'=>$count_rating,'count'=>($count_rating-$limit*5)));
        }
    }

    /**
     * Добавить отзыв доктору
     * @param type $id
     */
    public function actionRating($id)
    {
        if(Yii::app()->request->isPostRequest&&!empty($_POST['AdminReview'])){
            $model=new AdminReview;
            $model->doctor_id=$id;
            $model->status=0;
            $model->attributes=$_POST['AdminReview'];
            $modelData=AdminReview::model()->findByAttributes(array('telephone'=>$model->telephone));
            if(!empty($modelData)){
                if(date("Ymd",strtotime($modelData->create_time))<date("Ymd")){
                    if($model->save()){
                        echo CJSON::encode(array("message"=>$this->renderPartial("//layouts/elements/_comment_response",array(),true,false)));
                    } else{
                        echo CActiveForm::validate($model);
                    }
                } else{
                    echo CJSON::encode(array("AdminReview_description"=>"Вы сегодня уже добавляли комментарий..."));
                }
            } else{
                if($model->save()){
                    echo CJSON::encode(array("message"=>$this->renderPartial("//layouts/_comment_response",array(),true,false)));
                } else{
                    echo CActiveForm::validate($model);
                }
            }
        }
    }

    /**
     * SEO - шляпа
     * @param type $title
     */
    private function seoHate($title,$_category)
    {
        if(empty($title)){
            if(!empty($this->category_title)){
                if(!empty($this->subway_title)){
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        if(!empty($_category->name_spec)){
                            $this->title_h1="Врачи ".$_category->name_spec." м. ".$this->subway_title;
                            $this->pageTitle="Врачи-".$_category->name_spec." возле метро ".$this->subway_title.": цены и отзывы - MedBooking.com";
                            $this->pageDescription="MedBooking.com - лучшие врачи-".$_category->name_spec." Москвы принимающие в районе метро ".$this->subway_title.". Онлайн запись на прием &#9742; +7(499) 705-39-99";
                        } else{
                            $this->title_h1="Врачи ".$this->category_title." м. ".$this->subway_title;
                            $this->pageTitle="Врачи-".$this->category_title." возле метро ".$this->subway_title.": цены и отзывы - MedBooking.com";
                            $this->pageDescription="&#128270; MedBooking.com - лучшие врачи-".$this->category_title." Москвы принимающие в возле метро ".$this->subway_title.". Бесплатная OnLine запись на прием ☎ +7(499) 705-39-99";
                        }
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    $this->ctype.=" | ".$this->category_title." | Станция метро ".$this->subway_title;
                } elseif(!empty($this->district_title)){
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        if(!empty($_category->name_spec)){
                            $this->title_h1="Врачи-".$_category->name_spec.": район ".$this->district_title;
                            $this->pageTitle="Врачи-".$_category->name_spec.": район ".$this->district_title." - MedBooking.com";
                            $this->pageDescription="&#10010; Врачи-".$_category->name_spec." - район ".$this->district_title.". Бесплатная OnLine запись на прием по &#9742; +7(499) 705-39-99";
                        } else{
                            $this->title_h1="Врачи-".$this->category_title.": район ".$this->district_title;
                            $this->pageTitle="Врачи-".$this->category_title.": район ".$this->district_title." - MedBooking.com";
                            $this->pageDescription="&#10010; Врачи-".$this->category_title." - район ".$this->district_title.". Бесплатная OnLine запись на прием по &#9742; +7(499) 705-39-99";
                        }
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    $this->ctype.=" | ".$this->category_title." | Станция метро ".$this->district_title;
                } else{
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        if(!empty($_GET['house'])){
                            $this->title_h1='Вызов '.$this->name_service." на дом";
                            $this->pageTitle='Вызов на дом '.$this->name_service." - ".Yii::app()->name;
                        } else{
                            $this->title_h1=$this->category_title;
                            $this->pageTitle=$this->category_title." - ".Yii::app()->name;
                        }
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    if(!empty($_GET['house'])){
                        $this->ctype.=" | ".$this->name_service;
                    } else{
                        $this->ctype.=" | ".$this->category_title;
                    }
                }
            } else{
                if(!empty($this->subway_title)){
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        $this->title_h1="Врачи на станции метро ".$this->subway_title;
                        $this->pageTitle=$this->ctype." возле метро ".$this->subway_title." - ".Yii::app()->name;
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    $this->ctype.=" | Станция метро ".$this->subway_title;
                } elseif(!empty($this->district_title)){
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        $this->title_h1="Врачи Москвы - район ".$this->district_title;
                        $this->pageTitle=$this->ctype." Москвы (".$this->district_title.") - ".Yii::app()->name;
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    $this->ctype.=" | Район ".$this->district_title;
                }
            }
        } else{
            if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                $this->title_h1=$title;
            }
            if(empty($this->pageH1)){
                $this->pageH1=$this->title_h1;
            }
        }
    }

}
