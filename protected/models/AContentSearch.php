<?php

/**
 * Created by PhpStorm.
 * User: serg
 * Date: 05.02.14
 * Time: 17:11
 */
class AContentSearch extends CModel
{

    protected $entityType='search';
    public $id;
    public $meta_title;
    public $url;
    public $is_mtitle;
    public $is_mdesc;
    public $is_mkeys;
    public $is_top;
    public $is_bottom;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('id,meta_title,url,is_mtitle,is_mdesc,is_mkeys,is_top,is_bottom','safe'),
            array('id,meta_title,url,is_mtitle,is_mdesc,is_mkeys,is_top,is_bottom','safe','on'=>'search'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'meta_title'=>'Наименование',
            'url'=>'URL',
            'is_mtitle'=>'MTitle',
            'is_mdesc'=>'MDesc',
            'is_mkeys'=>'MKeys',
            'is_top'=>'В.текст',
            'is_bottom'=>'Н.текст',
        );
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeNames()
    {
        return array('id','meta_title','url','is_mtitle','is_mdesc','is_mkeys','is_top','is_bottom');
    }

    public function attributeConditions()
    {
        return array(
            'id'=>'s.id=%s',
            'meta_title'=>'s.meta_title like %s',
            'url'=>'s.url like %s',
            'is_mtitle'=>array(
                '0'=>'CHAR_LENGTH(s.meta_title)=0',
                '1'=>'CHAR_LENGTH(s.meta_title)>0',
            ),
            'is_mdesc'=>array(
                '0'=>'CHAR_LENGTH(s.meta_description)=0',
                '1'=>'CHAR_LENGTH(s.meta_description)>0',
            ),
            'is_mkeys'=>array(
                '0'=>'CHAR_LENGTH(s.meta_keywords)=0',
                '1'=>'CHAR_LENGTH(s.meta_keywords)>0',
            ),
            'is_top'=>array(
                '0'=>'CHAR_LENGTH(s.top)=0',
                '1'=>'CHAR_LENGTH(s.top)>0',
            ),
            'is_bottom'=>array(
                '0'=>'CHAR_LENGTH(i.bottom)=0',
                '1'=>'CHAR_LENGTH(i.bottom)>0',
            ),
        );
    }

    public function attributesForHaving()
    {
        return array();
    }

}
