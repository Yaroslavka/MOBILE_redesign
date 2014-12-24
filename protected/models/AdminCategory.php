<?php

class AdminCategory extends CActiveRecord
{

    public $item;
    public $category;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'category';
    }

    public static function modelName($className=__CLASS__)
    {
        return $className;
    }

    public function rules()
    {
        return array(
            array('name','required','message'=>'Название категории обязательное поле'),
            array('item, child','safe'),
            array('translit','unique','message'=>'Транслитерация уже существует'),
            array('name, name_spec, translit, name_clinic, name_service','length','max'=>255),
            array('home, popular, main','numerical','integerOnly'=>true),
        );
    }

    public function relations()
    {
        return array(
            'category_price_s'=>array(self::HAS_MANY,'AdminPrice','category_id'),
            'category_doctor_s'=>array(self::HAS_MANY,'AdminCategoryDoctor','category_id'),
            'category_clinic_s'=>array(self::HAS_MANY,'AdminCategoryClinic','category_id'),
            'category_diagnostic_sesvice_s'=>array(self::HAS_MANY,'AdminCategoryDiagnosticService','category_id'),
        );
    }

    public function afterFind()
    {
        if(isset($this->parent()->find()->id)){
            $this->item=$this->parent()->find()->id;
        }
        return parent::afterFind();
    }

    public function getCat()
    {
        return Yii::app()->db->createCommand('SELECT `name` FROM category WHERE level=2 AND rgt<='.$this->rgt.' AND lft>='.$this->lft)->queryRow();
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
                    'name_clinic',
                    'name_spec',
                    'name_service',
                    'home',
                    'child',
                    'popular',
                    'main',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'item'=>'Родительская категория',
            'name'=>'Категория',
            'translit'=>'URL',
            'name_clinic'=>'Специализация клиники',
            'name_spec'=>'Множественное',
            'name_service'=>'Склонность для услуг',
            'home'=>'Вызов на дом',
            'child'=>'URl детской категории',
            'popular'=>'Популярная категория',
            'main'=>'Показывать на главной',
        );
    }

}
