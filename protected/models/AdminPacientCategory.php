<?php

class AdminPacientCategory extends CActiveRecord
{

    public $item;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pacient_category';
    }

    public static function modelName($className=__CLASS__)
    {
        return $className;
    }

    public function rules()
    {
        return array(
            array('name','required','message'=>'Название категории обязательное поле'),
            array('item, description','safe'),
            array('translit','unique','message'=>'Транслитерация уже существует'),
            array('name, name_spec, name_translit, translit','length','max'=>255),
        );
    }

    public function relations()
    {
        return array(
            'category_services_s'=>array(self::HAS_MANY,'AdminPacientCategoryServices','cat_id'),
            'category_illness_s'=>array(self::HAS_MANY,'AdminPacientCategoryIllness','cat_id'),
            'category_node_s'=>array(self::HAS_MANY,'AdminPacientNodeFaq','cat_id'),
        );
    }

    public function afterFind()
    {
        if(isset($this->parent()->find()->id)){
            $this->item=$this->parent()->find()->id;
        }
        return parent::afterFind();
    }

    public function behaviors()
    {
        return array(
            'NestedSetBehavior'=>array(
                'class'=>'ext.behaviors.trees.NestedSetBehavior',
                'hasManyRoots'=>true,
            ),
            'TranslitBehavior'=>array(
                'class'=>'ext.sprutlab.translit.TranslitBehavior',
                'name'=>'name',
                'model'=>__CLASS__,
                'translit'=>'translit',
            ),
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.HistoryBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'item',
                    'name',
                    'translit',
                    'name_translit',
                    'name_spec',
                    'description',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'status'=>'Статус',
            'item'=>'Родительская категория',
            'name'=>'Категория',
            'name_spec'=>'Категория',
            'translit'=>'URL',
            'name_translit'=>'Категория для связи',
            'description'=>'Описание',
        );
    }

}
