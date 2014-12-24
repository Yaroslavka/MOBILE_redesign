<?php

class AdminSubwayLine extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'subway_line';
    }

    public function rules()
    {
        return array(
            array('title','required','message'=>'Данное поле не может быть пустым'),
            array('title','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('title','length','max'=>255),
        );
    }

    public function relations()
    {
        return array(
            'subways'=>array(self::HAS_MANY,'AdminSubway','subway_line_id'),
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
                    'title',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Ветка метро',
        );
    }

}
