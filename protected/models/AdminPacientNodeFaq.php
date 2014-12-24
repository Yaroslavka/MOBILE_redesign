<?php

class AdminPacientNodeFaq extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pacient_node_faq';
    }

    

    public function rules()
    {
        return array(
            array('title','required','message'=>'Укажите название'),
            array('description','required','message'=>'Заполните описание'),
            array('body','required','message'=>'Заполните содержание'),
            array('title, translit','length','max'=>255),
            array('create_time, translit','safe'),
            array('cat_id','exist','attributeName'=>'id','className'=>'AdminPacientCategory'),
        );
    }

    public function relations()
    {
        return array(
            'cat_pacient'=>array(self::BELONGS_TO,'AdminPacientCategory','cat_id'),
            'pacient_doctor_s'=>array(self::HAS_MANY,'AdminPacientNodeFaqDoctor','faq_id'),
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
            'TranslitBehavior'=>array(
                'class'=>'ext.sprutlab.translit.TranslitBehavior',
                'name'=>'title',
                'model'=>__CLASS__,
                'translit'=>'translit',
            ),
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.HistoryBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'title',
                    'description',
                    'cat_id',
                    'body',
                    'create_time',
                    'translit',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Название',
            'description'=>'Мини описание',
            'cat_id'=>'Категория',
            'create_time'=>'Время создания',
            'body'=>'Содержание',
            'translit'=>' Транслит',
        );
    }

}