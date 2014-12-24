<?php

class AdminClinicDoctor extends CActiveRecord
{

    public $apiMode=1;
    private $_clinic_name;

    public function getClinic_name()
    {
        if(!empty($this->clinic_id)){
            $model=AdminClinic::model()->findByPk($this->clinic_id);
            if(!empty($model->title)){
                $this->_clinic_name=$model->title;
            }
        }
        return $this->_clinic_name;
    }

    public function setClinic_name($value)
    {
        $this->_clinic_name=$value;
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
        return 'clinic_doctor';
    }

    public function rules()
    {
        return array(
            array('doctor_id','required','message'=>'Доктор не выбран'),
            array('clinic_id','required','message'=>'Клиника не выбрана'),
            array('clinic_id','exist','attributeName'=>'id','className'=>'AdminClinic','message'=>'Клиника выбрана не верно'),
            array('doctor_id','exist','attributeName'=>'id','className'=>'AdminDoctor','message'=>'Доктор выбран не верно'),
            array('doctor_id, clinic_id','numerical','integerOnly'=>true),
            array('speciality, position','length','max'=>255),
        );
    }

    public function relations()
    {
        return array(
            'c'=>array(self::BELONGS_TO,'AdminClinic','clinic_id'),
            'd'=>array(self::BELONGS_TO,'AdminDoctor','doctor_id'),
            'dc_s'=>array(self::HAS_MANY,'AdminTimeslot','dcid'),
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
                    'clinic_id',
                    'speciality',
                    'position',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'clinic_id'=>'Клиника ID',
            'doctor_id'=>'Доктор ID',
            'speciality'=>'Специальность',
            'position'=>'Должность',
        );
    }

}
