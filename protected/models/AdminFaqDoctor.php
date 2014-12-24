<?php

class AdminFaqDoctor extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'node_faq_doctor';
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
                    'translit_category',
                    'translit_doctor',
                ),
            ),
        );
    }

    public function rules()
    {
        return array(
            array('translit_category, translit_doctor','safe'),
        );
    }

}
