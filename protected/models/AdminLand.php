<?php

class AdminLand extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'land';
    }

    public function rules()
    {
        return array(
            array('title','required'),
            array('translit','length','max'=>255),
            array('body, body2','required'),
        );
    }

    public function relations()
    {
        return array(
            'land_category'=>array(self::HAS_MANY,'AdminLandCategory','land_id','with'=>'land_category_category','order'=>'land_category_category.name ASC'),
            'land_child_category'=>array(self::HAS_MANY,'AdminLandChildCategory','land_id','with'=>'land_child_category_category','order'=>'land_child_category_category.name ASC'),
        );
    }

    public function behaviors()
    {
        return array(
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
                    'body',
                    'translit',
                    'body2',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Название',
            'body'=>'Содержание',
            'body2'=>'Основное содержание',
            'translit'=>'Транслит',
        );
    }

}
