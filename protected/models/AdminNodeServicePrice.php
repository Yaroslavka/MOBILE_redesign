<?php

class AdminNodeServicePrice extends CActiveRecord
{

    private $_service_name;

    public function getService_name()
    {
        if(!empty($this->service_id)){
            $model=AdminNodeService::model()->findByPk($this->service_id);
            if(!empty($model->name)){
                $this->_service_name=$model->name;
            }
        }
        return $this->_service_name;
    }

    public function setService_name($value)
    {
        $this->_service_name=$value;
    }

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

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'node_service_price';
    }

    public function rules()
    {
        return array(
            array('price,clinic_id','required'),
            array('price','length','max'=>255),
            array('service_id, status, category_id, clinic_id','numerical','integerOnly'=>true),
            array('clinic_id','exist','attributeName'=>'id','className'=>'AdminClinic'),
            array('service_id','exist','attributeName'=>'id','className'=>'AdminNodeService'),
        );
    }

    public function relations()
    {
        return array(
            'service_price_category'=>array(self::BELONGS_TO,'AdminCategory','category_id'),
            'service_price_clinic'=>array(self::BELONGS_TO,'AdminClinic','clinic_id'),
            'service_price_service'=>array(self::BELONGS_TO,'AdminNodeService','service_id'),
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
                    'service_id',
                    'price',
                    'status',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'price'=>'Стоимость',
            'status'=>'Запрашивание цены',
            'clinic_id'=>'ID клиники',
            'category_id'=>'ID Категория',
            'service_id'=>'ID Услуги',
            'service_name'=>'Услуга',
            'category_name'=>'Категория',
            'clinic_name'=>'Клиника',
        );
    }

}
