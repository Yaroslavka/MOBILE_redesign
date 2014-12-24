<?php

class AdminCategoryDiagnosticService extends CActiveRecord
{

    

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'category_diagnostic_service';
    }

    public function rules()
    {
        return array(
            array('title','required','message'=>'Название диагностики'),
            array('url','required','message'=>'Ссылка на диагностику'),
            array('category_id','exist','attributeName'=>'id','className'=>'AdminCategory','message'=>'Категори'),
            array('category_id','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            'cds_category'=>array(self::BELONGS_TO,'AdminCategory','category_id'),
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
                    'title',
                    'url',
                    'category_id',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Название',
            'category_id'=>'Доктор ID',
            'url'=>'Ссылка на страницу',
        );
    }

}
