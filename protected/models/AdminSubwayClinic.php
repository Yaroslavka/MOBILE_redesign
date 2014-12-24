<?php

class AdminSubwayClinic extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'subway_clinic';
    }

    public function rules()
    {
        return array(
            array('subway_id, clinic_id','required'),
            array('lin1, lin2','safe'),
            array('clinic_id','exist','attributeName'=>'id','className'=>'AdminClinic','message'=>'Клиника выбрана не верно'),
            array('subway_id','exist','attributeName'=>'id','className'=>'AdminSubway','message'=>'Доктор выбран не верно'),
            array('subway_id, clinic_id','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            'subway_clinic'=>array(self::BELONGS_TO,'AdminClinic','clinic_id'),
            'sub'=>array(self::BELONGS_TO,'AdminSubway','subway_id'),
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
                    'lin1',
                    'lin2',
                    'clinic_id',
                    'subway_id',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'lin1'=>'Пешком (мин.)',
            'lin2'=>'Авто (мин.)',
            'clinic_id'=>'Клиника',
            'subway_id'=>'Метро',
        );
    }

}
