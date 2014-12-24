<?php

class AdminDoctorAdd extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'doctor_add';
    }

    public function rules()
    {
        return array(
            array('nid','required'),
            array('nid','exist','attributeName'=>'id','className'=>'AdminDcotor'),
            array('prices','safe'),
        );
    }

    public function relations()
    {
        return array(
            'ds_s'=>array(self::BELONGS_TO,'AdminDcotor','nid'),
        );
    }

}
