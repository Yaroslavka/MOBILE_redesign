<?php

class AdminTimeslot extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'timeslot';
    }

    public function rules()
    {
        return array(
            array('dcid, start_date, end_date','required'),
            array('update_time','default','setOnEmpty'=>true,'value'=>null),
            array('start_date, end_date, update_time, create_time','safe'),
            array('dcid','exist','attributeName'=>'id','className'=>'AdminClinicDoctor'),
            array('dcid','numerical','integerOnly'=>true),
        );
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior'=>array(
                'class'=>'zii.behaviors.CTimestampBehavior',
                'createAttribute'=>'create_time',
                'updateAttribute'=>'update_time',
            ),
        );
    }

    public function relations()
    {
        return array(
            'dc'=>array(self::BELONGS_TO,'AdminClinicDoctor','dcid'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'create_time'=>'Время создания',
            'update_time'=>'Время редактирования',
            'start_date'=>'Начало приема',
            'end_date'=>'Конец приема',
            'dcid'=>'Клиника-Доктор',
        );
    }

}
