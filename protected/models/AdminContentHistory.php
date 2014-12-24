<?php

class AdminContentHistory extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'content_history';
    }

    public function rules()
    {
        return array(
            array('title','required'),
            array('create_time, description','safe'),
            array('title, tablemodel','length','max'=>255),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser'),
            array('uid, primary_key','numerical','integerOnly'=>true),
        );
    }

    public function getLink()
    {
        $output='';
        if(!empty($this->tablemodel)&&!empty($this->primary_key)){
            $output=$this->primary_key;
            $example=array(
                "clinic_price"=>array('model'=>'AdminDiagnosticPrice','key'=>'clinic_id','ca'=>'adminDiagnosticClinic/indexCategory'),
                "clinic_doctor"=>array('model'=>'AdminClinicDoctor','key'=>'doctor_id','ca'=>'adminDoctor/indexClinic'),
                "category_doctor"=>array('model'=>'AdminCategoryDoctor','key'=>'doctor_id','ca'=>'adminDoctor/indexCategory'),
                "category_clinic"=>array('model'=>'AdminCategoryClinic','key'=>'clinic_id','ca'=>'adminClinic/indexCatregory'),
                "subway_clinic"=>array('model'=>'AdminSubwayClinic','key'=>'clinic_id','ca'=>'adminClinic/updateClinic'),
                "child_clinic_doctor"=>array('model'=>'AdminChildClinicDoctor','key'=>'doctor_id','ca'=>'adminChildDoctor/indexClinic'),
                "child_category_doctor"=>array('model'=>'AdminChildCategoryDoctor','key'=>'doctor_id','ca'=>'adminChildDoctor/indexCategory'),
                "child_category_clinic"=>array('model'=>'AdminChildCategoryClinic','key'=>'clinic_id','ca'=>'adminChildClinic/indexCategory'),
                "subway_child_clinic"=>array('model'=>'AdminSubwayClinic','key'=>'clinic_id','ca'=>'adminChildClinic/updateClinic'),
                "node_service_price"=>array('model'=>'AdminNodeService','key'=>'service_id','ca'=>'adminNodeService/indexCategory'),
                "clinic_diagnostic"=>array('ca'=>'adminDiagnosticClinic/updateClinic'),
                "clinic"=>array('ca'=>'adminClinic/updateClinic'),
                "doctor"=>array('ca'=>'adminDoctor/updateDoctor'),
                "child_clinic"=>array('ca'=>'adminChildClinic/updateClinic'),
                "child_doctor"=>array('ca'=>'adminChildDoctor/updateDoctor'),
                "category"=>array('ca'=>'category/createForm'),
                "child_category"=>array('ca'=>'adminChildCategory/createForm'),
                "reviews"=>array('ca'=>'adminReview/createForm'),
                "price"=>array('ca'=>'adminPrice/createForm'),
                "node_faq"=>array('ca'=>"adminNodeFaq/createForm"),
                "node_faq_alias"=>array('ca'=>'adminNodeFaqAlias/createForm'),
                "node_faq_category"=>array('ca'=>'adminNodeFaqCategory/createForm'),
                "node_illness"=>array('ca'=>'adminNodeIllness/createForm'),
                "node_illness_alias"=>array('ca'=>'adminNodeIllnesAlias/createForm'),
                "node_illness_category"=>array('ca'=>'adminNodeIllnessCategory/createForm'),
                "node_service"=>array('ca'=>'adminNodeService/createForm'),
                "node_service_doctor"=>array('ca'=>'adminNodeServiceDoctor/createForm'),
                "node_static"=>array('ca'=>'adminNodeStatic/createForm'),
                "alias"=>array('ca'=>'alias/createForm'),
                "alias_diagnostic"=>array('ca'=>'aliasDiagnostic/createForm'),
                "district"=>array('ca'=>'adminDistrict/createForm'),
                "district_line"=>array('ca'=>'adminDistrictLine/createForm'),
                "subway"=>array('ca'=>'adminSubway/createForm'),
                "subway_line"=>array('ca'=>'adminSubwayLine/createForm'),
                "user"=>array('ca'=>'adminUser/createForm'),
                "category_price"=>array('ca'=>'adminDiagnosticCategory/update'),
                "child_reviews"=>array('ca'=>'adminChildReview/createForm'),
                "clinic_network"=>array('ca'=>'adminClinic/createForm'),
            );
            if($this->title!='delete'&&!empty($example[$this->tablemodel])){
                if(empty($example[$this->tablemodel]['model'])){
                    $output=CHtml::link($this->primary_key,array($example[$this->tablemodel]['ca'],'id'=>$this->primary_key));
                } elseif(!empty($example[$this->tablemodel]['key'])&&!empty($example[$this->tablemodel]['model'])){
                    $pk=$example[$this->tablemodel]['key'];
                    $modelName=$example[$this->tablemodel]['model'];
                    $model=$modelName::model()->findByPk($this->primary_key);
                    if(!empty($model->{$pk})){
                        $output=CHtml::link($this->primary_key,array($example[$this->tablemodel]['ca'],'id'=>$model->{$pk}));
                    }
                }
            }
        }
        return $output;
    }

    public function relations()
    {
        return array(
            'u'=>array(self::BELONGS_TO,'AdminUser','uid'),
        );
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior'=>array(
                'class'=>'zii.behaviors.CTimestampBehavior',
                'createAttribute'=>'create_time',
                'updateAttribute'=>null,
            ),
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.VisibleBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
                    'id',
                    'description',
                    'tablemodel',
                    'title',
                    'primary_key',
                    'uid',
                ),
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'link'=>'Link',
            'create_time'=>'Время создания',
            'description'=>'Мини описание',
            'tablemodel'=>'Модель-таблица',
            'title'=>'Название',
            'primary_key'=>'Основной ID',
            'uid'=>'Пользователь ID',
        );
    }

    public function getDs()
    {
        $array=array();
        if(!empty($this->description)&&!empty($this->tablemodel)){
            $ds=explode(",",$this->description);
            $modelName=self::historyArray($this->tablemodel,'mainModel');
            $model=new $modelName;
            $labels=$model->attributeLabels();
            if(!empty($ds)&&!empty($modelName)){
                foreach($ds as $value){
                    if(!empty($labels[trim($value)])){
                        $attr=$labels[trim($value)];
                        $array[]=CHtml::link($attr,'#',array('data-act'=>trim($value),'class'=>'addlink'));
                    } else {
                        $array[]=trim($value);
                    }
                }
            }
        }
        return !empty($array)?implode(', ',$array):'';
    }

    public static function historyArray($alias,$key)
    {
        $array=array(
            'alias'=>array(
                'mainModel'=>'AdminAlias',
                'id',
                'url',
                'url_canonical',
                'price',
                'h1',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'top',
                'middle',
                'bottom',
            ),
            'alias_diganostic'=>array(
                'mainModel'=>'AdminAliasDiagnostic',
                'id',
                'url_canonical',
                'price',
                'h1',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'top',
                'middle',
                'bottom',
            ),
            'user'=>array(
                'mainModel'=>'AdminUser',
                'uid',
                'role',
                'password',
                'telephone',
                'lastvisit',
                'email',
                'gender',
                'username',
                'update_time',
            ),
            'category'=>array(
                'mainModel'=>'AdminCategory',
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
            'category_clinic'=>array(
                'mainModel'=>'AdminCategoryClinic',
                'id',
                'category_id',
                'clinic_id',
                'parent_id',
                'price',
                'name',
            ),
            'category_doctor'=>array(
                'mainModel'=>'AdminCategoryDoctor',
                'id',
                'category_id',
                'doctor_id',
                'parent_id',
                'price',
                'name',
            ),
            'category_price'=>array(
                'mainModel'=>'AdminDiagnosticCategory',
                'id',
                'item',
                'name',
                'translit',
                'name_alt',
                'sames',
            ),
            'child_category'=>array(
                'mainModel'=>'AdminChildCategory',
                'id',
                'item',
                'name',
                'translit',
                'name_clinic',
                'name_spec',
                'name_service',
                'home',
                'booking',
            ),
            'child_category_clinic'=>array(
                'mainModel'=>'AdminChildCategoryClinic',
                'id',
                'category_id',
                'clinic_id',
                'parent_id',
                'price',
                'name',
            ),
            'child_category_doctor'=>array(
                'mainModel'=>'AdminChildCategoryDoctor',
                'id',
                'category_id',
                'doctor_id',
                'parent_id',
                'price',
                'name',
            ),
            'child_clinic'=>array(
                'mainModel'=>'AdminChildClinic',
                'id',
                'title',
                'translit',
                'address',
                'telephone',
                'image',
                'url',
                'specialistAll',
                'countReview',
                'countDoctors',
                'subway_name',
                'status',
                'modificator',
                'sames',
                'district',
                'inn',
                'start_rate',
                'lat',
                'description',
                'body',
                'companyname',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'regime_sun',
                'regime_mon',
                'regime_tue',
                'regime_wed',
                'regime_thu',
                'regime_fri',
                'regime_sat',
                'regime_byd',
                'network_id',
                'district_id',
                'gallery_id',
                'none_status',
            ),
            'child_clinic_doctor'=>array(
                'mainModel'=>'AdminChildClinicDoctor',
                'id',
                'doctor_id',
                'clinic_id',
                'speciality',
                'position',
            ),
            'child_doctor'=>array(
                'mainModel'=>'AdminChildDoctor',
                'id',
                'fio',
                'translit',
                'speciality',
                'position',
                'image',
                'startyear',
                'degree',
                'description',
                'countReview',
                'clinic_name',
                'status',
                'sames',
                'email',
                'house',
                'child',
                'modificator',
                'start_rate',
                'price',
                'professional',
                'associated',
                'education',
                'awards',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'gender',
            ),
            'child_reviews'=>array(
                'mainModel'=>'AdminChildReviews',
                'id',
                'doctor_id',
                'clinic_id',
                'name',
                'update_time',
                'status',
                'description',
                'telephone',
                'attention_value',
                'doctor_value',
                'price_value',
                'uid',
            ),
            'child_subway_clinic'=>array(
                'mainModel'=>'AdminChildSubwayClinic',
                'id',
                'lin1',
                'lin2',
                'clinic_id',
                'subway_id',
            ),
            'clinic'=>array(
                'mainModel'=>'AdminClinic',
                'id',
                'title',
                'translit',
                'address',
                'telephone',
                'image',
                'url',
                'specialistAll',
                'countReview',
                'countDoctors',
                'subway_name',
                'status',
                'sames',
                'district',
                'inn',
                'modificator',
                'start_rate',
                'lat',
                'description',
                'body',
                'companyname',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'regime_sun',
                'regime_mon',
                'regime_tue',
                'regime_wed',
                'regime_thu',
                'regime_fri',
                'regime_sat',
                'regime_byd',
                'network_id',
                'district_id',
                'gallery_id',
                'view_status',
                'external_id',
                'alias',
                'none_status',
            ),
            'clinic_diagnostic'=>array(
                'mainModel'=>'AdminClinicDiagnostic',
                'id',
                'title',
                'translit',
                'address',
                'telephone',
                'image',
                'subway_name',
                'status',
                'start_rate',
                'modificator',
                'lat',
                'description',
                'body',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'regime_sun',
                'regime_mon',
                'regime_tue',
                'regime_wed',
                'regime_thu',
                'regime_fri',
                'regime_sat',
                'regime_byd',
                'district_id',
                'none_status',
            ),
            'clinic_doctor'=>array(
                'mainModel'=>'AdminClinicDoctor',
                'id',
                'doctor_id',
                'clinic_id',
                'speciality',
                'position',
            ),
            'clinic_price'=>array(
                'mainModel'=>'AdminDiagnosticPrice',
                'id',
                'category_price_id',
                'clinic_id',
                'parent_id',
                'price',
                'old_price',
                'status',
                'sames',
                'name',
            ),
            'district'=>array(
                'mainModel'=>'AdminDistrict',
                'id',
                'title',
                'district_line_id',
                'translit',
            ),
            'district_line'=>array(
                'mainModel'=>'AdminDistrictLine',
                'id',
                'title',
                'translit',
            ),
            'doctor'=>array(
                'mainModel'=>'AdminDoctor',
                'id',
                'fio',
                'translit',
                'speciality',
                'position',
                'image',
                'startyear',
                'degree',
                'description',
                'countReview',
                'clinic_name',
                'status',
                'child',
                'sames',
                'email',
                'house',
                'start_rate',
                'modificator',
                'price',
                'professional',
                'associated',
                'education',
                'awards',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'gender',
                'view_status',
                'external_id',
            ),
            'land'=>array(
                'mainModel'=>'AdminLand',
                'id',
                'title',
                'body',
                'translit',
                'body2',
            ),
            'land_category'=>array(
                'mainModel'=>'AdminLandCategory',
                'id',
                'category_id',
                'land_id',
            ),
            'node_faq'=>array(
                'mainModel'=>'AdminNodeFaq',
                'id',
                'url_canonical',
                'title',
                'description',
                'theme_id',
                'status',
                'uid',
                'update_time',
                'body',
                'image',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'translit',
            ),
            'node_faq_alias'=>array(
                'mainModel'=>'AdminNodeFaqAlias',
                'id',
                'depth',
                'category_translit',
                'theme_translit',
            ),
            'node_faq_category'=>array(
                'mainModel'=>'AdminNodeFaqCategory',
                'id',
                'title',
                'description',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'translit',
            ),
            'node_faq_doctor'=>array(
                'mainModel'=>'AdminNodeFaqDoctor',
                'id',
                'translit_category',
                'translit_doctor',
            ),
            'node_illness'=>array(
                'mainModel'=>'AdminNodeIllness',
                'id',
                'category_id',
                'url_canonical',
                'title',
                'description',
                'theme_id',
                'status',
                'uid',
                'update_time',
                'body',
                'image',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'translit',
            ),
            'node_illness_alias'=>array(
                'mainModel'=>'AdminNodeIllnessAlias',
                'id',
                'depth',
                'category_translit',
                'theme_translit',
            ),
            'node_illness_category'=>array(
                'mainModel'=>'AdminNodeIllnessCategory',
                'id',
                'title',
                'description',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'translit',
            ),
            'node_illness_doctor'=>array(
                'mainModel'=>'AdminNodeIllnessDoctor',
                'id',
                'illness_id',
                'doctor_id',
            ),
            'node_service'=>array(
                'mainModel'=>'AdminNodeService',
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
            'node_service_doctor'=>array(
                'mainModel'=>'AdminNodeServiceDoctor',
                'id',
                'doctor_id',
                'service_id',
            ),
            'node_service_price'=>array(
                'mainModel'=>'AdminNodeServicePrice',
                'id',
                'category_id',
                'clinic_id',
                'service_id',
                'price',
                'status',
            ),
            'node_static'=>array(
                'mainModel'=>'AdminNodeStatic',
                'id',
                'title',
                'description',
                'underdescription',
                'body',
                'meta_title',
                'meta_keywords',
                'meta_description',
                'translit',
                'subtitle1',
                'subtitle2',
                'item1',
                'item2',
                'list1',
                'list2',
            ),
            'pacient_category'=>array(
                'mainModel'=>'AdminPacientCategory',
                'id',
                'item',
                'name',
                'translit',
                'description',
            ),
            'pacient_category_illness'=>array(
                'mainModel'=>'AdminPacientCategoryIllness',
                'id',
                'cat_id',
                'illness_id',
            ),
            'pacient_category_service'=>array(
                'mainModel'=>'AdminPacientCategoryService',
                'id',
                'cat_id',
                'service_id',
            ),
            'pacient_node_faq'=>array(
                'mainModel'=>'AdminPacientNodeFaq',
                'id',
                'title',
                'description',
                'cat_id',
                'body',
                'create_time',
                'translit',
            ),
            'pacient_node_faq_docort'=>array(
                'mainModel'=>'AdminPacientNodeFaqDoctor',
                'id',
                'faq_id',
                'doctor_id',
            ),
            'price'=>array(
                'mainModel'=>'AdminPrice',
                'id',
                'category_id',
                'clinic_id',
                'doctor_id',
                'price',
            ),
            'reviews'=>array(
                'mainModel'=>'AdminReview',
                'id',
                'doctor_id',
                'clinic_id',
                'name',
                'update_time',
                'status',
                'description',
                'telephone',
                'attention_value',
                'doctor_value',
                'price_value',
                'uid',
            ),
            'subway'=>array(
                'mainModel'=>'AdminSubway',
                'id',
                'title',
                'lat',
                'subway_line_id',
                'district_id',
                'translit',
            ),
            'subway_clinic'=>array(
                'mainModel'=>'AdminSubwayClinic',
                'id',
                'lin1',
                'lin2',
                'clinic_id',
                'subway_id',
            ),
            'subway_clinic_diagnostic'=>array(
                'mainModel'=>'AdminSubwayClinicDiagnostic',
                'id',
                'lin1',
                'lin2',
                'clinic_id',
                'subway_id',
            ),
            'subway_line'=>array(
                'mainModel'=>'AdminSubwayLine',
                'id',
                'title',
            ),
        );
        return !empty($array[$alias][$key])?$array[$alias][$key]:'';
    }

}