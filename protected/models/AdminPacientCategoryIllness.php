<?php

class AdminPacientCategoryIllness extends CActiveRecord
{

    private $_illness_id;

    public function getIllness_name()
    {
        if(!empty($this->illness_id)){
            $model= AdminNodeIllness::model()->findByPk($this->illness_id);
            if(!empty($model->title)){
                $this->_illness_id=$model->title;
            }
        }
        return $this->_illness_id;
    }

    public function setIllness_name($value)
    {
        $this->_illness_id=$value;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pacient_category_illness';
    }

    public function rules()
    {
        return array(
            array('cat_id','required'),
            array('illness_id','required'),
            array('cat_id','exist','attributeName'=>'id','className'=>'AdminPacientCategory'),
            array('illness_id','exist','attributeName'=>'id','className'=>'AdminNodeIllness'),
            array('cat_id, illness_id','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            'i_i'=>array(self::BELONGS_TO,'AdminNodeIllness','illness_id'),
            'i_c'=>array(self::BELONGS_TO,'AdminPacientCategory','cat_id'),
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
                    'illness_id',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'illness_id'=>'Болезнь ID',
            'illness_name'=>'Болезнь',
            'cat_id'=>'Категория ID',
        );
    }

}