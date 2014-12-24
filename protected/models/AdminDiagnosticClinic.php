<?php

class AdminDiagnosticClinic extends CActiveRecord
{

    public $lat_id;
    public $lng_id;
    public $metro;
    public $min_car;
    public $min_man;
    
    public function getSubway_array()
    {
        $array=array();
        $data=AdminDiagnosticSubwayClinic::model()->findAllByAttributes(array('clinic_id'=>$this->primaryKey));
        if(!empty($data)){
            foreach($data as $value){
                if(!empty($value['sub']['title'])){
                    $array[$value->id]['title']=$value['sub']['title'];
                    $array[$value->id]['lin1']=$value->lin1;
                    $array[$value->id]['lin2']=$value->lin2;
                }
            }
        }
        $this->_subway_array=$array;
        return $this->_subway_array;
    }

    public function setSubway_array($value)
    {
        $this->_subway_array=$value;
    }

    public function getSubway_name()
    {
        $array=array();
        $data=AdminDiagnosticSubwayClinic::model()->findAllByAttributes(array('clinic_id'=>$this->primaryKey));
        if(!empty($data)){
            foreach($data as $value){
                if(!empty($value['sub']['title'])){
                    $array[]=$value['sub']['title'];
                }
            }
        }
        $this->_subway_name=implode(", ",$array);
        return $this->_subway_name;
    }

    public function setSubway_name($value)
    {
        $this->_subway_name=$value;
    }

    public function getSelSubways()
    {
        $this->_selSubways=Yii::app()->db->createCommand("SELECT subway.title AS title FROM subway_clinic_diagnostic LEFT JOIN subway ON subway_clinic_diagnostic.subway_id=subway.id WHERE subway_clinic_diagnostic.clinic_id=".$this->id)->queryAll();
        return $this->_selSubways;
    }

    public function setSelSubways($value)
    {
        $this->_selSubways=$value;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'clinic_diagnostic';
    }

    public function rules()
    {
        return array(
            array('title','required'),
            array('translit','unique'),
            array('address, telephone, email, translit, meta_keywords, meta_title','length','max'=>255),
            array('lat, lng, regime_sun, regime_mon, regime_tue, regime_wed, regime_thu, regime_fri, regime_sat, regime_byd','length','max'=>128),
            array('create_time, update_time, description, body, image','safe'),
            array('startyear, district_id, uid, status, none_status','numerical','integerOnly'=>true),
            array('start_rate, modificator','numerical'),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser'),
            array('district_id','exist','attributeName'=>'id','className'=>'AdminDistrict'),
            array('email','email'),
            array('sames','safe'),
        );
    }

