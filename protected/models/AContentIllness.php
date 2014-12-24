<?php

/**
 * Created by PhpStorm.
 * User: serg
 * Date: 04.02.14
 * Time: 17:41
 */
class AContentIllness extends CModel
{

    protected $entityType='illnesses';
    public $id;
    public $title;
    public $status;
    public $author;
    public $cat_title;
    public $is_desc;
    public $is_det_desc;
    public $is_mtitle;
    public $is_mdesc;
    public $is_mkeys;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('id,title,status,author,cat_title,is_desc,is_det_desc,is_mtitle,is_mdesc,is_mkeys','safe'),
            array('id,title,status,author,cat_title,is_desc,is_det_desc,is_mtitle,is_mdesc,is_mkeys','safe','on'=>'search'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title'=>'Наименование',
            'status'=>'Статус',
        );
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeNames()
    {
        return array('id','title','status','author','cat_title','is_desc','is_det_desc','is_mtitle','is_mdesc','is_mkeys');
    }

    public function attributeConditions()
    {
        return array(
            'id'=>'i.id=%s',
            'title'=>'i.title like %s',
            'status'=>'i.status=%s',
            'author'=>'u.email like %s or u.username like %s or u.email like %s',
            'cat_title'=>'icat.title like %s',
            'is_desc'=>array(
                '0'=>'CHAR_LENGTH(i.description)=0',
                '1'=>'CHAR_LENGTH(i.description)>0',
            ),
            'is_det_desc'=>array(
                '0'=>'CHAR_LENGTH(i.body)=0',
                '1'=>'CHAR_LENGTH(i.body)>0',
            ),
            'is_mtitle'=>array(
                '0'=>'CHAR_LENGTH(i.meta_title)=0',
                '1'=>'CHAR_LENGTH(i.meta_title)>0',
            ),
            'is_mdesc'=>array(
                '0'=>'CHAR_LENGTH(i.meta_description)=0',
                '1'=>'CHAR_LENGTH(i.meta_description)>0',
            ),
            'is_mkeys'=>array(
                '0'=>'CHAR_LENGTH(i.meta_keywords)=0',
                '1'=>'CHAR_LENGTH(i.meta_keywords)>0',
            ),
        );
    }

    public function attributesForHaving()
    {
        return array();
    }

}
