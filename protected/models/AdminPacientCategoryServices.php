<?php

class AdminPacientCategoryServices extends CActiveRecord
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

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pacient_category_service';
    }

    public function rules()
    {
        return array(
            array('cat_id','required'),
            array('service_id','required'),
            array('cat_id','exist','attributeName'=>'id','className'=>'AdminPacientCategory'),
            array('service_id','exist','attributeName'=>'id','className'=>'AdminNodeService'),
            array('cat_id, service_id','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            'p_s'=>array(self::BELONGS_TO,'AdminNodeService','service_id'),
            'p_c'=>array(self::BELONGS_TO,'AdminPacientCategory','cat_id'),
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
                    'cat_id',
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
            'cat_id'=>'Категория ID',
        );
    }

}