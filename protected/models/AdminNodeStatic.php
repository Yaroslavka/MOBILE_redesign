<?php

class AdminNodeStatic extends CActiveRecord
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
            array('title','required','message'=>'Укажите название категории'),
            array('subtitle1, subtitle2, title, meta_title, meta_keywords, translit','length','max'=>255),
            array('description, underdescription, item1, item2, list1, list2, body ,meta_description','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('id, translit, title, meta_description meta_title, meta_keywords','safe','on'=>'search'),
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
                    'underdescription',
                    'body',
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                    'translit',
                    'subtitle1',
                    'subtitle2',
                    'item1',
                    'item2',
                    'list1',
                    'list2',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Название категории',
            'description'=>'Кратко',
            'underdescription'=>'Текст',
            'body'=>'Содержание',
            'meta_title'=>'МЕТА :заголовок',
            'meta_keywords'=>'МЕТА :слова',
            'meta_description'=>'МЕТА :описание',
            'translit'=>'Транслит',
            'subtitle1'=>'Заголовок 1',
            'subtitle2'=>'Заголовок 2',
            'item1'=>'Инфо 1',
            'item2'=>'Инфо 2',
            'list1'=>'Список 1',
            'list2'=>'Список 2',
        );
    }

}
