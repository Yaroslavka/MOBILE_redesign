<?php

class SiteController extends MobiController
{
    /**
     * Функции отзывов
     * 
    public function actionNetwork()
    {
        $data=Yii::app()->db->createCommand("SELECT * FROM clinic WHERE status=3")->queryAll();
        foreach($data as $value){
            $clinic=Yii::app()->db->createCommand("SELECT clinic_rating2.rate10 AS rate10, clinic_rating2.comm AS comm FROM clinic LEFT JOIN clinic_rating2 ON clinic_rating2.nid=clinic.id WHERE clinic.network_id='{$value['id']}' ORDER BY MAX(clinic_rating2.rate10) DESC LIMIT 1")->queryRow();
            if(!empty($clinic)){
                $rate10=!empty($clinic['rate10'])?$clinic['rate10']:0;
                $_comm=Yii::app()->db->createCommand("SELECT COUNT(*) FROM reviews LEFT JOIN clinic ON reviews.clinic_id=clinic.id WHERE reviews.status=1 AND clinic.network_id='{$value['id']}'")->queryScalar();
                $comm=!empty($_comm)?$_comm:(!empty($value['comm'])?$value['comm']:0);
                Yii::app()->db->createCommand("UPDATE clinic SET rate10='{$rate10}', comm='{$comm}' WHERE id='{$value['id']}'")->execute();
            }
        }
        echo 1;
    }

    public function actionClinic()
    {
        $data=Yii::app()->db->createCommand("SELECT * FROM clinic_rating2 WHERE 1")->queryAll();
        foreach($data as $value){
            $rate10=!empty($value['rate10'])?$value['rate10']:0;
            $_comm=Yii::app()->db->createCommand("SELECT COUNT(*) FROM reviews WHERE status=1 AND clinic_id='{$value['id']}'")->queryScalar();
            $comm=!empty($_comm)?$_comm:(!empty($value['comm'])?$value['comm']:0);
            Yii::app()->db->createCommand("UPDATE clinic SET rate10='{$rate10}', comm='{$comm}' WHERE id='{$value['id']}'")->execute();
        }
        echo 1;
    }

    public function actionDoctor()
    {
        $data=Yii::app()->db->createCommand("SELECT * FROM doctor_rating2 WHERE 1")->queryAll();
        foreach($data as $value){
            $rate10=!empty($value['rate10'])?$value['rate10']:0;
            $_comm=Yii::app()->db->createCommand("SELECT COUNT(*) FROM reviews WHERE status=1 AND doctor_id='{$value['id']}'")->queryScalar();
            $comm=!empty($_comm)?$_comm:(!empty($value['comm'])?$value['comm']:0);
            $drecords=!empty($value['allRecord'])?$value['allRecord']:'';
            $dreviews=!empty($value['reviews'])?$value['reviews']:'';
            $ddayrecords=!empty($value['dayRecord'])?$value['dayRecord']:'';
            $dservices=!empty($value['services'])?$value['services']:'';
            Yii::app()->db->createCommand("UPDATE doctor SET rate10='{$rate10}', comm='{$comm}', dservices='{$dservices}', drecords='{$drecords}', dreviews='{$dreviews}', ddayrecords='{$ddayrecords}' WHERE id='{$value['id']}'")->execute();
        }
        echo 1;
    }
    */
    
    public function actionIndex()
    {
        $this->getAError();
        $this->layout='//layouts/column/column_index';
        $this->data=$this->categories(null);
        $this->dataSubway=$this->subways(null);
        $this->blogInitAlias();
        $this->render('index');
    }

    public function actionError()
    {
        $this->layout='//layouts/column/column_error';
        if($error=Yii::app()->errorHandler->error){
            if(Yii::app()->request->isAjaxRequest){
                echo $error['message'];
            } else{
                $this->render('error',$error);
            }
        }
    }

