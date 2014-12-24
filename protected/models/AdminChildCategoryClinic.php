<?php

class AdminChildCategoryClinic extends CActiveRecord
{

    private $_parent_name;

    public function getParent_name()
    {
        if(!empty($this->parent_id)){
            $model=AdminChildCategoryClinic::model()->findByPk($this->parent_id);
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
            $model=AdminChildClinic::model()->findByPk($this->clinic_id);
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
        if(!empty($this->category_id)){
            $model=AdminChildCategory::model()->findByPk($this->category_id);
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
        return 'child_category_clinic';
    }

    public function rules()
    {
        return array(
            array('category_id','required','message'=>'Категория не выбрана'),
            array('category_id','exist','attributeName'=>'id','className'=>'AdminChildCategory','message'=>'Услуги не обнаружено'),
            array('clinic_id','exist','attributeName'=>'id','className'=>'AdminChildClinic','message'=>'Клиника не обнаружена'),
            array('parent_id','exist','attributeName'=>'id','className'=>'AdminChildCategoryClinic','message'=>'Родительская услуга не обнаружена'),
            array('parent_id, category_id','default','setOnEmpty'=>true,'value'=>null),
            array('name','length','max'=>255),
            array('price','numerical','message'=>'Стоимость должна быть числом'),
        );
    }

    public function relations()
    {
        return array(
            'catc_clinic'=>array(self::BELONGS_TO,'AdminChildClinic','clinic_id'),
            'catc_service'=>array(self::BELONGS_TO,'AdminChildCategoryClinic','parent_id'),
            'catc_category'=>array(self::BELONGS_TO,'AdminChildCategory','category_id'),
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
                    'parent_id',
                    'price',
                    'name',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'category_id'=>'Категория ID',
            'clinic_id'=>'Клиника ID',
            'parent_id'=>'Услуга ID',
            'price'=>'Стоимость',
            'name'=>'Название',
        );
    }

}
