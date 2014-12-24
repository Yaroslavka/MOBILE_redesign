<?php

class AdminAlias extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'alias';
    }

    public function rules()
    {
        return array(
            array('url','required','message'=>'Укажите Url'),
            array('url, price, url_canonical, meta_title, h1','length','max'=>255),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser','message'=>'Учетной записи пользователя не существует'),
            array('meta_keywords, meta_description, top, bottom, middle','safe'),
            array('id, price, meta_keywords, meta_description, top, bottom, middle, price, meta_title,h1','safe','on'=>'search'),
        );
    }

    public function behaviors()
    {
        return array(
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.HistoryBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'url',
                    'url_canonical',
                    'price',
                    'h1',
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                    'top',
                    'middle',
                    'bottom',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'alias_uid'=>array(self::BELONGS_TO,'AdminUser','uid'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'url_canonical'=>'Канонический',
            'price'=>'Стоимость',
            'h1'=>'H1',
            'meta_title'=>'МЕТА :заголовок',
            'meta_keywords'=>'МЕТА :слова',
            'meta_description'=>'МЕТА :описание',
            'top'=>'Сверху',
            'middle'=>'По центру',
            'bottom'=>'Снизу',
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
