<?php

class AdminDoctor extends CActiveRecord
{

    public $clinics;
    public $clinic_name;

    public function beforeValidate()
    {
        if(Yii::app()->user->id){
            $this->uid=Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }

    public function afterSave()
    {
        if($this->status==1){
            $this->saveRating();
        }
        return parent::afterSave();
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'doctor';
    }

    public function rules()
    {
        return array(
            array('fname','required','message'=>'Не указано имя доктора'),
            array('lname','required','message'=>'Не указана фамилия доктора'),
            array('awards, education, associated, professional, description, image, create_time, update_time','safe'),
            array('meta_title, meta_keywords, meta_description, price,awards, education, associated, professional, description','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('meta_title, sname, translit, telephone, degree, position, speciality','length','max'=>255),
            array('translit','unique','message'=>'Транслитерация уже существует'),
            array('gender, status, view_status, child','length','max'=>1),
            array('uid, house, external_id','numerical','integerOnly'=>true),
            array('email','email'),
            array('sames','safe'),
            array('start_rate, rate10, modificator','numerical'),
            array('countRecords, countReviews, countDoctors, selClinics, price','safe'),
            array('uid','exist','attributeName'=>'uid','className'=>'AdminUser','message'=>'Учетной записи пользователя не существует'),
            array('startyear, comm','numerical','integerOnly'=>true),
            array('associated, education, awards, meta_title, meta_keywords, meta_description, external_id, translit, sname, description, speciality, position, email, professional, degree, telephone','default','setOnEmpty'=>true,'value'=>null),
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
                        'centeredpreview'=>array(150,150),
                    ),
                ),
            ),
            'TranslitBehavior'=>array(
                'class'=>'ext.sprutlab.translit.TranslitBehavior',
                'name'=>'fio',
                'model'=>__CLASS__,
                'translit'=>'translit',
            ),
            'CTimestampBehavior'=>array(
                'class'=>'zii.behaviors.CTimestampBehavior',
                'createAttribute'=>'create_time',
                'updateAttribute'=>'update_time',
            ),
        );
    }

    public function relations()
    {
        return array(
            'rs'=>array(self::HAS_ONE,'AdminDoctorRating','nid'),
            'ds'=>array(self::HAS_ONE,'AdminDoctorAdd','nid'),
            'doctor_user'=>array(self::BELONGS_TO,'AdminUser','uid'),
            'doctor_review_s'=>array(self::HAS_MANY,'AdminReview','doctor_id'),
            'doctor_price_s'=>array(self::HAS_MANY,'AdminPrice','doctor_id'),
            'doctor_price_s1'=>array(self::HAS_MANY,'AdminPrice','doctor_id','limit'=>1),
            'doctor_review_s_limit'=>array(self::HAS_MANY,'AdminReview','doctor_id',"limit"=>5,'condition'=>'status!=0'),
            'doctor_clinic_s'=>array(self::HAS_MANY,'AdminClinicDoctor','doctor_id'),
            'doctor_clinic_single'=>array(self::HAS_MANY,'AdminClinicDoctor','doctor_id','group'=>'clinic_id'),
            'doctor_category_s'=>array(self::HAS_MANY,'AdminCategoryDoctor','doctor_id'),
            'doctor_category_s2'=>array(self::HAS_MANY,'AdminCategoryDoctor','doctor_id','with'=>array('catd_category'),'condition'=>'catd_category.level=2'),
            'doctor_illness_s'=>array(self::HAS_MANY,'AdminNodeIllnessDoctor','doctor_id'),
            'doctor_service_s'=>array(self::HAS_MANY,'AdminNodeServiceDoctor','doctor_id'),
        );
    }

    public function saveRating($api=true)
    {
        $model=AdminDoctorRating::model()->findByAttributes(array('nid'=>$this->primaryKey));
        if(empty($model->id)){
            $model=new AdminDoctorRating;
            $model->nid=$this->id;
        }
        if(!empty($this->start_rate)){
            $model->rate1=$this->start_rate;
        }
        if(!empty($this->startyear)){
            $model->rate2=$this->startyear;
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
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array('sn'=>"medbooking.com",'mode'=>"doctor_id")));
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

    public function getSelSubways()
    {
        $this->_selClinics=Yii::app()->db->createCommand("SELECT clinic.title AS title FROM clinic_doctor LEFT JOIN clinic ON clinic_doctor.clinic_id=clinic.id WHERE clinic_doctor.doctor_id=".$this->id)->queryAll();
        return $this->_selClinics;
    }

    public function setSelSubways($value)
    {
        $this->_selSubways=$value;
    }

    public function getCountReview()
    {
        if(!empty($this->id)){
            $this->_countReviews=Yii::app()->db->createCommand("SELECT COUNT(id) as qty FROM reviews WHERE status=1 AND doctor_id=".$this->id)->queryScalar();
            return $this->_countReviews;
        }
    }

    public function setCountReview($value)
    {
        $this->_countReviews=$value;
    }

    public function getCountClinics()
    {
        $this->_countClinics=Yii::app()->db->createCommand("SELECT COUNT(id) as qty FROM clinic_doctor WHERE doctor_id=".$this->id)->queryScalar();
        return $this->_countClinics;
    }

    public function setCountClinics($value)
    {
        $this->_countClinics=$value;
    }

    public function getFio()
    {
        if(empty($this->_fio)){
            $this->_fio='';
            if(!empty($this->lname)){
                $this->_fio.=!empty($this->_fio)?" ".trim($this->lname):trim($this->lname);
            }
            if(!empty($this->fname)){
                $this->_fio.=!empty($this->_fio)?" ".trim($this->fname):trim($this->fname);
            }
            if(!empty($this->sname)){
                $this->_fio.=!empty($this->_fio)?" ".trim($this->sname):trim($this->sname);
            }
        }
        return $this->_fio;
    }

    public function setFio($value)
    {
        $this->_fio=$value;
    }

    public function getClinicsName()
    {
        $array=array();
        if(!empty($this->id)){
            $data=AdminClinicDoctor::model()->findAllByAttributes(array('doctor_id'=>$this->id));
            if(!empty($data)){
                foreach($data as $value){
                    $array[]=$value['c']['title'];
                }
            }
        }
        return $array;
    }

    public function getRateRating()
    {
        $_rate=0;
        $_count=0;
        if(!empty($this->primaryKey)){
            $data=Yii::app()->db->createCommand("SELECT AVG(doctor_value) AS doctor_value, AVG(attention_value) AS attention_value, AVG(price_value) AS price_value FROM reviews WHERE doctor_id='".$this->primaryKey."' AND status=1")->queryRow();
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

    public function getCommRate()
    {
        return Yii::app()->db->createCommand("SELECT COUNT(id) FROM reviews WHERE doctor_id=".$this->primaryKey." AND status=1")->queryScalar();
    }

    public function getOldRate()
    {
        $this->_old_rate=0;
        if(!$this->isNewRecord){
            $this->_old_rate=AdminDoctor::model()->findByPk($this->id)->start_rate;
        }
        return $this->_old_rate;
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
            $sort=new CSort('AdminDoctor');
            $sort->defaultOrder="CASE WHEN t.image IS NULL THEN 1 ELSE 0 END, CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC, CASE WHEN doctor_category_s.price IS NULL THEN 1 ELSE 0 END, doctor_category_s.price ASC, CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END";
            if(!empty($this->sames)){
                $qty=AdminDoctor::model()->countByAttributes(array('sames'=>$this->sames));
                if(!empty($qty)&&$qty>3){
                    $saveCriteria['sames']="t.sames='".$this->sames."'";
                }
            }
            $clinics=$this->doctor_clinic_s[0];
            if(!empty($clinics)){
                if(!empty($clinics->c)){
                    if(!empty($clinics->c->clinic_subway_s[0])){
                        if(!empty($clinics->c->clinic_subway_s[0]->sub)){
                            $subway=$clinics->c->clinic_subway_s[0]->sub;
                            if(!empty($subway->translit)){
                                $_defaultOrder=array();
                                if(!empty($subway->lat)&&!empty($subway->lng)){
                                    $lat=str_replace(",",".",CHtml::encode($subway->lat));
                                    $lng=str_replace(",",".",CHtml::encode($subway->lng));
                                    $_defaultOrder[]="CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(sub.lat))*cos(radians({$lat}))*cos(radians({$lng})-radians(sub.lng))+sin(radians(sub.lat))*sin(radians({$lat}))) ASC";
                                }
                                $_defaultOrder[]="CASE WHEN t.rate10 IS NULL THEN 1 ELSE 0 END, t.comm DESC, t.rate10 DESC, CASE WHEN doctor_category_s.price IS NULL THEN 1 ELSE 0 END, doctor_category_s.price ASC";
                                $_defaultOrder[]="CASE WHEN sub.lat IS NULL THEN 1 ELSE 0 END, acos(cos(radians(c.lat))*cos(radians(sub.lat))*cos(radians(sub.lng)-radians(c.lng))+sin(radians(c.lat))*sin(radians(sub.lat))) ASC";
                                if(!empty($_defaultOrder)){
                                    $sort->defaultOrder=implode(",",$_defaultOrder);
                                }
                            }
                        }
                    }
                }
            } else{
                if(empty($saveCriteria['sames'])){
                    return $data;
                }
            }
            $criteria->with=array(
                'doctor_clinic_s'=>array('select'=>false,'with'=>array('c'=>array('select'=>false,'with'=>array('clinic_subway_s'=>array('select'=>false,'with'=>array('sub'=>array('select'=>'title, translit','with'=>array('line')))),'clinic_category_s'=>array('select'=>false))))),
                'doctor_category_s'=>array('select'=>false,'with'=>array('catd_category'=>array('select'=>'name'))),
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
            $data=AdminDoctor::model()->findAll($criteria);
        }
        return $data;
    }

    public function firstPriceCategoryClinic($category_id,$clinic_id)
    {
        return AdminPrice::model()->findByAttributes(array('clinic_id'=>$clinic_id,'category_id'=>$category_id,'doctor_id'=>$this->primaryKey),array('order'=>'price'));
    }

    public function firstPriceCategory($category)
    {
        if(!empty($this->doctor_price_s1)){
            $category_id=AdminCategory::model()->findByAttributes(array('translit'=>$category));
            if(!empty($category_id->id)){
                $data=AdminPrice::model()->findAllByAttributes(array('category_id'=>$category_id->id,'doctor_id'=>$this->primaryKey),array('order'=>'price'));
                return $data;
            }
        }
        return array();
    }

    public function firstPriceCategorySingle($category)
    {
        return AdminPrice::model()->findByAttributes(array('category_id'=>$category,'doctor_id'=>$this->primaryKey),array('order'=>'price'));
    }
    
    public function getFirstPrice()
    {
        return AdminPrice::model()->findAllByAttributes(array('doctor_id'=>$this->primaryKey),array('order'=>'price'));
    }    
    
    public function getFirstPriceSingle()
    {
        return AdminPrice::model()->findByAttributes(array('doctor_id'=>$this->primaryKey),array('order'=>'price'));
    }    

    private $_selClinics;
    private $_countReviews;
    private $_countClinics;
    private $_fio;
    private $_old_rate;

}
