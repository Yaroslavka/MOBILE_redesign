<?php

class AdminClinic extends CActiveRecord
{

    public $relevant;
    public $_specialistAll;
    public $lat_id;
    public $lng_id;
    public $metro;
    public $min_car;
    public $min_man;

    public function beforeValidate()
    {
        if(Yii::app()->user->id){
            $this->uid=Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }

    public function afterSave()
    {
        // обновляем все станции метро у клиники, при каждом сохранении
        AdminSubwayClinic::model()->deleteAll('clinic_id=:clinic_id',array(':clinic_id'=>$this->id));
        if(!empty($_POST['AdminClinic']['subway_array'])){
            foreach($_POST['AdminClinic']['subway_array'] as $value){
                $subway=AdminSubway::model()->findByAttributes(array('title'=>trim($value['title'])));
                if(!empty($subway->id)){
                    $model=new AdminSubwayClinic;
                    $model->subway_id=$subway->id;
                    $model->clinic_id=$this->primaryKey;
                    $model->lin1=$value['lin1'];
                    $model->lin2=$value['lin2'];
                    $model->save();
                }
            }
        }
        // открываем уже сохраненную модель, без смены статуса
        $model=AdminClinic::model()->findByPk($this->id);
        $data=AdminClinicDoctor::model()->with(array('c','d'))->findAll("d.status=:status AND c.id=:id",array(":status"=>$model->status,":id"=>$this->id));
        // обновляем статус докторов в немодерированной клинике, либо в модерированной, если доктора привязаны
        if(!empty($data)){
            foreach($data as $value){
                if(!empty($value->d->id)){
                    AdminDoctor::model()->updateByPk($value->d->id,array("status"=>$this->status));
                }
            }
        }
        // сохранение рейтинга
        if($this->status==1){
            $this->saveRating();
        } elseif($this->status==3){
            $this->saveRatingNetwork();
        }
        return parent::afterSave();
    }

    public function afterDelete()
    {
        $data=AdminClinicDoctor::model()->with(array('c','d'))->findAll("c.id=:id",array(":id"=>$this->primaryKey));
        // меняем статус докторам
        if(!empty($data)){
            foreach($data as $value){
                if(!empty($value->d->id)){
                    AdminDoctor::model()->updateByPk($value->d->id,array("status"=>2));
                }
            }
        }
        return parent::afterDelete();
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'clinic';
    }

    public function rules()
    {
        return array(
            array('title','required'),
            array('translit','unique'),
            array('address, translit, meta_keywords, meta_title, companyname','length','max'=>255),
            array('alias, telephone, inn, url, district','length','max'=>128),
            array('district_id, district_line_id, comm, network_id, status, external_id, uid, view_status, clinic_city_line_id, clinic_city_id','numerical','integerOnly'=>true),
            array('start_rate, modificator, rate10','numerical'),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser'),
            array('city_id','exist','attributeName'=>'id','className'=>'AdminCity'),
            array('city_line_id','exist','attributeName'=>'id','className'=>'AdminCityLine'),
            array('district_id','exist','attributeName'=>'id','className'=>'AdminDistrict'),
            array('district_line_id','exist','attributeName'=>'id','className'=>'AdminDistrictLine'),
            array('network_id','exist','attributeName'=>'id','className'=>'AdminClinic'),
            array('create_time, update_time, image','safe'),
            array('countRecords, countReviews, countDoctors, selSubways, description, body, meta_description, meta_keywords, district, meta_title','safe'),
            array('view_status, none_status','length','max'=>1),
            array('email','email'),
            array('sames, subways_data','safe'),
            array('lat, lng, regime_sun, regime_mon, regime_tue, regime_wed, regime_thu, regime_fri, regime_sat, regime_byd','length','max'=>128),
            array('create_time, update_time, companyname, meta_description, meta_keywords, meta_title,body, regime_sun, regime_mon, regime_tue, regime_wed, regime_thu, regime_fri, regime_sat, regime_byd, uid, title, alias, external_id, translit, description, address, email, telephone, inn, url, lat, lng','default','setOnEmpty'=>true,'value'=>null),
        );
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
        );
    }

