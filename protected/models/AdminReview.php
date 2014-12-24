<?php

class AdminReview extends CActiveRecord
{

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

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'reviews';
    }

    public function rules()
    {
        return array(
            array('name','required','message'=>'ФИО не может быть пустым'),
            array('description','required','message'=>'Отзыв не может быть пустым'),
            array('telephone','required','message'=>'Телефон не может быть пустым'),
            array('attention_value','required','message'=>'Оценка внимания не должна быть пустой'),
            array('doctor_value','required','message'=>'Оценка доктора не должна быть пустой'),
            array('price_value','required','message'=>'Оценка стоимости услуг не должна быть пустой'),
            array('attention_value','numerical','message'=>'Оценка внимания может быть только числом'),
            array('doctor_value','numerical','message'=>'Оценка доктора может быть только числом'),
            array('price_value','numerical','message'=>'Оценка стоимости может быть только числом'),
            array('create_time, update_time','safe'),
            array('status, clinic_id, doctor_id, record_id, uid','numerical','integerOnly'=>true,'message'=>'Только целочисленное значение'),
            array('clinic_id, doctor_id, uid','default','setOnEmpty'=>true,'value'=>null),
            array('clinic_id','exist','attributeName'=>'id','className'=>'AdminClinic','message'=>'Клиника для комментария не обнаружена'),
            array('doctor_id','exist','attributeName'=>'id','className'=>'AdminDoctor','message'=>'Доктор для комментария не обнаружен'),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser','message'=>'Автор комментария не обнаружен'),
            array('name, telephone','length','max'=>255),
        );
    }

    public function relations()
    {
        return array(
            'review_clinic'=>array(self::BELONGS_TO,'AdminClinic','clinic_id'),
            'review_doctor'=>array(self::BELONGS_TO,'AdminDoctor','doctor_id'),
            'review_user'=>array(self::BELONGS_TO,'AdminUser','uid'),
        );
    }

    public function behaviors()
    {
        if(Yii::app()->user->id){
            return array(
                'HistoryBehavior'=>array(
                    'class'=>'ext.sprutlab.history.HistoryBehavior',
                    'tablemodel'=>$this->tableName(),
                    'classmodel'=>__CLASS__,
                    'fields'=>array(
                        'id',
                        'doctor_id',
                        'clinic_id',
                        'name',
                        'update_time',
                        'status',
                        'description',
                        'telephone',
                        'attention_value',
                        'doctor_value',
                        'price_value',
                        'uid',
                    ),
                ),
            );
        } else{
            return array();
        }
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'record_id'=>'Из API',
            'name'=>'ФИО',
            'doctor_name'=>'Доктор',
            'clinic_name'=>'Клиника',
            'create_time'=>'Время создания',
            'update_time'=>'Время редактирования',
            'clinic_id'=>'Клиник ID',
            'doctor_id'=>'Доктор ID',
            'status'=>'Модерация',
            'description'=>'Содержание',
            'telephone'=>'Телефон',
            'attention_value'=>'Оценка внимания',
            'doctor_value'=>'Оценка доктора',
            'price_value'=>'Оценка стоимости',
            'uid'=>'Пользователь ID',
        );
    }

    public function beforeValidate()
    {
        if(Yii::app()->user->id){
            $this->uid=Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }

}
