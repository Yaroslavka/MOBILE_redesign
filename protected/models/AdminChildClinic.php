<?php

class AdminChildClinic extends CActiveRecord
{

    public $lat_id;
    public $lng_id;
    public $metro;
    public $min_car;
    public $min_man;
    public $_specialistAll;

    public function getSpecialist()
    {
        return $this->_specialist;
    }

    public function getSpecialistAll()
    {
        $array=array();
        if(!empty($this->clinic_doctor_single)){
            foreach($this->clinic_doctor_single as $value){
                if(!empty($value->d->doctor_category_s)){
                    foreach($value->d->doctor_category_s as $item){
                        if(!empty($item->catd_category->name)){
                            $array[$item->catd_category->id]=$item->catd_category->name;
                        }
                    }
                }
            }
        }
        $this->_specialistAll=implode(", ",$array);
        return $this->_specialistAll;
    }

    public function setSpecialist($value)
    {
        $this->_specialist=$value;
    }

    public function setSpecialistAll($value)
    {
        $this->_specialistAll=$value;
    }

    public function getSubway_array()
    {
        $array=array();
        $data=AdminChildSubwayClinic::model()->findAllByAttributes(array('clinic_id'=>$this->primaryKey));
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
        $data=AdminChildSubwayClinic::model()->findAllByAttributes(array('clinic_id'=>$this->primaryKey));
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
        $this->_selSubways=Yii::app()->db->createCommand("SELECT subway.title AS title FROM child_subway_clinic LEFT JOIN subway ON child_subway_clinic.subway_id=subway.id WHERE child_subway_clinic.clinic_id=".$this->id)->queryAll();
        return $this->_selSubways;
    }

    public function setSelSubways($value)
    {
        $this->_selSubways=$value;
    }

    public function getCountReview()
    {
        if(!empty($this->id)){
            $this->_countReviews=Yii::app()->db->createCommand("SELECT COUNT(*) as qty FROM child_reviews WHERE status=1 AND clinic_id=".$this->id)->queryScalar();
            return $this->_countReviews;
        }
    }

    public function setCountReview($value)
    {
        $this->_countReviews=$value;
    }

    public function getCountDoctors()
    {
        if(!empty($this->id)){
            $this->_countDoctors=Yii::app()->db->createCommand("SELECT COUNT(DISTINCT doctor_id) as qty FROM child_clinic_doctor WHERE clinic_id=".$this->id)->queryScalar();
            return $this->_countDoctors;
        }
    }

    public function setCountDoctors($value)
    {
        $this->_countDoctors=$value;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'child_clinic';
    }

    public function rules()
    {
        return array(
            array('title','required','message'=>'Название клиники обязательное значение'),
            array('translit','unique','message'=>'Транслитерация уже существует'),
            array('address, translit, meta_keywords, meta_title, companyname','length','max'=>255),
            array('alias, telephone, inn, url, district','length','max'=>128),
            array('district_id, district_id, network_id, status, external_id, uid, view_status','numerical','integerOnly'=>true),
            array('start_rate, modificator','numerical'),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser'),
            array('district_id','exist','attributeName'=>'id','className'=>'AdminDistrict'),
            array('network_id','exist','attributeName'=>'id','className'=>'AdminChildClinic'),
            array('create_time, update_time, image','safe'),
            array('countRecords, countReviews, countDoctors, selSubways, description, body, meta_description, meta_keywords, district, meta_title','safe'),
            array('view_status, none_status','length','max'=>1),
            array('email','email'),
            array('sames','safe'),
            array('lat, lng, regime_sun, regime_mon, regime_tue, regime_wed, regime_thu, regime_fri, regime_sat, regime_byd','length','max'=>128),
            array('create_time, update_time, companyname, meta_description, meta_keywords, meta_title,body, regime_sun, regime_mon, regime_tue, regime_wed, regime_thu, regime_fri, regime_sat, regime_byd, uid, title, alias, external_id, translit, description, address, email, telephone, inn, url, lat, lng','default','setOnEmpty'=>true,'value'=>null),
        );
    }

    public function getGImages()
    {
        if(!empty($this->gallery_id)){
            $galleryPhoto=GalleryPhoto::model()->findAllByAttributes(array('gallery_id'=>$this->gallery_id),array('order'=>'rank DESC'));
            if(!empty($galleryPhoto)){
                foreach($galleryPhoto as $key=> $value){
                    $this->gImages[$key]['path']=$value->getImageUrl();
                    $this->gImages[$key]['big']=$value->getImageUrl();
                    $this->gImages[$key]['photo']=$value->getImageUrl('photo');
                    $this->gImages[$key]['small']=$value->getImageUrl('small');
                    $this->gImages[$key]['title']=!empty($value['name'])?$value['name']:'';
                    $this->gImages[$key]['alt']=!empty($value['description'])?$value['description']:'';
                }
            }
        } else{
            $this->gImages=array();
        }
        return $this->gImages;
    }