    public function relations()
    {
        return array(
            'clinic_district'=>array(self::BELONGS_TO,'AdminDistrict','district_id'),
            'clinic_district_line'=>array(self::BELONGS_TO,'AdminDistrictLine','district_line_id'),
            'clinic_city'=>array(self::BELONGS_TO,'AdminCity','clinic_city_id'),
            'clinic_city_line'=>array(self::BELONGS_TO,'AdminCityLine','clinic_city_line_id'),
            'clinic_network'=>array(self::BELONGS_TO,'AdminClinic','network_id'),
            'clinic_user'=>array(self::BELONGS_TO,'AdminUser','uid'),
            'rs'=>array(self::HAS_ONE,'AdminClinicRating','nid'),
            'as'=>array(self::HAS_ONE,'AdminClinicAdd','nid'),
            // HAS_MANY
            'clinic_review_s'=>array(self::HAS_MANY,'AdminReview','clinic_id'),
            'clinic_review_s1'=>array(self::HAS_MANY,'AdminReview','clinic_id','condition'=>'clinic_review_s1.status!=0'),
            'clinic_price_s'=>array(self::HAS_MANY,'AdminPrice','clinic_id'),
            'clinic_review_s_limit'=>array(self::HAS_MANY,'AdminReview','clinic_id',"limit"=>5,'condition'=>'status!=0'),
            'clinic_doctor_s'=>array(self::HAS_MANY,'AdminClinicDoctor','clinic_id','with'=>array('d'),'condition'=>'d.status=1','group'=>'d.id'),
            'clinic_doctor_sa'=>array(self::HAS_MANY,'AdminClinicDoctor','clinic_id','with'=>array('d')),
            'clinic_doctor_single'=>array(self::HAS_MANY,'AdminClinicDoctor','clinic_id','group'=>'doctor_id','with'=>array('d'=>array('with'=>array('rs'))),'order'=>'rs.rate DESC'),
            'clinic_subway_s'=>array(self::HAS_MANY,'AdminSubwayClinic','clinic_id'),
            'clinic_category_s'=>array(self::HAS_MANY,'AdminCategoryClinic','clinic_id'),
            'clinic_services_s0'=>array(self::HAS_MANY,'AdminNodeServicePrice','clinic_id'),
            'clinic_services_s'=>array(self::HAS_MANY,'AdminNodeServicePrice','clinic_id','with'=>array('service_price_category'),'order'=>'service_price_category.name'),
            'clinic_network_s'=>array(self::HAS_MANY,'AdminClinic','network_id','condition'=>'status=1'),
            'clinic_network_s3'=>array(self::HAS_MANY,'AdminClinic','network_id','condition'=>'status=1','limit'=>3),
            'clinic_action'=>array(self::HAS_MANY,'AdminClinicAction','clinic_id','with'=>array('clinic_action_action'=>array('condition'=>'DATE(clinic_action_action.end_time)>="'.addslashes(date('Y-m-d')).'"'))),
        );
    }

    public function saveRatingNetwork() // только если статус 3
    {
        if(!empty($this->primaryKey)&&$this->inFil){ // rate1, rate2, rate4, nid формируем clinic_rating
            $model=AdminClinicRating::model()->findByAttributes(array('nid'=>$this->primaryKey));
            if(empty($model->id)){
                $model=new AdminClinicRating;
                $model->nid=$this->primaryKey;
            }
            $data=Yii::app()->db->createCommand("SELECT id, MAX(rate10) AS r10 FROM clinic_rating2 WHERE nid IN (".$this->inFil.") ORDER BY r10 DESC")->queryRow();
            if(!empty($data['id'])){
                $ratingModel=AdminClinicRating::model()->findByPk($data['id']);
                unset($ratingModel->nid);
                unset($ratingModel->id);
                $model->attributes=$ratingModel->attributes;
                $model->nid=$this->id;
                if($model->save(false)){
                    $_model=AdminClinicRating::model()->findByPk($model->id);
                    $rate10=(!empty($_model->rate10))?$_model->rate10:0;
                    $comm=(!empty($_model->comm))?$_model->comm:0;
                    Yii::app()->db->createCommand("UPDATE ".$this->tableName()." SET rate10='{$rate10}', comm='{$comm}' WHERE id='{$this->id}'");
                }
            }
        }
    }

