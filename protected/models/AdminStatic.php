<?php

class AdminStatic extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'node_static';
    }

    public function rules()
    {
        return array(
            array('title','required','message'=>'Укажите название'),
            array('description','required','message'=>'Заполните описание'),
            array('body','required','message'=>'Заполните содержание'),
            array('title','length','max'=>255),
            array('description, body, meta_title, meta_description, meta_keywords','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('id, update_time, create_time, meta_title, meta_description, meta_keywords','safe','on'=>'search'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Название',
            'description'=>'Мини описание',
            'create_time'=>'Время создания',
            'update_time'=>'Время редактирования',
            'body'=>'Содержание',
            'meta_title'=>'МЕТА :заголовок',
            'meta_keywords'=>'МЕТА :слова',
            'meta_description'=>'МЕТА :описание',
        );
    }

}
