<?php

class AdminNodeIllness extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    private $_themes;

    public function getThemes()
    {
        $this->_themes=array();
        if(!empty($this->theme_id)){
            $this->_themes=AdminNodeIllness::model()->findAllByAttributes(array('theme_id'=>$this->theme_id));
        }
        return $this->_themes;
    }

    public function getDoctorsSame()
    {
        $data=array();
        if(!empty($this->translit)){
            $model=AdminNodeIllnessAlias::model()->findByAttributes(array('theme_translit'=>$this->translit));
            if(!empty($model->category_translit)){
                $saveCriteria='';
                $saveParams=array();
                $criteria=new CDbCriteria();
                $saveCriteria['status']="t.status=1";
                $sort=new CSort('AdminDoctor');
                $sort->defaultOrder="CASE WHEN t.image IS NULL THEN 1 ELSE 0 END, CASE WHEN rs.rate IS NULL THEN 1 ELSE 0 END, rs.comm DESC, rs.rate DESC, CASE WHEN doctor_category_s.price IS NULL THEN 1 ELSE 0 END, doctor_category_s.price ASC, CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(c.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(c.lng))+sin(radians(c.lat))*sin(radians(sub.lat))) ASC";
                if(!empty($model->category_translit)){
                    $_category=AdminCategory::model()->findByAttributes(array('translit'=>$model->category_translit));
                    if(!empty($_category->translit)&&!empty($_category->level)&&$_category->level<=2){
                        $saveCriteria['category']="catd_category.lft>=:lft AND catd_category.rgt<=:rgt AND catd_category.root=:root";
                        $saveParams['category']=array(":lft"=>$_category->lft,":rgt"=>$_category->rgt,":root"=>$_category->root);
                    }
                }
                $criteria->with=array(
                    'rs'=>array('select'=>false),
                    'doctor_clinic_s'=>array('select'=>false,'with'=>array('c'=>array('select'=>false,'with'=>array('clinic_subway_s'=>array('select'=>false,'with'=>array('sub'=>array('select'=>'title, translit','with'=>array('line')))),'clinic_category_s'=>array('select'=>false))))),
                    'doctor_category_s'=>array('select'=>false,'with'=>array('catd_category'=>array('select'=>'name'))),
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
                $count=AdminDoctor::model()->count($criteria);
                $sort->applyOrder($criteria);
                $pages=new CPagination($count);
                if(!empty($model->depth)){
                    $pages->currentPage=$model->depth;
                }
                $pages->applyLimit($criteria);
                $_data=AdminDoctor::model()->findAll($criteria);
                if(!empty($_data)){
                    $data['data']=$_data;
                    $data['translit']=$model->category_translit;
                }
            }
        }
        return $data;
    }

    public function tableName()
    {
        return 'node_illness';
    }

    public function rules()
    {
        return array(
            array('title','required','message'=>'Укажите название'),
            array('description','required','message'=>'Заполните описание'),
            array('body','required','message'=>'Заполните содержание'),
            array('title, url_canonical, translit, specialist, image','length','max'=>255),
            array('status, category_id','numerical','integerOnly'=>true),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser'),
            array('category_id','exist','attributeName'=>'id','className'=>'AdminCategory'),
            array('theme_id','exist','attributeName'=>'id','className'=>'AdminNodeIllnessCategory'),
            array('meta_title, meta_description,meta_keywords','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('description, body','safe'),
            array('id, status, update_time, create_time, meta_title, meta_description, meta_keywords, translit','safe','on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
            'user_uid'=>array(self::BELONGS_TO,'AdminUser','uid'),
            'cat_s'=>array(self::BELONGS_TO,'AdminNodeIllnessCategory','theme_id'),
            'illness_category'=>array(self::BELONGS_TO,'AdminCategory','category_id'),
            'illness_doctor_s'=>array(self::HAS_MANY,'AdminNodeIllnessDoctor','illness_id','limit'=>10,'with'=>array('illness_doctor'),'condition'=>'illness_doctor.status=1'),
        );
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior'=>array(
                'class'=>'zii.behaviors.CTimestampBehavior',
                'createAttribute'=>'create_time',
                'updateAttribute'=>null,
            ),
            'TranslitBehavior'=>array(
                'class'=>'ext.sprutlab.translit.TranslitBehavior',
                'name'=>'title',
                'model'=>__CLASS__,
                'translit'=>'translit',
            ),
            'ImageBehavior'=>array(
                'class'=>'ext.sprutlab.image.ImageBehavior',
                'versions'=>array(
                    'photo'=>array(
                        'centeredpreview'=>array(860,575),
                    ),
                ),
            ),
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.HistoryBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'category_id',
                    'url_canonical',
                    'title',
                    'description',
                    'theme_id',
                    'status',
                    'uid',
                    'update_time',
                    'body',
                    'image',
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                    'translit',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'category_id'=>'Категория ID',
            'url_canonical'=>'Канонический',
            'title'=>'Название',
            'description'=>'Мини описание',
            'theme_id'=>'Категория',
            'status'=>'Статус публикации',
            'uid'=>'Пользователь ID',
            'create_time'=>'Время создания',
            'update_time'=>'Время редактирования',
            'body'=>'Содержание',
            'meta_title'=>'МЕТА :заголовок',
            'meta_keywords'=>'МЕТА :слова',
            'meta_description'=>'МЕТА :описание',
            'translit'=>' Транслит',
            'image'=>' Фото',
        );
    }

    public function beforeValidate()
    {
        if(Yii::app()->user->id){
            $this->uid=Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }

}