    public function behaviors()
    {
        return array(
            'ImageBehavior'=>array(
                'class'=>'ext.sprutlab.image.ImageBehavior',
                'versions'=>array(
                    'small'=>array(
                        'centeredpreview'=>array(150,150),
                    ),
                    'photo'=>array(
                        'centeredpreview'=>array(200,300),
                    ),
                ),
            ),
            'TranslitBehavior'=>array(
                'class'=>'ext.sprutlab.translit.TranslitBehavior',
                'name'=>'title',
                'model'=>__CLASS__,
                'translit'=>'translit',
            ),
            'CTimestampBehavior'=>array(
                'class'=>'zii.behaviors.CTimestampBehavior',
                'createAttribute'=>'create_time',
                'updateAttribute'=>'update_time',
            ),
            'HistoryBehavior'=>array(
                'class'=>'ext.sprutlab.history.HistoryBehavior',
                'tablemodel'=>$this->tableName(),
                'classmodel'=>__CLASS__,
                'fields'=>array(
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
            ),
        );
    }

    public function relations()
    {
        return array(
            'clinic_district'=>array(self::BELONGS_TO,'AdminDistrict','district_id'),
            'rs'=>array(self::HAS_ONE,'AdminDiagnosticClinicRating','nid'),
            'clinic_user'=>array(self::BELONGS_TO,'AdminUser','uid'),
            'clinic_category_s'=>array(self::HAS_MANY,'AdminDiagnosticPrice','clinic_id'),
            'clinic_subway_s'=>array(self::HAS_MANY,'AdminDiagnosticSubwayClinic','clinic_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'modificator'=>'Модификатор рейтинга',
            'title'=>'Название',
            'district_id'=>'Район',
            'translit'=>'URL страницы',
            'address'=>'Адрес',
            'subway_name'=>'Метро',
            'subway_array'=>'Метро',
            'url'=>'URL',
            'none_status'=>'Не записывать',
            'description'=>'Краткое описание',
            'create_time'=>'Время создания',
            'update_time'=>'Время редактирования',
            'image'=>'Логотип',
            'status'=>'Статус модерации',
            'regime_sun'=>'Режим работы вс.',
            'regime_mon'=>'Режим работы пн.',
            'regime_tue'=>'Режим работы вт.',
            'regime_wed'=>'Режим работы ср.',
            'regime_thu'=>'Режим работы чт.',
            'regime_fri'=>'Режим работы пт.',
            'regime_sat'=>'Режим работы чб.',
            'regime_byd'=>'Режим работы буд.',
            'sames'=>'Схожий',
            'uid'=>'Пользователь ID',
            'lat'=>'Широта',
            'lng'=>'Долгота',
            'body'=>'Содержание',
            'email'=>'E-mail',
            'telephone'=>'Телефон',
            'start_rate'=>'Стартовый рейтинг',
            'old_rate'=>'Старый рейтинг',
            'meta_title'=>'МЕТА: Заголовок',
            'meta_keywords'=>'МЕТА: Слова',
            'meta_description'=>'МЕТА: Описание',
        );
    }

    public function getOldRate()
    {
        $this->_old_rate=0;
        if(!$this->isNewRecord){
            $this->_old_rate=AdminClinic::model()->findByPk($this->id)->start_rate;
        }
        return $this->_old_rate;
    }

    public function afterSave()
    {
        AdminDiagnosticSubwayClinic::model()->deleteAll('clinic_id=:clinic_id',array(':clinic_id'=>$this->id));
        if(!empty($_POST[__CLASS__]['subway_array'])){
            foreach($_POST[__CLASS__]['subway_array'] as $value){
                $subway=AdminSubway::model()->findByAttributes(array('title'=>trim($value['title'])));
                if(!empty($subway->id)){
                    $model=new AdminDiagnosticSubwayClinic;
                    $model->subway_id=$subway->id;
                    $model->clinic_id=$this->primaryKey;
                    $model->lin1=$value['lin1'];
                    $model->lin2=$value['lin2'];
                    $model->save();
                }
            }
        }
        if($this->status==1){
            $this->saveRating();
        }
        return parent::afterSave();
    }

    public function getCountRate()
    {
        $this->_countRate=0;
        if(!empty($this->primaryKey)){
            $this->_countRate=AdminDiagnosticRating::model()->countByAttributes(array('clinic_id'=>$this->primaryKey));
        }
        return $this->_countRate;
    }

    public function beforeValidate()
    {
        if(Yii::app()->user->id){
            $this->uid=Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }

    public function getAwaySubway()
    {
        if(!empty($this->primaryKey)){
            if($this->status==3){
                return 0.0001;
            } else{
                return Yii::app()->db->createCommand("SELECT acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) AS away FROM clinic_diagnostic t LEFT JOIN subway_clinic_diagnostic ON t.id=subway_clinic_diagnostic.clinic_id LEFT JOIN subway sub ON subway_clinic_diagnostic.subway_id=sub.id WHERE t.id='{$this->primaryKey}' AND acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) IS NOT NULL ORDER BY away ASC LIMIT 1")->queryScalar();
            }
        }
    }

    public function getSameBlock()
    {
        $data=array();
        if(!empty($this->id)){
            $saveCriteria='';
            $saveParams=array();
            $criteria=new CDbCriteria();
            $saveCriteria['status']="t.status=1";
            $saveCriteria['id']="t.id!=".$this->id;
            if(!empty($this->sames)){
                $qty=AdminDiagnosticClinic::model()->countByAttributes(array('sames'=>$this->sames));
                if(!empty($qty)){
                    if($qty>3){
                        $saveCriteria['sames']="t.sames='".$this->sames."'";
                    }
                }
            }
            $sort=new CSort(__CLASS__);
            $sort->defaultOrder="CASE WHEN rs.rate IS NULL THEN 1 ELSE 0 END, rs.comm DESC, rs.rate DESC, CASE WHEN clinic_category_s.price IS NULL THEN 1 ELSE 0 END, clinic_category_s.price ASC, CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) ASC";
            if(!empty($this->clinic_subway_s[0])){
                if(!empty($this->clinic_subway_s[0]->sub)){
                    $subway=$this->clinic_subway_s[0]->sub->title;
                    if(!empty($subway->title)){
                        $_defaultOrder=array();
                        if(!empty($subway->lat)&&!empty($subway->lng)){
                            $lat=str_replace(",",".",CHtml::encode($subway->lat));
                            $lng=str_replace(",",".",CHtml::encode($subway->lng));
                            $_defaultOrder[]="CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(sub.lat))*cos(radians({$lat}))*cos(radians({$lng})-radians(sub.lng))+sin(radians(sub.lat))*sin(radians({$lat}))) ASC";
                        }
                        $_defaultOrder[]="CASE WHEN rs.rate IS NULL THEN 1 ELSE 0 END, rs.comm DESC, rs.rate DESC, CASE WHEN clinic_category_s.price IS NULL THEN 1 ELSE 0 END, clinic_category_s.price ASC";
                        $_defaultOrder[]="CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) ASC";
                        if(!empty($_defaultOrder)){
                            $sort->defaultOrder=implode(",",$_defaultOrder);
                        }
                    }
                }
            } else{
                if(empty($saveCriteria['sames'])){
                    return $data;
                }
            }
            $criteria->with=array(
                'rs'=>array('select'=>false),
                'clinic_subway_s'=>array('select'=>false,'with'=>array('sub'=>array('select'=>'title, translit','with'=>array('line')))),
                'clinic_category_s'=>array('select'=>false,'with'=>array('catc_category'=>array('select'=>'name'))),
            );
            $criteria->together=true;
            $criteria->group="t.id";
            if(!empty($saveCriteria)){
                foreach($saveCriteria as $value){
                    $criteria->addCondition($value);
                }
            }
            if(!empty($saveParams)){
                $params=array();
                foreach($saveParams as $value){
                    $params=array_merge($params,$value);
                }
                $criteria->params=$params;
            }
            $sort->applyOrder($criteria);
            $pages=new CPagination(15);
            $pages->pageSize=15;
            $pages->applyLimit($criteria);
            $data=AdminDiagnosticClinic::model()->findAll($criteria);
        }
        return $data;
    }

    public function saveRating($api=true)
    {
        $model=AdminDiagnosticClinicRating::model()->findByAttributes(array('nid'=>$this->primaryKey));
        if(empty($model->id)){
            $model=new AdminDiagnosticClinicRating;
            $model->nid=$this->id;
        }
        if(!empty($this->start_rate)){
            $model->rate1=$this->start_rate;
        }
        if(!empty($this->awaySubway)){
            $model->rate2=$this->awaySubway;
        }
        if($api){
            $model->rate3=$this->getRecord();
        }
        $model->rate4=5;
        $model->comm=10;
        if(!empty($this->modificator)){
            $model->modificator=$this->modificator;
        }
        $model->save();
    }

    private function getRecord()
    {
        $output=0;
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"http://api.medbooking.com/index.php?r=site/rateWithoutId&id=".$this->primaryKey);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array('sn'=>"diagnostic.medbooking.com",'mode'=>"clinic_id")));
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_VERBOSE,1);
        curl_setopt($ch,CURLINFO_HEADER_OUT,1);
        $response=curl_exec($ch);
        if(!empty($response)){
            $array=CJSON::decode($response);
            if(!empty($array[0])){
                $output=$array[0]['qty'];
            }
        }
        return $output;
    }

    public function listPrice($category=null)
    {
        $array=array();
        if(!empty($this->clinic_category_s)){
            foreach($this->clinic_category_s as $value){
                if(!empty($value->category_price_id)){
                    if(!empty($category)&&$category!=$value->catc_category->translit){
                        continue;
                    } elseif(count($array)<10){
                        $array[]=array(
                            'name'=>!empty($value->name)?$value->name:$value->catc_category->name,
                            'price'=>!empty($value->price)?$value->price:'0',
                        );
                    } else{
                        break;
                    }
                }
            }
        }
        return $array;
    }

    public function getSinglePrice()
    {
        $array=array();
        if(!empty($this->clinic_category_s)){
            foreach($this->clinic_category_s as $value){
                if(!empty($value->category_price_id)&&empty($value->parent_id)){
                    $parent=AdminDiagnosticCategory::model()->findByPk($value->catc_category->root);
                    if(empty($array[$parent->id])){
                        $array[$parent->id]=array(
                            'h2'=>!empty($parent->name)?$parent->name:'',
                            'id'=>$parent->id,
                        );
                    }
                    $array[$parent->id]['items'][$value->id]=array(
                        'name'=>!empty($value->name)?$value->name:$value->catc_category->name,
                        'price'=>!empty($value->price)?$value->price:'0',
                        'id'=>$value->id,
                        'sub'=>array(),
                    );
                } else{
                    $array[0]=array(
                        'h2'=>'Другие услуги',
                        'id'=>$parent->id,
                    );
                    $array[0]['items'][$value->id]=array(
                        'name'=>!empty($value->name)?$value->name:$value->catc_category->name,
                        'price'=>!empty($value->price)?$value->price:'0',
                        'id'=>$value->id,
                        'sub'=>array(),
                    );
                }
            }
        }
        $output=array();
        if(!empty($array)){
            $i=0;
            foreach($array as $key=> $value){
                if($i<floor(count($array)/2)){
                    $output['left'][$key]=$value;
                } else{
                    $output['right'][$key]=$value;
                }
                $i++;
            }
        }
        return $output;
    }

    private $_countRate;
    private $_subway_array;
    private $_subway_name;
    private $_selSubways;
    private $_old_rate;

}
