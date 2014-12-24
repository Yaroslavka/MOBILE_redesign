<?php

class AdminDiagnosticPrice extends CActiveRecord
{

    private $_parent_name;

    public function getParent_name()
    {
        if(!empty($this->parent_id)){
            $model=AdminDiagnosticPrice::model()->findByPk($this->parent_id);
            if(!empty($model->name)){
                $this->_parent_name=$model->name;
            }
        }
        return $this->_parent_name;
    }

    public function setParent_name($value)
    {
        $this->_parent_name=$value;
    }

    private $_clinic_name;

    public function getClinic_name()
    {
        if(!empty($this->clinic_id)){
            $model=AdminDiagnosticClinic::model()->findByPk($this->clinic_id);
            if(!empty($model->name)){
                $this->_clinic_name=$model->name;
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
        if(!empty($this->category_price_id)){
            $model=AdminDiagnosticCategory::model()->findByPk($this->category_price_id);
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
        return 'clinic_price';
    }

    public function rules()
    {
        return array(
            array('clinic_id','required'),
            array('category_price_id','exist','attributeName'=>'id','className'=>'AdminDiagnosticCategory','message'=>'Услуги не обнаружено'),
            array('clinic_id','exist','attributeName'=>'id','className'=>'AdminDiagnosticClinic','message'=>'Клиника не обнаружена'),
            array('parent_id','exist','attributeName'=>'id','className'=>'AdminDiagnosticPrice','message'=>'Родительская услуга не обнаружена'),
            array('parent_id, category_price_id','default','setOnEmpty'=>true,'value'=>null),
            array('name, sames','length','max'=>255),
            array('price, old_price','numerical'),
            array('status, category_price_id, clinic_id, parent_id','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            'catc_clinic'=>array(self::BELONGS_TO,'AdminDiagnosticClinic','clinic_id'),
            'catc_service'=>array(self::BELONGS_TO,'AdminDiagnosticPrice','parent_id'),
            'catc_services'=>array(self::HAS_MANY,'AdminDiagnosticPrice','parent_id'),
            'catc_category'=>array(self::BELONGS_TO,'AdminDiagnosticCategory','category_price_id'),
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
                    'category_price_id',
                    'clinic_id',
                    'parent_id',
                    'price',
                    'old_price',
                    'status',
                    'sames',
                    'name',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'category_price_id'=>'Категория ID',
            'clinic_id'=>'Клиника ID',
            'parent_id'=>'Услуга ID',
            'price'=>'Стоимость',
            'old_price'=>'Стоимость - старая',
            'status'=>'Статус',
            'name'=>'Название',
            'sames'=>'Группа',
        );
    }

}
