<?php

class ClinicController extends MobiController
{

    public $limit;
    public $doctors;
    public $clinic_id;
    public $category='';
    public $subway='';
    public $district='';
    public $category_title='';
    public $subway_title='';
    public $district_title='';
    public $title_h1='';
    public $ctype='Клиники';

    /**
     * Сетевые
     * @param type $category
     * @param type $subway
     * @param type $district
     * @throws CHttpException
     */
    public function actionSetevaya()
    {
        $this->getAError(array('page','sort'));
        $this->layout="//layouts/column/column_list_network";
        $this->link='Специализация';
        $criteria=new CDbCriteria();
        $criteria->addCondition("(t.status=3) AND (t.network_id IS NULL OR t.network_id='')");
        $sort=new CSort('AdminClinic');
        $sort->defaultOrder="CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC";
        $sort->attributes=array(
            'title_desc'=>array(
                'asc'=>'t.title DESC',
                'desc'=>'t.title DESC',
                'label'=>'Ю - А',
            ),
            'title_asc'=>array(
                'asc'=>'t.title ASC',
                'desc'=>'t.title ASC',
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
        $criteria->with=array(
            'clinic_subway_s'=>array('select'=>false,'with'=>array('sub'=>array('select'=>'title, translit'))),
            'clinic_category_s'=>array('select'=>false,'with'=>array('catc_category'=>array('select'=>'translit, lft, rgt, root'))),
        );
        $criteria->together=true;
        $criteria->group="t.id";
        $count=AdminClinic::model()->count($criteria);
        $sort->applyOrder($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $data=AdminClinic::model()->findAll($criteria);
        $this->initAlias();
        if(empty($this->pageH1)){
            $this->pageH1='Список сетевых клиник - '.(!empty($this->category_title)?$this->category_title.', ':'').(!empty($this->subway_title)?$this->subway_title:'Москва');
        }
        $array=array('data'=>$data,'pages'=>$pages,'count'=>$count,'sort'=>$sort);
        $this->render('list_network',$array);
    }

    /**
     * Список клиник
     * @param type $category по услугам
     * @param type $subway по станциям метро
     * @param type $title по вхождению
     * @throws CHttpException
     */
    public function actionIndex($category=null,$subway=null,$district=null)
    {
        $this->getAError(array('category','subway','district','page','sort'));
        $this->layout="//layouts/column/column_list_clinic";
        $this->link='Специализация';
        $this->blogInitAlias(); // SEO вставка
        $this->category=$category;
        $this->subway=$subway;
        $this->district=$district;
        $saveCriteria='';
        $saveParams=array();
        $criteria=new CDbCriteria();
        $saveCriteria['status']="t.status=1";
        $sort=new CSort('AdminClinic');
        $sort->defaultOrder="CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC, CASE WHEN clinic_category_s.price IS NULL THEN 1 ELSE 0 END, clinic_category_s.price ASC";
        $sort->attributes=array(
            'title_desc'=>array(
                'asc'=>'t.title DESC',
                'desc'=>'t.title DESC',
                'label'=>'Ю - А',
            ),
            'title_asc'=>array(
                'asc'=>'t.title ASC',
                'desc'=>'t.title ASC',
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
        if(!empty($category)){
            $_category=AdminCategory::model()->findByAttributes(array('translit'=>$category));
            if(empty($_category->translit)||(!empty($_category->level)&&$_category->level>2)){
                throw new CHttpException(404,"Категории с URL: {$category} на сайте не обнаружено.");
            }
            $saveCriteria['category']="catc_category.lft>=:lft AND catc_category.rgt<=:rgt AND catc_category.root=:root";
            $saveParams['category']=array(":lft"=>$_category->lft,":rgt"=>$_category->rgt,":root"=>$_category->root);
        }
        if(!empty($district)){
            $_district=AdminDistrict::model()->findByAttributes(array('translit'=>$district));
            if(empty($_district->translit)){
                $_district=AdminDistrictLine::model()->findByAttributes(array('translit'=>$district));
                if(empty($_district->translit)){
                    throw new CHttpException(404,"Района и округа с URL: {$district} на сайте не обнаружено.");
                }
                $saveCriteria['district']="t.district_line_id=:district_id";
                $saveParams['district']=array(":district_id"=>$_district->id);
            } else{
                $saveCriteria['district']="t.district_id=:district_id";
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
                $_defaultOrder[]="CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC, CASE WHEN clinic_category_s.price IS NULL THEN 1 ELSE 0 END, clinic_category_s.price ASC";
                $_defaultOrder[]="CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) ASC";
            }
            if(!empty($_defaultOrder)){
                $sort->defaultOrder=implode(",",$_defaultOrder);
            }
        }
        $criteria->with=array(
            'clinic_district'=>array('select'=>'title, translit'),
            'clinic_district_line'=>array('select'=>'title, translit'),
            'clinic_network'=>array('select'=>'title, translit'),
            'clinic_subway_s'=>array('select'=>false,'with'=>array('sub'=>array('select'=>'title, translit'))),
            'clinic_category_s'=>array('select'=>false,'with'=>array('catc_category'=>array('select'=>'name, translit, lft, rgt, root'))),
        );
        $criteria->together=true;
        $criteria->group="t.id";
        if(!empty($saveCriteria)){
            foreach($saveCriteria as $value){
                $criteria->addCondition($value);
            }
        }
        if(!empty($saveParams)){
            $params=array();
            foreach($saveParams as $value){
                $params=array_merge($params,$value);
            }
            $criteria->params=$params;
        }
        $count=AdminClinic::model()->count($criteria);
        if(!empty($this->subway)){
            if($count>10){
                $count=10;
            }
        }
        $sort->applyOrder($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $data=AdminClinic::model()->findAll($criteria);
        $this->data=$this->categories($category);
        $this->dataSubway=$this->subways($subway);
        $this->initAlias();
        if(empty($this->pageH1)){
            $this->pageH1='Список клиник - '.(!empty($this->category_title)?$this->category_title.', ':'').(!empty($this->subway_title)?$this->subway_title:'Москва');
        }
        $this->render('list',array('data'=>$data,'pages'=>$pages,'count'=>$count,'sort'=>$sort,'category'=>$category,'subway'=>$subway,'subways_title'=>$subways_title,'district'=>$district));
    }

    /**
     * Карточка сетевой
     * @param type $id
     * @throws CHttpException
     */
    public function actionSingleNetwork($id)
    {
        $this->getAError(array('id'));
        $this->layout='//layouts/column/column_single_network';
        $this->link='Доктора клиники';
        $model=AdminClinic::model()->findByAttributes(array('translit'=>$id));
        if(!empty($model->translit)){
            $limit=Yii::app()->db->createCommand("SELECT COUNT(reviews.id) AS qty FROM reviews LEFT JOIN clinic ON clinic.id=reviews.clinic_id WHERE clinic.network_id='{$model->id}' AND clinic.status=1")->queryScalar();
            if(!empty($limit)&&$limit>5){
                $this->limit=1;
            }
            if(isset($model->status)){
                if($model->status==3){
                    $this->clinic_id=$model->primaryKey;
                    $this->data=$this->specialists($model);
                    $this->doctors=$this->doctors($model,null);
                    $this->render('single_network',array('model'=>$model));
                } else{
                    throw new CHttpException(404,"Зпрашиваемой сетевой клиники на сайте не обнаружено #1.");
                }
            }
        } else{
            throw new CHttpException(404,"Зпрашиваемой сетевой клиники на сайте не обнаружено #0.");
        }
    }

    /**
     * Карточка клиники
     * @param type $id
     * @throws CHttpException
     */
    public function actionSingle($id)
    {
        $this->getAError(array('id'));
        $this->layout='//layouts/column/column_single_clinic';
        $this->link='Доктора клиники';
        $model=AdminClinic::model()->findByAttributes(array('translit'=>$id));
        if(!empty($model->translit)){
            $limit=Yii::app()->db->createCommand("SELECT COUNT(*) FROM reviews WHERE clinic_id='{$model->id}' AND status=1")->queryScalar();
            if(!empty($limit)&&$limit>5){
                $this->limit=1;
            }
            if(isset($model->status)){
                if($model->status==1||$model->status==0){
                    $this->clinic_id=$model->primaryKey;
                    $this->data=$this->specialists($model);
                    $this->doctors=$this->doctors($model,null);
                    $this->render('single',array('model'=>$model));
                } else{
                    throw new CHttpException(404,"Зпрашиваемой клиники на сайте не обнаружено #1.");
                }
            }
        } else{
            throw new CHttpException(404,"Зпрашиваемой клиники на сайте не обнаружено #0.");
        }
    }

    /**
     * Отзывы и комментарии клиники
     * @param type $id
     * @param type $limit
     */
    public function actionComment($id,$limit=2)
    {
        if(Yii::app()->request->isPostRequest){
            $clinic=AdminClinic::model()->findByPk($id);
            if($clinic->status==3){
                $data=AdminReview::model()->with(array('review_clinic'))->findAll(array('condition'=>'review_clinic.network_id='.$id.' AND review_clinic.status=1'),array('limit'=>$limit*5));
            } else{
                $data=AdminReview::model()->findAllByAttributes(array('clinic_id'=>$id,'status'=>1),array('limit'=>$limit*5));
            }
            $count_rating=AdminReview::model()->countByAttributes(array('clinic_id'=>$id,'status'=>1));
            if(($count_rating-$limit*5)>0){
                $this->limit=$limit;
            }
            $this->renderPartial('//clinic/elements/_comment',array('model'=>$clinic,'clinic'=>$clinic,'data'=>$data,'limit'=>($limit+1),'count_rating'=>$count_rating,'count'=>($count_rating-$limit*5)));
        }
    }

    /**
     * Добавить отзыв клинике
     * @param type $id
     */
    public function actionRating($id)
    {
        if(Yii::app()->request->isPostRequest&&!empty($_POST['AdminReview'])){
            $model=new AdminReview;
            $model->clinic_id=$id;
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
     * Список докторов
     * @param type $model
     * @param type $cat
     * @return type
     */
    private function doctors($model,$cat=null)
    {
        $criteria=new CDbCriteria();
        $criteria->order="CASE WHEN t.image IS NULL THEN 1 ELSE 0 END, CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC";
        if($model->status==3){
            $criteria->limit=100;
            $criteria->addCondition('c.network_id='.$model->primaryKey);
        } else{
            $criteria->addCondition('c.id='.$model->primaryKey);
        }
        if(!empty($cat)){
            $category=array();
            foreach($cat as $v){
                $_category=AdminCategory::model()->findByAttributes(array('translit'=>$v));
                if(!empty($_category->primaryKey)){
                    $category[]=$_category->primaryKey;
                }
            }
            if(!empty($category)){
                $criteria->addInCondition("catd_category.id",$category);
            }
        }
        $criteria->together=true;
        $criteria->with=array(
            'rs'=>array('select'=>false),
            'doctor_clinic_s'=>array('select'=>false,'with'=>array('c'=>array('select'=>false))),
            'doctor_category_s'=>array('select'=>false,'with'=>array('catd_category'=>array('select'=>false))),
        );
        $data=AdminDoctor::model()->findAll($criteria);
        return $data;
    }

    /**
     * Специалисты клиники
     * @param type $clinic_id
     */
    public function actionSpecialist($clinic_id)
    {
        if(Yii::app()->request->isPostRequest){
            $data=array();
            $model=AdminClinic::model()->findByPk($clinic_id);
            if(!empty($model->primaryKey)){
                if(!empty($_POST['category'])){
                    $data=$this->doctors($model,$_POST['category']);
                } else{
                    $data=$this->doctors($model,null);
                }
            }
            $this->renderPartial('elements/doctor_clinic',array('data'=>$data,'model'=>$model,'count'=>count($data)));
        }
    }

    /**
     * Специалисты клиники и сетевой клиники
     * @param type $model
     * @return type
     */
    private function specialists($model)
    {
        if($model->status==3){
            $data=Yii::app()->db->createCommand("SELECT category.translit AS translit, category.name AS title FROM clinic_doctor LEFT JOIN doctor ON doctor.id=clinic_doctor.doctor_id LEFT JOIN category_doctor ON doctor.id=category_doctor.doctor_id LEFT JOIN category ON category.id=category_doctor.category_id LEFT JOIN clinic ON clinic.id=clinic_doctor.clinic_id WHERE clinic.network_id='".$model->primaryKey."' AND category.level=2 GROUP BY category.name ASC")->queryAll();
        } else{
            $data=Yii::app()->db->createCommand("SELECT category.translit AS translit, category.name AS title FROM clinic_doctor LEFT JOIN doctor ON doctor.id=clinic_doctor.doctor_id LEFT JOIN category_doctor ON doctor.id=category_doctor.doctor_id LEFT JOIN category ON category.id=category_doctor.category_id LEFT JOIN clinic ON clinic.id=clinic_doctor.clinic_id WHERE clinic.id='".$model->primaryKey."' AND category.level=2 GROUP BY category.name ASC")->queryAll();
        }
        return $data;
    }

    /**
     * SEO шляпа
     * @param type $title
     */
    private function seoHate($title)
    {
        if(empty($title)){
            if(!empty($this->category_title)){
                if(!empty($this->subway_title)){
                    $alias=Yii::app()->db->createCommand("SELECT * FROM alias WHERE url='/search_clinic/{$this->category}'")->queryRow();
                    if(!empty($alias)){
                        $this->pageDescription="MedBooking.com - On-Line запись &#9742; +7(499) 705-39-99. {$alias['h1']} м.{$this->subway_title}.";
                    }
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        if(!empty($alias)){
                            $this->pageTitle=str_replace("Москвы","возле метро ".$this->subway_title,$alias['meta_title'])." - MedBooking.com";
                            $this->pageH1=$alias['h1']." м.".$this->subway_title;
                        }
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    $this->ctype.=" | ".$this->category_title." | Станция метро ".$this->subway_title;
                } elseif(!empty($this->district_title)){
                    $alias=Yii::app()->db->createCommand("SELECT * FROM alias WHERE url='/search_clinic/{$this->category}'")->queryRow();
                    if(!empty($alias)){
                        $this->pageDescription="MedBooking.com - On-Line запись &#9742; +7(499) 705-39-99. {$alias['h1']} {$this->district_title}.";
                    }
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        if(!empty($alias)){
                            $this->pageTitle=str_replace("Москвы","- район ".$this->district_title,$alias['meta_title'])." - ".Yii::app()->name;
                            $this->pageH1=$alias['h1']." (".$this->district_title.")";
                        }
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    $this->ctype.=" | ".$this->category_title." | Район ".$this->district_title;
                } else{
                    $alias=Yii::app()->db->createCommand("SELECT * FROM alias WHERE url='/search_clinic/{$this->category}'")->queryRow();
                    if(!empty($alias)){
                        $this->pageDescription="MedBooking.com - On-Line запись &#9742; +7(499) 705-39-99. {$alias['h1']}.";
                    }
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        if(!empty($this->subway_title)){
                            $this->pageTitle=str_replace("Москвы","возле метро ".$this->subway_title,$alias['meta_title'])." - MedBooking.com";
                        } else{
                            $this->pageTitle=$alias['meta_title']." - MedBooking.com";
                        }
                        $this->pageH1=$alias['h1']." м.".$this->subway_title;
                        $this->title_h1=$this->category_title;
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    $this->ctype.=" | ".$this->category_title;
                }
            } else{
                if(!empty($this->subway_title)){
                    $this->pageDescription="Медицинские клиники и центры возле метро {$this->subway_title} - запись онлайн через сервис MedBooking.com по &#9742; +7(499) 705-39-99";
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        $this->title_h1="Медицинские клиники и центры возле метро ".$this->subway_title;
                        $this->pageTitle="Медицинские клиники и центры на станции метро ".$this->subway_title." - ".Yii::app()->name;
                    }
                    if(empty($this->pageH1)){
                        $this->pageH1=$this->title_h1;
                    }
                    $this->ctype.=" | Станция метро ".$this->subway_title;
                } elseif(!empty($this->district_title)){
                    $this->pageDescription="Медицинские клиники и центры - район {$this->district_title} - запись онлайн через сервис MedBooking.com по &#9742; +7(499) 705-39-99";
                    if(empty($this->pageTitle)||$this->pageTitle==Yii::app()->name){
                        $this->title_h1="Медицинские клиники и центры (".$this->district_title.")";
                        $this->pageTitle="Медицинские клиники и центры - район ".$this->district_title." - ".Yii::app()->name;
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
