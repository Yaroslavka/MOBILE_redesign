<?php

class VisibleBehavior extends CActiveRecordBehavior
{

    public $tablemodel;
    public $classmodel;

    public function getFields()
    {
        return $this->_fields;
    }

    public function setFields($value)
    {
        $this->_fields=$value;
    }

    private $_fields;

}
