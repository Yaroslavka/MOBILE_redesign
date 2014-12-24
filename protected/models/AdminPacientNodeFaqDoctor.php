<?php

class AdminPacientNodeFaqDoctor extends CActiveRecord
{

    private $_doctor_name;

    public function getDoctor_name()
    {
        if(!empty($this->doctor_id)){
            $model= AdminDoctor::model()->findByPk($this->doctor_id);
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
        return 'pacient_node_faq_docort';
    }

    public function rules()
    {
        return array(
            array('faq_id','required'),
            array('doctor_id','required'),
            array('faq_id','exist','attributeName'=>'id','className'=>'AdminPacientNodeFaq'),
            array('doctor_id','exist','attributeName'=>'id','className'=>'AdminDoctor'),
            array('faq_id, doctor_id','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            'd_d'=>array(self::BELONGS_TO,'AdminDoctor','doctor_id'),
            'd_f'=>array(self::BELONGS_TO,'AdminPacientNodeFaq','faq_id'),
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
                    'faq_id',
                    'doctor_id',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'doctor_id'=>'Доктор ID',
            'doctor_name'=>'Доктор',
            'faq_id'=>'Статья ID',
        );
    }

}