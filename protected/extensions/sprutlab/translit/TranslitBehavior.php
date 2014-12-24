<?php

Yii::import('application.extensions.sprutlab.translit.Translit');

class TranslitBehavior extends CActiveRecordBehavior
{

    public $name='title';
    public $translit='translit';
    public $model;
    public $count=255;

    public function beforeSave($event)
    {
        $model= new $this->model;
        if(empty($this->getOwner()->{$this->translit})){
            $this->getOwner()->{$this->translit}=Translit::cutString(Translit::asURLSegment($this->getOwner()->{$this->name}),$this->count);
            $findTranslit=$model::model()->findByAttributes(array('translit'=>$this->getOwner()->{$this->translit}));
            if($this->getOwner()->isNewRecord){
                if(!empty($findTranslit->primaryKey)){
                    $count=$model::model()->count('translit LIKE "%:transli%"',array(":translit"=>$this->getOwner()->{$this->translit}));
                    $find=$model::model()->find(array('condition'=>'translit LIKE "%:translit%"','order'=>'id DESC'),array(":translit"=>$this->getOwner()->{$this->translit}));
                    $this->getOwner()->{$this->translit}=$this->getOwner()->{$this->translit}."-".($count+1)."-".$find['id'];
                }
            } else{
                if(!empty($findTranslit->primaryKey)&&$this->getOwner()->primaryKey!=$findTranslit->primaryKey){
                    $this->getOwner()->{$this->translit}=$this->getOwner()->{$this->translit}."-".$this->getOwner()->primaryKey;
                }
            }
        }
        return parent::beforeSave($event);
    }

}
