<?php

class AdminContentTask extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'content_task';
    }

    public function rules()
    {
        return array(
            array('title','required'),
            array('create_time, update_time, description','safe'),
            array('title, check_time','length','max'=>255),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser'),
            array('uid, status','numerical','integerOnly'=>true),
            array('period_m, period_pm','numerical'),
        );
    }

    public function relations()
    {
        return array(
            'u'=>array(self::BELONGS_TO,'AdminUser','uid'),
        );
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior'=>array(
                'class'=>'zii.behaviors.CTimestampBehavior',
                'createAttribute'=>'create_time',
                'updateAttribute'=>null,
            ),
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.VisibleBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'title',
                    'update_time',
                    'check_time',
                    'description',
                    'status',
                    'uid',
                    'period_pm',
                    'period_m',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'create_time'=>'Время создания',
            'update_time'=>'Время редактирования',
            'check_time'=>'Время',
            'description'=>'Мини описание',
            'title'=>'Название',
            'status'=>'Статус',
            'uid'=>'Пользователь ID',
            'period_pm'=>'Срок PM',
            'period_m'=>'Срок M',
        );
    }

    public function beforeValidate()
    {
        if(empty($this->uid)&&Yii::app()->user->id){
            $this->uid=Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }

}
