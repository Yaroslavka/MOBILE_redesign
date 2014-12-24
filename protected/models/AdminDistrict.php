<?php

class AdminDistrict extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'district';
    }

    public function rules()
    {
        return array(
            array('district_line_id','required'),
            array('title','required','message'=>'Название станции обязательное поле'),
            array('title','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('title, translit','length','max'=>255),
            array('district_line_id','numerical','integerOnly'=>true),
            array('district_line_id','exist','attributeName'=>'id','className'=>'AdminDistrictLine'),
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
                    'district_line_id',
                    'translit',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'dline'=>array(self::BELONGS_TO,'AdminDistrictLine','district_line_id'),
            'clinic_lines'=>array(self::HAS_MANY,'AdminClinic','district_id'),
            'diagnostic_lines'=>array(self::BELONGS_TO,'AdminDiagnostic','district_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Район',
            'district_line_id'=>'Округ ID',
            'translit'=>'Транслитерация',
        );
    }

}
