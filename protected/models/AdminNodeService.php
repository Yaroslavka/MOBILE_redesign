<?php

class AdminNodeService extends CActiveRecord
{

    private $_category_name;

    public function getCategory_name()
    {
        if(!empty($this->category_id)){
            $model=AdminCategory::model()->findByPk($this->category_id);
            if(!empty($model->name)){
                $this->_category_name=$model->name;
            }
        }
        return $this->_category_name;
    }

    public function setCategory_name($value)
    {
        $this->_category_name=$value;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'node_service';
    }

    public function rules()
    {
        return array(
            array('title','required'),
            array('translit','length','max'=>255),
            array('body, description','required'),
            array('status, category_id','numerical','integerOnly'=>true),
            array('category_id','exist','attributeName'=>'id','className'=>'AdminCategory'),
            array('meta_title, meta_description, meta_keywords, description, image, subdescription','safe'),
        );
    }

    public function relations()
    {
        return array(
            'service_category'=>array(self::BELONGS_TO,'AdminCategory','category_id'),
            'service_price'=>array(self::HAS_MANY,'AdminNodeServicePrice','service_id'),
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
            'ImageBehavior'=>array(
                'class'=>'ext.sprutlab.image.ImageBehavior',
                'versions'=>array(
                    'photo'=>array(
                        'centeredpreview'=>array(860,575),
                    ),
                    'small'=>array(
                        'centeredpreview'=>array(100,100),
                    ),
                ),
            ),
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.HistoryBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'title',
                    'body',
                    'description',
                    'subdescription',
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                    'translit',
                    'category_id',
                    'image',
                    'status',
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
            'description'=>'Кратко',
            'subdescription'=>'Подзаголовок',
            'meta_title'=>'МЕТА: заголовок',
            'meta_keywords'=>'МЕТА: слова',
            'meta_description'=>'МЕТА: описание',
            'translit'=>'Транслит',
            'category_id'=>'Категория ID',
            'status'=>'Статус',
            'image'=>'Фото',
            'category_name'=>'Категория',
        );
    }

}
