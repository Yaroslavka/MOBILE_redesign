<?php

class AdminCity extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'city';
    }

    public function rules()
    {
        return array(
            array('city_line_id','required'),
            array('title','required','message'=>'обязательное поле'),
            array('title','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('title, translit','length','max'=>255),
            array('city_line_id','numerical','integerOnly'=>true),
            array('city_line_id','exist','attributeName'=>'id','className'=>'AdminCityLine'),
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
                    'city_line_id',
                    'translit',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'city_line'=>array(self::BELONGS_TO,'AdminCityLine','city_line_id'),
            'city_clinic'=>array(self::HAS_MANY,'AdminClinic','city_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Город',
            'city_line_id'=>'Облать',
            'translit'=>'Транслитерация',
        );
    }

}