    public function actionBlogList($theme=null)
    {
        $this->getAError(array('theme','page'));
        $this->layout='//layouts/column/column_list_blog';
        $this->link='Темы блогов';
        $criteria=new CDbCriteria();
        $criteria->order="id DESC";
        if(!empty($theme)){
            $model=AdminNodeFaqCategory::model()->findByAttributes(array('translit'=>$theme));
            if(!empty($model->id)){
                $criteria->addCondition("theme_id=".$model->id);
            } else{
                throw new CHttpException(404,"Зпрашиваемой страницы на сайте не обнаружено.");
            }
        }
        $count=AdminNodeFaq::model()->count($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $data=AdminNodeFaq::model()->findAll($criteria);
        $this->data=$this->themes($theme);
        $this->blogInitAlias();
        $this->render('list',array('data'=>$data,'count'=>$count,'pages'=>$pages));
    }

    public function actionBlogSingle($id)
    {
        $this->getAError(array('id'));
        $this->layout='//layouts/column/column_single_blog';
        $this->link='Список блогов';
        $model=AdminNodeFaq::model()->findByAttributes(array('translit'=>$id));
        if(empty($model->id)){
            throw new CHttpException(404,"Зпрашиваемой страницы на сайте не обнаружено.");
        }
        $theme=!empty($model->cat_s->translit)?$model->cat_s->translit:null;
        $this->data=$this->themes($theme);
        $this->metaModel($model);
        $this->render('single',array('model'=>$model));
    }

    public function actionProject()
    {
        $this->getAError();
        $this->layout='//layouts/column/column_single_blog';
        $this->blogInitAlias();
        $this->render('static',array('model'=>AdminNodeStatic::model()->findByAttributes(array('translit'=>'project'))));
    }

    public function actionIllnessList()
    {
        $this->getAError();
        $this->layout='//layouts/column/column_list_illness';
        $this->link='Список заболеваний';
        $this->data=$this->illness();
        $this->blogInitAlias();
        $this->render('list_illness');
    }

    public function actionIllnessSingle($id)
    {
        $this->getAError(array('id'));
        $this->layout='//layouts/column/column_single_illness';
        $this->link='Список заболеваний';
        $model=AdminNodeIllness::model()->findByAttributes(array('translit'=>$id));
        if(empty($model->id)){
            throw new CHttpException(404,"Зпрашиваемой страницы на сайте не обнаружено.");
        }
        $this->data=$this->illness(!empty($model->translit)?$model->translit:null);
        $this->metaModel($model);
        $this->render('single_illness',array('model'=>$model));
    }

    public function actionServiceList($theme=null)
    {
        $this->getAError(array('theme','page'));
        $this->link='Список услуг';
        $this->data=$this->service($theme);
        $this->blogInitAlias();
        if(!empty($theme)){
            $model=AdminCategory::model()->findByAttributes(array('translit'=>$theme));
            if(empty($model->id)){
                throw new CHttpException(404,"Зпрашиваемой страницы на сайте не обнаружено.");
            }
            $this->layout='//layouts/column/column_list_service';
            $data=AdminNodeService::model()->findAllByAttributes(array('category_id'=>$model->id));
            $this->render('service_list_theme',array('data'=>(!empty($data)?$data:array())));
        } else{
            $this->layout='//layouts/column/column_list_service';
            $this->render('service_list');
        }
    }

    public function actionServiceSingle($id)
    {
        $this->getAError(array('id','sort'));
        $this->layout='//layouts/column/column_single_service';
        $this->link='Список услуг';
        $model=AdminNodeService::model()->findByAttributes(array('translit'=>$id));
        if(empty($model->id)){
            throw new CHttpException(404,"Зпрашиваемой страницы на сайте не обнаружено.");
        }
        $theme=!empty($model->service_category->translit)?$model->service_category->translit:null;
        $this->data=$this->service($theme);
        $data=array();
        $sort=null;
        if(!empty($theme)){
            $criteria=new CDbCriteria();
            $criteria->limit=10;
            $criteria->addCondition("t.status=1");
            $criteria->addCondition("service_price_category.translit='{$theme}'");
            $sort=new CSort('AdminClinic');
            $sort->defaultOrder="CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC";
            $sort->attributes=array(
                'title'=>array(
                    'asc'=>'t.title',
                    'desc'=>'t.title DESC',
                    'label'=>'Заголовок',
                ),
                'price'=>array(
                    'asc'=>'clinic_services_s0.price',
                    'desc'=>'clinic_services_s0.price DESC',
                    'label'=>'Цена',
                ),
                'rating'=>array(
                    'asc'=>'CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.rate10',
                    'desc'=>'CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.rate10 DESC',
                    'label'=>'Рейтинг',
                ),
            );
            $criteria->with=array(
                'clinic_services_s0'=>array('with'=>array('service_price_category','service_price_service')),
            );
            $criteria->together=true;
            $criteria->group="t.id";
            $sort->applyOrder($criteria);
            $data=AdminClinic::model()->findAll($criteria);
        }
        $this->blogInitAlias();
        $this->render('service_single',array('model'=>$model,'data'=>$data,'sort'=>$sort));
    }

    public function actionHandbookList($id=null)
    {
        $this->getAError(array('id','page'));
        $this->layout='//layouts/column/column_handbook';
        $this->link='Справочник';
        if(!empty($id)){
            $model=AdminPacientCategory::model()->findByAttributes(array('translit'=>$id));
            if(!empty($id)&&empty($model->id)){
                throw new CHttpException(404,"Зпрашиваемой страницы на сайте не обнаружено.");
            }
        } else{
            $model=AdminPacientCategory::model()->find(array("order"=>'id DESC'));
        }
        $this->data=$this->handbooks($id);
        $criteria=new CDbCriteria();
        $criteria->limit=10;
        $criteria->addCondition("t.status=1");
        $criteria->order="CASE WHEN rs.rate IS NULL THEN 1 ELSE 0 END, rs.comm DESC, rs.rate DESC";
        if(!empty($model->name_translit)){
            $_category=AdminCategory::model()->findByAttributes(array('translit'=>$model->name_translit));
            if(empty($_category->translit)||(!empty($_category->level)&&$_category->level>2)){
                throw new CHttpException(404,"Категории с URL: {$model->name_translit} на сайте не обнаружено.");
            }
            $criteria->addCondition("catd_category.lft>=:lft AND catd_category.rgt<=:rgt AND catd_category.root=:root");
            $criteria->params=array(":lft"=>$_category->lft,":rgt"=>$_category->rgt,":root"=>$_category->root);
        }
        $criteria->with=array(
            'rs'=>array('select'=>false),
            'doctor_category_s'=>array('select'=>false,'with'=>array('catd_category'=>array('select'=>false))),
        );
        $criteria->together=true;
        $criteria->group="t.id";
        $data=AdminDoctor::model()->findAll($criteria);
        $this->blogInitAlias();
        $this->render('handbook',array('model'=>$model,'data'=>$data));
    }

    private function service($theme)
    {
        $criteria=new CDbCriteria();
        $criteria->select="service_category.translit AS translit, service_category.name AS title, COUNT(t.id) AS id";
        $criteria->with=array('service_category');
        $criteria->together=true;
        $criteria->group="t.category_id";
        $services=AdminNodeService::model()->findAll($criteria);
        if(!empty($services)){
            foreach($services as $value){
                $data[]=array(
                    'translit'=>!empty($value['translit'])?$value['translit']:'',
                    'title'=>!empty($value['title'])?$value['title']:'',
                    'count'=>!empty($value['id'])?$value['id']:'',
                    'active'=>!empty($theme)&&(!empty($value['name']))&&($theme==$value['translit'])?true:false,
                );
            }
        }
        return $data;
    }

    private function themes($theme)
    {
        $data=array();
        $themes=AdminNodeFaqCategory::model()->findAll(array('order'=>'title'));
        if(!empty($themes)){
            foreach($themes as $value){
                $data[]=array(
                    'translit'=>$value['translit'],
                    'title'=>$value['title'],
                    'active'=>!empty($theme)&&($theme==$value['translit'])?true:false,
                );
            }
        }
        return $data;
    }

    private function illness($theme=null)
    {
        $data=array();
        $criteria=new CDbCriteria();
        $criteria->select='title, translit';
        $criteria->order="id DESC";
        $illnesses=AdminNodeIllness::model()->findAll($criteria);
        if(!empty($illnesses)){
            foreach($illnesses as $value){
                $data[]=array(
                    'translit'=>$value['translit'],
                    'title'=>$value['title'],
                    'active'=>!empty($theme)&&($theme==$value['translit'])?true:false,
                );
            }
        }
        return $data;
    }

    private function handbooks($theme)
    {
        $data=array();
        $themes=AdminPacientCategory::model()->findAll(array('order'=>'name'));
        if(!empty($themes)){
            foreach($themes as $value){
                $data[]=array(
                    'translit'=>$value['translit'],
                    'count'=>!empty($value['category_node_s'])?count($value['category_node_s']):'',
                    'title'=>$value['name'],
                    'active'=>!empty($theme)&&($theme==$value['translit'])?true:false,
                );
            }
        }
        return $data;
    }
    
    private function metaModel($model)
    {
        if(!empty($model->id)){
            $this->pageDescription=!empty($model->meta_description)?$model->meta_description:'';
            $this->pageKeywords=!empty($model->meta_keywords)?$model->meta_keywords:'';
            $this->pageTitle=!empty($model->meta_title)?$model->meta_title:$model->title;
            if(empty($this->pageCanonical)&&!empty($model->url_canonical)){
                $this->pageCanonical=$model->url_canonical;
            }
            $this->breadcrumbs[]=$model->title;
        }
    }

    private function metaModelTitle($model)
    {
        if(!empty($model->id)){
            $this->pageDescription=!empty($model->meta_description)?$model->meta_description:'';
            $this->pageKeywords=!empty($model->meta_keywords)?$model->meta_keywords:'';
            if(empty($this->pageCanonical)&&!empty($model->url_canonical)){
                $this->pageCanonical=$model->url_canonical;
            }
            $this->pageTitle=$model->title." - ".Yii::app()->name;
            $this->breadcrumbs[]=$model->title;
        }
    }    

}
