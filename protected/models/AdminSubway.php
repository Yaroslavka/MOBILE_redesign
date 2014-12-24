<?php

class AdminSubway extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'subway';
    }

    public function rules()
    {
        return array(
            array('subway_line_id','required','message'=>'Не выбрана ветка метро'),
            array('title','required','message'=>'Название станции обязательное поле'),
            array('title','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('title, translit','length','max'=>255),
            array('lat, lng','numerical'),
            array('subway_line_id','numerical','integerOnly'=>true),
            array('district_id','exist','attributeName'=>'id','className'=>'AdminDistrict','message'=>'Район'),
            array('subway_line_id','exist','attributeName'=>'id','className'=>'AdminSubwayLine','message'=>'Ветка метро не обнаружена'),
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
                    'lat',
                    'subway_line_id',
                    'district_id',
                    'translit',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'line'=>array(self::BELONGS_TO,'AdminSubwayLine','subway_line_id'),
            'dline'=>array(self::BELONGS_TO,'AdminDistrict','district_id'),
            'subclinics'=>array(self::HAS_MANY,'AdminSubwayClinic','subway_id'),
            'subdiagnostics'=>array(self::HAS_MANY,'AdminDiagnosticSubwayClinic','subway_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Станция',
            'lat'=>'Широта',
            'lng'=>'Долгота',
            'subway_line_id'=>'Ветка метро ID',
            'district_id'=>'Округ ID',
            'translit'=>'Транслитерация',
        );
    }

}