    public function saveRating($api=true)
    {
        $model=AdminClinicRating::model()->findByAttributes(array('nid'=>$this->primaryKey));
        if(empty($model->id)){
            $model=new AdminClinicRating;
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
        if($model->save()){
            $_model=AdminClinicRating::model()->findByPk($model->id);
            $rate10=(!empty($_model->rate10))?$_model->rate10:0;
            $comm=(!empty($_model->comm))?$_model->comm:0;
            Yii::app()->db->createCommand("UPDATE ".$this->tableName()." SET rate10='{$rate10}', comm='{$comm}' WHERE id='{$this->id}'");
        }
    }

    private function getRecord()
    {
        $output=0;
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,"http://api.medbooking.com/index.php?r=site/rateWithoutId&id=".$this->primaryKey);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array('sn'=>"medbooking.com",'mode'=>"clinic_id")));
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

    public function getClinic_city_name()
    {
        if(!empty($this->clinic_city_id)){
            $model=AdminCity::model()->findByPk($this->clinic_city_id);
            if(!empty($model->title)){
                $this->_clinic_city_name=$model->title;
            }
        }
        return $this->_clinic_city_name;
    }

    public function setClinic_city_name($value)
    {
        $this->_clinic_city_name=$value;
    }

    public function getRegimeClinic()
    {
        $data=array();
        if(!empty($this->regime_byd)){
            $data['буд.']=$this->regime_byd;
        }
        if(!empty($this->regime_mon)){
            $data['пн.']=$this->regime_mon;
        }
        if(!empty($this->regime_tue)){
            $data['вт.']=$this->regime_tue;
        }
        if(!empty($this->regime_wed)){
            $data['ср.']=$this->regime_wed;
        }
        if(!empty($this->regime_thu)){
            $data['чт.']=$this->regime_thu;
        }
        if(!empty($this->regime_fri)){
            $data['пт.']=$this->regime_fri;
        }
        if(!empty($this->regime_sat)){
            $data['сб.']=$this->regime_sat;
        }
        if(!empty($this->regime_sun)){
            $data['вс.']=$this->regime_sun;
        }
        return $data;
    }

    public function getSpecialist()
    {
        return $this->_specialist;
    }

    public function setSpecialist($value)
    {
        $this->_specialist=$value;
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

    public function setSpecialistAll($value)
    {
        $this->_specialistAll=$value;
    }

    public function getSubway_array()
    {
        $array=array();
        $data=AdminSubwayClinic::model()->findAllByAttributes(array('clinic_id'=>$this->primaryKey));
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
        $data=AdminSubwayClinic::model()->findAllByAttributes(array('clinic_id'=>$this->primaryKey));
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
        $this->_selSubways=Yii::app()->db->createCommand("SELECT subway.title AS title FROM subway_clinic LEFT JOIN subway ON subway_clinic.subway_id=subway.id WHERE subway_clinic.clinic_id=".$this->id)->queryAll();
        return $this->_selSubways;
    }

    public function setSelSubways($value)
    {
        $this->_selSubways=$value;
    }

    public function getCountReview()
    {
        if(!empty($this->id)){
            $this->_countReviews=Yii::app()->db->createCommand("SELECT COUNT(*) as qty FROM reviews WHERE status=1 AND clinic_id=".$this->id)->queryScalar();
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
            $this->_countDoctors=Yii::app()->db->createCommand("SELECT COUNT(DISTINCT doctor_id) as qty FROM clinic_doctor WHERE clinic_id=".$this->id)->queryScalar();
            return $this->_countDoctors;
        }
    }

    public function setCountDoctors($value)
    {
        $this->_countDoctors=$value;
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

    public function getOldRate()
    {
        $this->_old_rate=0;
        if(!$this->isNewRecord){
            $this->_old_rate=AdminClinic::model()->findByPk($this->id)->start_rate;
        }
        return $this->_old_rate;
    }

    public function getRateRating()
    {
        $_rate=0;
        $_count=0;
        if(!empty($this->primaryKey)){
            if($this->status==3&&$this->inFil){
                $data=Yii::app()->db->createCommand("SELECT AVG(doctor_value) AS doctor_value, AVG(attention_value) AS attention_value, AVG(price_value) AS price_value FROM reviews WHERE clinic_id IN (".$this->inFil.") AND status=1")->queryRow();
            } else{
                $data=Yii::app()->db->createCommand("SELECT AVG(doctor_value) AS doctor_value, AVG(attention_value) AS attention_value, AVG(price_value) AS price_value FROM reviews WHERE clinic_id=".$this->primaryKey." AND status=1")->queryRow();
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
        return Yii::app()->db->createCommand("SELECT COUNT(reviews.id) AS qty FROM reviews LEFT JOIN clinic ON clinic.id=reviews.clinic_id WHERE reviews.status=1 AND clinic.network_id=".$this->primaryKey)->queryScalar();
    }

    public function getDoctorNetworkCount()
    {
        return Yii::app()->db->createCommand("SELECT COUNT(DISTINCT doctor.id) AS qty FROM clinic_doctor LEFT JOIN clinic ON clinic.id=clinic_doctor.clinic_id LEFT JOIN doctor ON doctor.id=clinic_doctor.doctor_id WHERE clinic.network_id=".$this->primaryKey)->queryScalar();
    }

    public function getClinicNetworkCount()
    {
        return Yii::app()->db->createCommand("SELECT COUNT(id) AS qty FROM clinic WHERE network_id=".$this->primaryKey." AND status=1")->queryScalar();
    }

    public function getClinicNetworkDoctorsSpeciality()
    {
        return AdminClinicDoctor::model()->with(array('c','d'))->findAll(array("condition"=>'c.network_id='.$this->primaryKey.' AND d.status=1','group'=>"t.speciality",'order'=>'t.speciality'));
    }

    public function getClinicNetworkDoctors()
    {
        return AdminClinicDoctor::model()->with(array('c','d'))->findAll(array("condition"=>'c.network_id='.$this->primaryKey.' AND d.status=1','group'=>'t.doctor_id','order'=>'d.rate10 DESC'));
    }

    public function getClinicNetworkReviews()
    {
        return AdminReview::model()->with(array('review_clinic','review_doctor'))->findAll(array("condition"=>'review_clinic.network_id='.$this->primaryKey.' AND review_clinic.status=1 AND t.status=1'));
    }

    public function getClinicNetworkReviewsLimit()
    {
        return AdminReview::model()->with(array('review_clinic','review_doctor'))->findAll(array("condition"=>'review_clinic.network_id='.$this->primaryKey.' AND review_clinic.status=1 AND t.status=1','limit'=>10));
    }

    public function getAwaySubway()
    {
        if(!empty($this->primaryKey)){
            if($this->status==3){
                return 0.0001;
            } else{
                return Yii::app()->db->createCommand("SELECT acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) AS away FROM clinic t LEFT JOIN subway_clinic ON t.id=subway_clinic.clinic_id LEFT JOIN subway sub ON subway_clinic.subway_id=sub.id WHERE t.id='{$this->primaryKey}' AND acos(cos(radians(t.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(t.lng))+sin(radians(t.lat))*sin(radians(sub.lat))) IS NOT NULL ORDER BY away ASC LIMIT 1")->queryScalar();
            }
        }
    }

    public function getSameBlock()
    {
        $data=array();
        if(!empty($this->id)){
            $criteria=new CDbCriteria();
            $criteria->addCondition("t.status=1");
            $criteria->addCondition("t.id!=".$this->id);
            if(!empty($this->sames)){
                $qty=AdminClinic::model()->countByAttributes(array('sames'=>$this->sames));
                if(!empty($qty)){
                    if($qty>3){
                        $criteria->addCondition("t.sames='".$this->sames."'");
                    }
                }
            }
            $criteria->with=array(
                'clinic_subway_s'=>array('select'=>false,'with'=>array('sub'=>array('select'=>'title, translit'))),
                'clinic_category_s'=>array('select'=>false,'with'=>array('catc_category'=>array('select'=>'translit, lft, rgt, root'))),
            );
            $criteria->together=true;
            $criteria->group="t.id";
            $criteria->limit=15;
            $data=AdminClinic::model()->findAll($criteria);
        }
        return $data;
    }

    private $gImages;
    private $_countDoctors;
    private $_specialist;
    private $_subway_array;
    private $_subway_name;
    private $_selSubways;
    private $_countReviews;
    private $_old_rate;
    private $_clinic_city_name;

}
