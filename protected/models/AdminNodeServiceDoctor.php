<?php

class AdminNodeServiceDoctor extends CActiveRecord
{

    private $_service_name;

    public function getService_name()
    {
        if(!empty($this->service_id)){
            $model=AdminNodeService::model()->findByPk($this->service_id);
            if(!empty($model->title)){
                $this->_service_name=$model->title;
            }
        }
        return $this->_service_name;
    }

    public function setService_name($value)
    {
        $this->_service_name=$value;
    }

    private $_doctor_name;

    public function getDoctor_name()
    {
        if(!empty($this->doctor_id)){
            $model=AdminDoctor::model()->findByPk($this->doctor_id);
            if(!empty($model->fio)){
                $this->_doctor_name=$model->fio;
            }
        }
        return $this->_doctor_name;
    }

    public function setDoctor_name($value)
    {
        $this->_doctor_name=$value;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'node_service_doctor';
    }

    public function rules()
    {
        return array(
            array('doctor_id','required'),
            array('service_id','required'),
            array('doctor_id','exist','attributeName'=>'id','className'=>'AdminDoctor'),
            array('service_id','exist','attributeName'=>'id','className'=>'AdminNodeService'),
            array('doctor_id, service_id','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            's'=>array(self::BELONGS_TO,'AdminNodeService','service_id'),
            'd'=>array(self::BELONGS_TO,'AdminDoctor','doctor_id'),
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
                    'doctor_id',
                    'service_id',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'service_id'=>'Услуга ID',
            'service_name'=>'Услуга',
            'doctor_id'=>'Доктор ID',
            'doctor_name'=>'Доктор ',
        );
    }

}