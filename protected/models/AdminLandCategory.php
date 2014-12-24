<?php

class AdminLandCategory extends CActiveRecord
{

    private $_categoty_name;

    public function getCategory_name()
    {
        if(!empty($this->category_id)){
            $model=  AdminCategory::model()->findByPk($this->category_id);
            if(!empty($model->name)){
                $this->_categoty_name=$model->name;
            }
        }
        return $this->_categoty_name;
    }

    public function setCategory_name($value)
    {
        $this->_service_name=$value;
    }
    
    public function getCountDoctor(){
        $query = Yii::app()->db->createCommand('SELECT COUNT(*) FROM `doctor` d LEFT JOIN `category_doctor` cd ON(d.id=cd.doctor_id) WHERE cd.category_id='.(int)$this->category_id.' AND d.status=1 AND d.house=1')->queryScalar();
        return $query;
    }
    
    public function getMediumPrice(){
        $query = Yii::app()->db->createCommand('SELECT COUNT(*) as qty , SUM(p.price) as price FROM `price` p LEFT JOIN `doctor` d ON (p.doctor_id=d.id)  WHERE p.category_id='.(int)$this->category_id.' AND d.house=1 GROUP BY p.id')->queryAll();
        $price=0;
        if(!empty($query[0]['qty']) && !empty($query[0]['price'])){
            $price = ceil($query[0]['price']/$query[0]['qty']);
        }
        return $price;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'land_category';
    }

    public function rules()
    {
        return array(
            array('land_id, category_id','required'),
            array('category_id, land_id ','numerical','integerOnly'=>true),
            array('category_id','exist','attributeName'=>'id','className'=>'AdminCategory'),
            array('land_id','exist','attributeName'=>'id','className'=>'AdminLand'),
        );
    }

    public function relations()
    {
        return array(
            'land_category_land'=>array(self::BELONGS_TO,'AdminLand','land_id'),
            'land_category_category'=>array(self::BELONGS_TO,'AdminCategory','category_id'),
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
                    'land_id',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'category_id'=>'ID Категория',
            'land_id'=>'ID Ленда',
            'category_name'=>'Категория',
        );
    }

}
