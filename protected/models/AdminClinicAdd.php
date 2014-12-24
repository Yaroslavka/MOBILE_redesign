<?php

class AdminClinicAdd extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'clinic_add';
    }

    public function rules()
    {
        return array(
            array('nid','required'),
            array('nid','exist','attributeName'=>'id','className'=>'AdminClinic'),
            array('services, prices','safe'),
        );
    }

    public function relations()
    {
        return array(
            'as_s'=>array(self::BELONGS_TO,'AdminClinic','nid'),
        );
    }

}
