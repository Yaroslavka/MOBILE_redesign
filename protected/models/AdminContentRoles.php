<?php

class AdminContentRoles extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'content_roles';
    }

    public function rules()
    {
        return array(
            array('title','required'),
            array('title, tablemodel','length','max'=>255),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser'),
            array('uid, crud, status','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            'u'=>array(self::BELONGS_TO,'AdminUser','uid'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'tablemodel'=>'Модель-таблица',
            'title'=>'Название',
            'crud'=>'CRUD ID',
            'status'=>'Выбрано',
            'uid'=>'Пользователь ID',
        );
    }

    public function behaviors()
    {
        return array(
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.VisibleBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'title',
                    'tablemodel',
                    'crud',
                    'status',
                    'uid',
                ),
            ),
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
