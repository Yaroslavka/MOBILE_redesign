<?php

class Role extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'auth_item';
    }

    public function primaryKey()
    {
        return 'name';
    }

    public function rules()
    {
        return array(
            array('name, type','required'),
            array('type','numerical','integerOnly'=>true),
            array('name','length','max'=>64),
            array('description, bizrule, data','safe'),
            array('name, type','safe','on'=>'search'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name'=>'Роль',
            'type'=>'Тип',
            'description'=>'Описание',
            'bizrule'=>'Bizrule',
            'data'=>'Data',
        );
    }

}
