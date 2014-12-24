<?php

class HistoryBehavior extends CActiveRecordBehavior
{

    public $tablemodel;
    public $classmodel;
    public $deleting='Удаление!';

    public function getFields()
    {
        return $this->_fields;
    }

    public function setFields($value)
    {
        $this->_fields=$value;
    }

    private $_oldFields;

    public function getOldFields()
    {
        return $this->_oldFields;
    }

    public function setOldFields($value)
    {
        $this->_oldFields=$value;
    }

    private $_newFields;

    public function getNewFields()
    {
        return $this->_newFields;
    }

    public function setNewFields($value)
    {
        $this->_newFields=$value;
    }

    public function beforeSave($event)
    {
        $modelName=$this->classmodel;
        if(!$this->getOwner()->isNewRecord){
            $model=$modelName::model()->findByPk($this->getOwner()->primaryKey);
        } else {
            $model=new $modelName;
        }
        if(!empty($this->fields)){
            foreach($this->fields as $key=>$value){
                $this->_oldFields[$key]=$model->{$value};
                $this->_newFields[$key]=$this->getOwner()->{$value};
                if($this->getOwner()->isNewRecord||$this->oldFields[$key]!=$this->newFields[$key]){
                    $this->data[]=$value;
                }                
            }
            if(!empty($this->data)){
                $newContent=new AdminContentHistory;
                $newContent->primary_key=$this->getOwner()->primaryKey;
                $newContent->uid=Yii::app()->user->id;
                $newContent->tablemodel=$this->tablemodel;
                $newContent->title=$this->getOwner()->getScenario();
                $newContent->description=implode(", ",$this->data);
                if($newContent->save()){
                    $this->pkd=$newContent->primaryKey;
                }
            }            
        }
        return parent::beforeSave($event);
    }
    
    public $pkd;
    
    public function afterSave($event)
    {
        if($this->getOwner()->isNewRecord&&!empty($this->pkd)){
            $model=AdminContentHistory::model()->findByPk($this->pkd);
            $model->primary_key=$this->getOwner()->primaryKey;
            $model->save();
        }       
        return parent::afterSave($event);
    }

    public function beforeDelete($event)
    {
        $model=new AdminContentHistory;
        $model->primary_key=$this->getOwner()->primaryKey;
        $model->uid=Yii::app()->user->id;
        if(!empty($this->tablemodel)){
            $model->tablemodel=$this->tablemodel;
        } else{
            $model->tablemodel=$this->getOwner()->tableName();
        }
        $model->title='delete';
        $model->description=$this->deleting;
        $model->save();
        return parent::beforeDelete($event);
    }

    private $data=array();
    private $_fields;

}
