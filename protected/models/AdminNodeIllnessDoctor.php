<?php

class AdminNodeIllnessDoctor extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'node_illness_doctor';
    }

    public function rules()
    {
        return array(
            array('illness_id','exist','attributeName'=>'id','className'=>'AdminNodeIllness'),
            array('doctor_id','exist','attributeName'=>'id','className'=>'AdminDoctor'),
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
                    'illness_id',
                    'doctor_id',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'illness_doctor'=>array(self::BELONGS_TO,'AdminDoctor','doctor_id'),
            'illness_illness'=>array(self::BELONGS_TO,'AdminNodeIllness','illness_id'),
        );
    }

}
