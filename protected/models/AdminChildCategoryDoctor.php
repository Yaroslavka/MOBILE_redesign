<?php

class AdminChildCategoryDoctor extends CActiveRecord
{

    private $_parent_name;

    public function getParent_name()
    {
        if(!empty($this->parent_id)){
            $model=AdminChildCategoryDoctor::model()->findByPk($this->parent_id);
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

    private $_doctor_name;

    public function getDoctor_name()
    {
        if(!empty($this->doctor_id)){
            $model=AdminChildDoctor::model()->findByPk($this->doctor_id);
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
        return 'child_category_doctor';
    }

    public function rules()
    {
        return array(
            array('category_id','required','message'=>'Категория не выбран'),
            array('category_id','exist','attributeName'=>'id','className'=>'AdminChildCategory','message'=>'Услуги не обнаружено'),
            array('doctor_id','exist','attributeName'=>'id','className'=>'AdminChildDoctor','message'=>'Клиника не обнаружена'),
            array('parent_id','exist','attributeName'=>'id','className'=>'AdminChildCategoryDoctor','message'=>'Родительская услуга не обнаружена'),
            array('parent_id, category_id','default','setOnEmpty'=>true,'value'=>null),
            array('name, price','length','max'=>255),
           // array('price','numerical','message'=>'Стоимость должна быть числом'),
        );
    }

    public function relations()
    {
        return array(
            'catd_category'=>array(self::BELONGS_TO,'AdminChildCategory','category_id'),
            'catd_doctor'=>array(self::BELONGS_TO,'AdminChildDoctor','doctor_id'),
            'catd_service'=>array(self::BELONGS_TO,'AdminChildCategoryDoctor','parent_id'),
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
                    'doctor_id',
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
            'doctor_id'=>'Доктор ID',
            'parent_id'=>'Услуга ID',
            'price'=>'Стоимость',
            'name'=>'Название',
        );
    }

}