    public function behaviors()
    {
        return array(
            'ImageBehavior'=>array(
                'class'=>'ext.sprutlab.image.ImageBehavior',
                'versions'=>array(
                    'small'=>array(
                        'centeredpreview'=>array(100,100),
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
            'galleryBehavior'=>array(
                'class'=>'GalleryBehavior',
                'idAttribute'=>'gallery_id',
                'versions'=>array(
                    'small'=>array(
                        'centeredpreview'=>array(213,138),
                    ),
                    'big'=>array(
                        'centeredpreview'=>array(1000,800),
                    ),
                ),
                'name'=>true,
                'description'=>true,
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
            ),
        );
    }

    public function relations()
    {
        return array(
            'rs'=>array(self::HAS_ONE,'AdminChildClinicRating','nid'),
            'clinic_user'=>array(self::BELONGS_TO,'AdminUser','uid'),
            'clinic_district'=>array(self::BELONGS_TO,'AdminDistrict','district_id'),
            'clinic_network'=>array(self::BELONGS_TO,'AdminChildClinic','network_id'),
            'clinic_review_s'=>array(self::HAS_MANY,'AdminChildReview','clinic_id'),
            'clinic_review_s_limit'=>array(self::HAS_MANY,'AdminChildReview','clinic_id',"limit"=>5,'condition'=>'status!=0'),
            'clinic_doctor_s'=>array(self::HAS_MANY,'AdminChildClinicDoctor','clinic_id','with'=>array('d'),'condition'=>'d.status=1'),
            'clinic_doctor_sa'=>array(self::HAS_MANY,'AdminChildClinicDoctor','clinic_id','with'=>array('d')),
            'clinic_doctor_single'=>array(self::HAS_MANY,'AdminChildClinicDoctor','clinic_id','group'=>'doctor_id','with'=>array('d'=>array('with'=>array('rs'))),'order'=>'rs.rate DESC'),
            'clinic_subway_s'=>array(self::HAS_MANY,'AdminChildSubwayClinic','clinic_id'),
            'clinic_category_s'=>array(self::HAS_MANY,'AdminChildCategoryClinic','clinic_id'),
            'clinic_network_s'=>array(self::HAS_MANY,'AdminChildClinic','network_id','condition'=>'status=1'),
            'clinic_network_s3'=>array(self::HAS_MANY,'AdminChildClinic','network_id','condition'=>'status=1','limit'=>3),
        );
    }

    public function attributeLabels()
    {
        return array(
            'countRecords'=>'Записей',
            'specialistAll'=>'Специалисты',
            'countReviews'=>'Отзывов',
            'countDoctors'=>'Докторов',
            'id'=>'ID',
            'modificator'=>'Модификатор рейтинга',
            'none_status'=>'Не записывать',
            'view_status'=>'Показывать',
            'url'=>'URL',
            'external_id'=>'Филиал ID для интегратора',
            'alias'=>'Алиас для интегратора',
            'title'=>'Название',
            'translit'=>'URL страницы',
            'address'=>'Адрес',
            'image'=>'Логотип',
            'subway_name'=>'Метро',
            'subway_array'=>'Метро',
            'district_id'=>'Район ID',
            'description'=>'Краткое описание',
            'create_time'=>'Время создания',
            'update_time'=>'Время редактирования',
            'status'=>'Статус модерации',
            'regime_sun'=>'Режим работы вс.',
            'regime_mon'=>'Режим работы пн.',
            'regime_tue'=>'Режим работы вт.',
            'regime_wed'=>'Режим работы ср.',
            'regime_thu'=>'Режим работы чт.',
            'regime_fri'=>'Режим работы пт.',
            'regime_sat'=>'Режим работы чб.',
            'regime_byd'=>'Режим работы буд.',
            'inn'=>'Код',
            'network_id'=>'Сеть',
            'lat'=>'Широта',
            'lng'=>'Долгота',
            'body'=>'Содержание',
            'sames'=>'Схожий',
            'uid'=>'Пользователь ID',
            'email'=>'E-mail',
            'telephone'=>'Телефон',
            'url'=>'Сайт',
            'companyname'=>'Компания',
            'meta_title'=>'МЕТА: Заголовок',
            'meta_keywords'=>'МЕТА: Слова',
            'meta_description'=>'МЕТА: Описание',
            'district'=>'Округ',
            'specialist'=>'Специалисты',
            'start_rate'=>'Стартовый рейтинг',
        );
    }

    public function getOldRate()
    {
        $this->_old_rate=0;
        if(!$this->isNewRecord){
            $this->_old_rate=AdminChildClinic::model()->findByPk($this->id)->start_rate;
        }
        return $this->_old_rate;
    }

    public function beforeSave()
    {
        if(!$this->isNewRecord&&!empty($_POST[__CLASS__]['start_rate'])){
            $model=AdminChildClinicRating::model()->findByAttributes(array('nid'=>$this->primaryKey));
            if(empty($model->id)){
                $model=new AdminClinicRating;
                $model->nid=$this->id;
            }
            $model->save();
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        AdminChildSubwayClinic::model()->deleteAll('clinic_id=:clinic_id',array(':clinic_id'=>$this->id));
        if(!empty($_POST[__CLASS__]['subway_array'])){
            foreach($_POST[__CLASS__]['subway_array'] as $value){
                $subway=AdminSubway::model()->findByAttributes(array('title'=>trim($value['title'])));
                if(!empty($subway->id)){
                    $model=new AdminChildSubwayClinic;
                    $model->subway_id=$subway->id;
                    $model->clinic_id=$this->primaryKey;
                    $model->lin1=$value['lin1'];
                    $model->lin2=$value['lin2'];
                    $model->save();
                }
            }
        }
        $model=AdminChildClinic::model()->findByPk($this->id);
        $data=AdminChildClinicDoctor::model()->with(array('c','d'))->findAll("d.status=:status AND c.id=:id",array(":status"=>$model->status,":id"=>$this->id));
        if(!empty($data)){
            foreach($data as $value){
                if(!empty($value->d->id)){
                    AdminChildDoctor::model()->updateByPk($value->d->id,array("status"=>$this->status));
                }
            }
        }
        if($this->status==1){
            $this->saveRating();
        }
        return parent::afterSave();
    }

    public function afterDelete()
    {
        $data=AdminChildClinicDoctor::model()->with(array('c','d'))->findAll("c.id=:id",array(":id"=>$this->primaryKey));
        if(!empty($data)){
            foreach($data as $value){
                if(!empty($value->d->id)){
                    AdminChildDoctor::model()->updateByPk($value->d->id,array("status"=>2));
                }
            }
        }
        return parent::afterDelete();
    }

    public function getRateRating()
    {
        $_rate=0;
        $_count=0;
        if(!empty($this->primaryKey)){
            if($this->status==3&&$this->inFil){
                $data=Yii::app()->db->createCommand("SELECT AVG(doctor_value) AS doctor_value, AVG(attention_value) AS attention_value, AVG(price_value) AS price_value FROM child_reviews WHERE clinic_id IN (".$this->inFil.") AND status=1")->queryRow();
            } else{
                $data=Yii::app()->db->createCommand("SELECT AVG(doctor_value) AS doctor_value, AVG(attention_value) AS attention_value, AVG(price_value) AS price_value FROM child_reviews WHERE clinic_id=".$this->primaryKey." AND status=1")->queryRow();
            }
            if(!empty($data)){
                $_rate+=(!empty($data['doctor_value'])?$data['doctor_value']:0);
                $_count+=(!empty($data['doctor_value'])?1:0);
                $_rate+=(!empty($data['price_value'])?$data['price_value']:0);
                $_count+=(!empty($data['price_value'])?1:0);
                $_rate+=(!empty($data['attention_value'])?$data['attention_value']:0);
                $_count+=(!empty($data['attention_value'])?1:0);
                if($_count&&$_rate){
                    return round($_rate/$_count,2);
                }
            }
        }
        return $_rate;
    }

    public function getCountRate()
    {
        $this->_countRate=0;
        if(!empty($this->primaryKey)){
            $this->_countRate=AdminChildRating::model()->countByAttributes(array('clinic_id'=>$this->primaryKey));
        }
        return $this->_countRate;
    }

    public function getCommRate()
    {
        if(!empty($this->primaryKey)){
            if($this->status==3&&$this->inFil){
                return Yii::app()->db->createCommand("SELECT COUNT(id) AS qty FROM child_reviews WHERE clinic_id IN (".$this->inFil.") AND status=1")->queryScalar();
            } else{
                return Yii::app()->db->createCommand("SELECT COUNT(id) AS qty FROM child_reviews WHERE clinic_id='".$this->primaryKey."' AND status=1")->queryScalar();
            }
        }
    }

    public function getInFil()
    {
        $output='';
        if(!empty($this->clinic_network_s)){
            $data=array();
            foreach($this->clinic_network_s as $value){
                $data[]=$value->primaryKey;
            }
            if(!empty($data)){
                $output=implode(",",$data);
            }
        }
        return $output;
    }

    public function getRateNetworkCount()
    {
        $count=0;
        if(!empty($this->inFil)){
            $count=Yii::app()->db->createCommand("SELECT COUNT(id) AS qty FROM child_reviews WHERE clinic_id IN (".$this->inFil.") AND status=1")->queryScalar();
        }
        return $count;
    }

    public function getDoctorNetworkCount()
    {
        $count=0;
        if(!empty($this->inFil)){
            $count=Yii::app()->db->createCommand("SELECT COUNT(DISTINCT child_doctor.id) AS qty FROM child_clinic_doctor LEFT JOIN child_clinic ON child_clinic.id=child_clinic_doctor.clinic_id LEFT JOIN child_doctor ON child_doctor.id=child_clinic_doctor.doctor_id WHERE child_clinic.network_id=".$this->primaryKey)->queryScalar();
        }
        return $count;
    }

    public function getClinicNetworkCount()
    {
        $count=0;
        if(!empty($this->primaryKey)){
            $count=Yii::app()->db->createCommand("SELECT COUNT(id) AS qty FROM child_clinic WHERE network_id=".$this->primaryKey." AND status=1")->queryScalar();
        }
        return $count;
    }

    public function getClinicNetworkDoctorsSpeciality()
    {
        $data=array();
        if(!empty($this->primaryKey)){
            $data=AdminChildClinicDoctor::model()->with(array('c','d'=>array('with'=>array('rs'))))->findAll(array("condition"=>'c.network_id='.$this->primaryKey.' AND d.status=1','group'=>"t.speciality",'order'=>'t.speciality'));
        }
        return $data;
    }

    public function getClinicNetworkDoctors()
    {
        $data=array();
        if(!empty($this->primaryKey)){
            $data=AdminChildClinicDoctor::model()->with(array('c','d'=>array('with'=>array('rs'))))->findAll(array("condition"=>'c.network_id='.$this->primaryKey.' AND d.status=1','group'=>'t.doctor_id','order'=>'rs.rate DESC'));
        }
        return $data;
    }

    public function getClinicNetworkReviews()
    {
        $data=array();
        if(!empty($this->primaryKey)){
            $data=AdminReview::model()->with(array('review_clinic'))->findAll(array("condition"=>'child_review_clinic.network_id='.$this->primaryKey.' AND child_review_clinic.status=1 AND t.status=1'));
        }
        return $data;
    }

    public function getClinicNetworkReviewsLimit()
    {
        $data=array();
        if(!empty($this->primaryKey)){
            $data=AdminReview::model()->with(array('review_clinic'))->findAll(array("condition"=>'child_review_clinic.network_id='.$this->primaryKey.' AND child_review_clinic.status=1 AND t.status=1','limit'=>10));
        }
        return $data;
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
                return Yii::app()->db->createCommand("SELECT acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) AS away FROM child_clinic t LEFT JOIN child_subway_clinic ON t.id=child_subway_clinic.clinic_id LEFT JOIN subway sub ON child_subway_clinic.subway_id=sub.id WHERE t.id='{$this->primaryKey}' AND acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) IS NOT NULL ORDER BY away ASC LIMIT 1")->queryScalar();
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
                $qty=AdminChildClinic::model()->countByAttributes(array('sames'=>$this->sames));
                if(!empty($qty)){
                    if($qty>3){
                        $saveCriteria['sames']="t.sames='".$this->sames."'";
                    }
                }
            }
            $sort=new CSort('AdminChildClinic');
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
            $data=AdminChildClinic::model()->findAll($criteria);
        }
        return $data;
    }

    public function saveRating($api=true)
    {
        $model=AdminChildClinicRating::model()->findByAttributes(array('nid'=>$this->primaryKey));
        if(empty($model->id)){
            $model=new AdminChildClinicRating;
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
        $model->rate4=$this->rateRating;
        $model->comm=$this->commRate;
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
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array('sn'=>"child",'mode'=>"clinic_id")));
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

    private $_countRate;
    private $_countDoctors;
    private $_specialist;
    private $_subway_array;
    private $_subway_name;
    private $_selSubways;
    private $_countReviews;
    private $gImages;
    private $_old_rate;

}
