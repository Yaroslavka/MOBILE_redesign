<?php

class AdminDistrictLine extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'district_line';
    }

    public function rules()
    {
        return array(
            array('title','required'),
            array('title','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('title','length','max'=>255),
        );
    }

    public function relations()
    {
        return array(
            'districts'=>array(self::HAS_MANY,'AdminDistrict','district_line_id'),
            'clinic_districts'=>array(self::HAS_MANY,'AdminClinic','district_ line_id'),
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
                    'translit',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Округ',
            'translit'=>'URL: транслитерации',
        );
    }

}
