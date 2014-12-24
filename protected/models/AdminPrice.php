<?php

class AdminPrice extends CActiveRecord
{

    private $_clinic_name;
    public $check;
    
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

    private $_category_name;

    public function getCategory_name()
    {
        if(!empty($this->category_id)){
            $model=AdminCategory::model()->findByPk($this->category_id);
            if(!empty($model->name)){
                $this->_category_name=$model->name;
            }
        }
        return $this->_category_name;
    }

    public function setCategory_name($value)
    {
        $this->_category_name=$value;
    }

    private $_clinic_name1;

    public function getClinic_name1()
    {
        return $this->_clinic_name1;
    }

    public function setClinic_name1($value)
    {
        $this->_clinic_name1=$value;
    }

    private $_doctor_name1;

    public function getDoctor_name1()
    {
        return $this->_doctor_name1;
    }

    public function setDoctor_name1($value)
    {
        $this->_doctor_name1=$value;
    }

    private $_category_name1;

    public function getCategory_name1()
    {
        return $this->_category_name1;
    }

    public function setCategory_name1($value)
    {
        $this->_category_name1=$value;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'price';
    }
    
    public function beforeSave() {
        if(!empty($this->price_action)){
            if($this->check==2){
                $this->price_action = ceil($this->price-(($this->price * $this->price_action)/100));
            }
        }
        return parent::beforeSave();
    }

    public function rules()
    {
        return array(
            array('doctor_id','required'),
            array('clinic_id','required'),
            array('category_id','required'),
            array('clinic_id','exist','attributeName'=>'id','className'=>'AdminClinic'),
            array('doctor_id','exist','attributeName'=>'id','className'=>'AdminDoctor'),
            array('category_id','exist','attributeName'=>'id','className'=>'AdminCategory'),
            array('doctor_id, category_id, clinic_id, check','numerical','integerOnly'=>true),
            array('price, price_action','length','max'=>255),
        );
    }

    public function relations()
    {
        return array(
            'clinic'=>array(self::BELONGS_TO,'AdminClinic','clinic_id'),
            'doctor'=>array(self::BELONGS_TO,'AdminDoctor','doctor_id'),
            'category'=>array(self::BELONGS_TO,'AdminCategory','category_id'),
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
                    'category_id',
                    'clinic_id',
                    'doctor_id',
                    'price',
                    'price_action',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'clinic_id'=>'Клиника ID',
            'clinic_name'=>'Клиника (название)',
            'doctor_id'=>'Доктор ID',
            'doctor_name'=>'Доктор (ФИО)',
            'category_id'=>'Категория ID',
            'category_name'=>'Категория (название)',
            'price'=>'Стоимость',
            'price_action'=>'Аукционная Стоимость',
            'check'=>'Условия акции',
        );
    }

}
