<?php

class AdminLandChildCategory extends CActiveRecord
{

    private $_categoty_name;

    public function getCategory_name()
    {
        if(!empty($this->category_id)){
            $model= AdminChildCategory::model()->findByPk($this->category_id);
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
        $query = Yii::app()->db->createCommand('SELECT COUNT(*) FROM `child_doctor` d LEFT JOIN `child_category_doctor` cd ON(d.id=cd.doctor_id) WHERE cd.category_id='.(int)$this->category_id.' AND d.status=1 AND d.house=1')->queryScalar();
        return $query;
    }
    
    public function getMediumPrice(){
        $query = Yii::app()->db->createCommand('SELECT cd.price,ccd.price as price2  FROM  `child_doctor` cd LEFT JOIN `child_category_doctor`  ccd  ON(cd.id=ccd.doctor_id) WHERE ccd.category_id='.(int)$this->category_id.' AND cd.house=1 GROUP BY cd.id')->queryAll();
        $price=0;
        if(!empty($query)){
            foreach ($query as $value){
                $price+=!empty($value['price'])?$value['price']:$value['price2'];
            }
            if(!empty($price)){
              $price= ceil($price/$this->countDoctor);  
            }
        }
        return $price;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'land_child_category';
    }

    public function rules()
    {
        return array(
            array('land_id, category_id','required'),
            array('category_id, land_id ','numerical','integerOnly'=>true),
            array('category_id','exist','attributeName'=>'id','className'=>'AdminChildCategory'),
            array('land_id','exist','attributeName'=>'id','className'=>'AdminLand'),
        );
    }

    public function relations()
    {
        return array(
            'land_child_category_land'=>array(self::BELONGS_TO,'AdminLand','land_id'),
            'land_child_category_category'=>array(self::BELONGS_TO,'AdminChildCategory','category_id'),
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
