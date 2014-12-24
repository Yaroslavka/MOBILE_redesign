<?php

class RoleChild extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'auth_item_child';
    }

    public function rules()
    {
        return array(
            array('parent, child','required'),
            array('parent, child','length','max'=>64),
            array('parent, child','safe','on'=>'search'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'parent'=>'Parent',
            'child'=>'Child',
        );
    }

}
