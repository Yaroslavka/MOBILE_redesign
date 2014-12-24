<?php

class AdminDiagnosticCategory extends CActiveRecord
{

    public $item;
    public $category;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'category_price';
    }

    public static function modelName($className=__CLASS__)
    {
        return $className;
    }

    public function rules()
    {
        return array(
            array('name','required'),
            array('item','safe'),
            array('translit','unique'),
            array('name, name_alt, translit','length','max'=>255),
        );
    }

    public function relations()
    {
        return array(
            'category_clinic_s'=>array(self::HAS_MANY,'AdminDiagnosticPrice','category_price_id'),
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
        return Yii::app()->db->createCommand('SELECT name FROM category_price WHERE root='.$this->root.' AND rgt>'.$this->rgt.' AND lft<'.$this->lft." ORDER BY rgt")->queryRow();
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
                    'name_alt',
                    'sames',
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
            'name_alt'=>'Альтернативно',
            'sames'=>'Группа',
            'translit'=>'URL',
        );
    }

}
