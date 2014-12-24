<?php

class AdminNodeFaqAlias extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'node_faq_alias';
    }

    public function rules()
    {
        return array(
            array('category_translit, theme_translit','required'),
            array('depth','numerical','integerOnly'=>true),
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
                    'depth',
                    'category_translit',
                    'theme_translit',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'depth'=>'Глубина',
            'category_translit'=>'Категория',
            'theme_translit'=>'Тема',
        );
    }

}
