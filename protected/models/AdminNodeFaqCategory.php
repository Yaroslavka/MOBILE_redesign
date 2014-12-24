<?php

class AdminNodeFaqCategory extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'node_faq_category';
    }

    public function rules()
    {
        return array(
            array('title','required','message'=>'Укажите название категории'),
            array('title, meta_title, meta_keywords, translit','length','max'=>255),
            array('description ,meta_description','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser'),
            array('id, translit, meta_description meta_title, meta_keywords','safe','on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
            'faq_s'=>array(self::HAS_MANY,'AdminNodeFaq','theme_id'),
            'faq_uid'=>array(self::BELONGS_TO,'AdminUser','uid'),
        );
    }

    public function behaviors()
    {
        return array(
            'TranslitBehavior'=>array(
                'class'=>'ext.sprutlab.translit.TranslitBehavior',
                'name'=>'title',
                'model'=>__CLASS__,
                'translit'=>'translit',
            ),
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.HistoryBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'title',
                    'description',
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
            'title'=>'Название категории',
            'description'=>'Описание категории',
            'meta_title'=>'МЕТА :заголовок',
            'meta_keywords'=>'МЕТА :слова',
            'meta_description'=>'МЕТА :описание',
            'translit'=>' Транслит',
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
