<?php

/**
 * Created by PhpStorm.
 * User: serg
 * Date: 04.02.14
 * Time: 17:41
 */
class AContentDoctor extends CModel
{

    public $id;
    public $price;
    public $email;
    public $status;
    public $author;
    public $rating;
    public $is_desc;
    public $is_img;
    public $translit;
    public $is_mtitle;
    public $is_price;
    public $is_startyear;
    public $is_education;
    public $is_awards;
    public $is_professional;
    public $is_associated;
    public $is_mdesc;
    public $is_mkeys;
    public $is_clinic;
    public $is_irating;
    public $reviews;
    public $irating;
    public $fio;
    public $sames;
    public $speciality;
    public $startyear;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('id,is_irating,is_associated,is_awards,is_professional,is_education,is_startyear,is_price,price,email,status,author,irating,rating,is_desc,is_img,is_mtitle,is_mdesc,is_mkeys,is_clinic,reviews,fio,sames,speciality,startyear','safe'),
            array('id,is_irating,is_associated,is_awards,is_professional,is_education,is_startyear,is_price,price,email,status,author,irating,rating,is_desc,is_img,is_mtitle,is_mdesc,is_mkeys,is_clinic,reviews,fio,sames,speciality,startyear','safe','on'=>'search'),
        );
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeNames()
    {
        return array('id','fio','translit','sames','speciality','status','rating','is_irating','is_desc','is_price','is_img','reviews','is_startyear','is_education','is_associated','is_awards','is_professional','email','author','irating','is_mtitle','is_mdesc','is_mkeys','is_clinic','startyear','price');
    }

    public function attributeNamesSmall()
    {
        return array('id','fio','translit','sames','speciality','status','rating','is_irating','is_desc','is_price','is_img','reviews','is_startyear','is_education');
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'fio'=>'ФИО',
            'translit'=>'URL',
            'sames'=>'Схожесть',
            'speciality'=>'Специальность',
            'status'=>'Статус',
            'rating'=>'Рейтинг',
            'is_irating'=>'iРейтинг',
            'is_desc'=>'Описание',
            'is_price'=>'Цены',
            'is_img'=>'Фото',
            'reviews'=>'Отзывы',
            'is_startyear'=>'Стаж',
            'is_education'=>'Образование',
            'is_associated'=>'Ассоциации',
            'is_awards'=>'Награды',
            'is_professional'=>'Навыки',
            'email'=>'E-mail',
            'author'=>'Автор',
            'irating'=>'iРейтинг',
            'is_mtitle'=>'MT',
            'is_mdesc'=>'MD',
            'is_mkeys'=>'MK',
            'is_clinic'=>'Клиники',
            'startyear'=>'Стаж',
            'price'=>'Цена',
        );
    }

    public function attributeConditions()
    {
        return array(
            'id'=>'d.id=%s',
            'price'=>'d.price=%s',
            'email'=>'d.email like %s',
            'status'=>'d.status=%s',
            'author'=>'u.email like %s or u.username like %s or u.email like %s',
            'rating'=>'d.start_rate=%s',
            'irating'=>'rs.rate=%s',
            'fio'=>'CONCAT_WS(" ",d.lname,d.fnames,d.sname) LIKE %s',
            'sames'=>'d.sames LIKE %s',
            'speciality'=>'d.speciality LIKE %s',
            'startyear'=>'d.startyear LIKE %s',
            'is_irating'=>array(
                '0'=>'rs.rate=0 OR rs.rate IS NULL',
                '1'=>'rs.rate=0>0',
            ),
            'is_startyear'=>array(
                '0'=>'CHAR_LENGTH(d.startyear)=0 OR d.startyear IS NULL',
                '1'=>'CHAR_LENGTH(d.startyear)>0',
            ),
            'is_education'=>array(
                '0'=>'CHAR_LENGTH(d.education)=0',
                '1'=>'CHAR_LENGTH(d.education)>0',
            ),
            'is_associated'=>array(
                '0'=>'CHAR_LENGTH(d.associated)=0',
                '1'=>'CHAR_LENGTH(d.associated)>0',
            ),
            'is_awards'=>array(
                '0'=>'CHAR_LENGTH(d.awards)=0',
                '1'=>'CHAR_LENGTH(d.awards)>0',
            ),
            'is_professional'=>array(
                '0'=>'CHAR_LENGTH(d.professional)=0',
                '1'=>'CHAR_LENGTH(d.professional)>0',
            ),
            'is_desc'=>array(
                '0'=>'CHAR_LENGTH(d.description)=0',
                '1'=>'CHAR_LENGTH(d.description)>0',
            ),
            'is_img'=>array(
                '0'=>'CHAR_LENGTH(d.image)=0',
                '1'=>'CHAR_LENGTH(d.image)>0',
            ),
            'is_mtitle'=>array(
                '0'=>'CHAR_LENGTH(d.meta_title)=0',
                '1'=>'CHAR_LENGTH(d.meta_title)>0',
            ),
            'is_mdesc'=>array(
                '0'=>'CHAR_LENGTH(d.meta_description)=0',
                '1'=>'CHAR_LENGTH(d.meta_description)>0',
            ),
            'is_mkeys'=>array(
                '0'=>'CHAR_LENGTH(d.meta_keywords)=0',
                '1'=>'CHAR_LENGTH(d.meta_keywords)>0',
            ),
        );
    }

    public function attributesForHaving()
    {
        return array(
            'reviews'=>'int',
            'is_clinic'=>'boolean',
            'is_price'=>'boolean',
            'is_irating'=>'boolean',
        );
    }

    public function search()
    {
        $result=new CArrayDataProvider($this->getContent());
        $result->setPagination(array('pageSize'=>50));
        return $result;
    }

    public function searchCsv()
    {
        $result=new CArrayDataProvider($this->getContent());
        return $result;
    }

    private function getContent()
    {
        $model=new AContentDoctor;
        $queryConditions=$this->getQueryConditions($model);
        $command=Yii::app()->db->createCommand('SELECT
            d.id AS id,
            d.price AS price,
            d.translit AS translit,
            CONCAT_WS(" ", d.lname, d.fname, d.sname) AS fio,
            d.start_rate AS rating,
            d.status AS status,
            d.sames AS sames,
            d.email AS email,
            d.startyear AS startyear,
            d.speciality AS speciality,
            if(CHAR_LENGTH(concat(u.email,u.username))=0, u.email, if(CHAR_LENGTH(u.email)=0, u.username, u.email)) AS author,
            if(CHAR_LENGTH(d.description)=0, 0, 1) AS is_desc,
            if(rs.rate=0 OR rs.rate IS NULL, 0, 1) AS is_irating,
            if(CHAR_LENGTH(d.image)=0, 0, 1) AS is_img,
            if(CHAR_LENGTH(d.meta_title)=0, 0, 1) AS is_mtitle,
            if(CHAR_LENGTH(d.meta_description)=0, 0, 1) AS is_mdesc,
            if(CHAR_LENGTH(d.meta_keywords)=0, 0, 1) AS is_mkeys,
            if(CHAR_LENGTH(d.startyear)=0 OR d.startyear IS NULL, 0, 1) AS is_startyear,
            if(CHAR_LENGTH(d.professional)=0, 0, 1) AS is_professional,
            if(CHAR_LENGTH(d.education)=0, 0, 1) AS is_education,
            if(CHAR_LENGTH(d.awards)=0, 0, 1) AS is_awards,
            if(CHAR_LENGTH(d.associated)=0, 0, 1) AS is_associated,
            if(count(dc.id)>0, 1, 0) AS is_clinic,
            if(count(dp.id)>0, 1, 0) AS is_price,
            rs.rate AS irating,
            (SELECT COUNT(id) FROM reviews WHERE (doctor_id=d.id)) AS reviews
        FROM doctor as d
            LEFT JOIN clinic_doctor dc ON d.id = dc.doctor_id AND dc.speciality IS NOT NULL AND dc.position IS NOT NULL
            LEFT JOIN clinic c ON dc.clinic_id = c.id
            LEFT JOIN doctor_rating rs ON d.id = rs.nid
            LEFT JOIN price dp ON dp.doctor_id = d.id
            LEFT JOIN user u ON d.uid = u.uid
        '.$queryConditions['where'].'
        GROUP BY d.id
        '.$queryConditions['having'].'
        ORDER BY fio
        ');
        $data=$command->queryAll();
        $result=array();
        if(!empty($data)){
            foreach($data as $key=> $value){
                $result[$value['id']]=$value;
                unset($data[$key]);
            }
        }
        if($result===null||is_array($result)==false){
            return array();
        }
        return $result;
    }

    public function getQueryConditions($model)
    {
        $queryConditions=array(
            'where'=>'',
            'having'=>'',
        );
        if(empty($model)||empty($_GET['AContentDoctor'])){
            return $queryConditions;
        }
        $attributeConditions=$model->attributeConditions();
        $attributesForHaving=$model->attributesForHaving();
        $model->unsetAttributes();
        $model->attributes=$_GET['AContentDoctor'];
        $whereCondsArray=array();
        $havingCondsArray=array();
        foreach($model->getAttributes() as $name=> $value){
            if($value===null||$value===''){
                continue;
            }
            if(isset($attributeConditions[$name])==false&&isset($attributesForHaving[$name])==false){
                continue;
            }
            if(isset($attributesForHaving[$name])){
                array_push($havingCondsArray,"$name=".(int)$value);
                continue;
            }
            if(is_array($attributeConditions[$name])){
                if(isset($attributeConditions[$name][$value])==false){
                    continue;
                }
                $whereCondsArray[]=$attributeConditions[$name][$value];
            } else{
                if(strpos($attributeConditions[$name],' LIKE ')===false){
                    $whereCondsArray[]=str_replace('%s',str_replace("'",'',$value),$attributeConditions[$name]);
                } else{
                    $whereCondsArray[]=str_replace('%s',Yii::app()->db->quoteValue('%'.$value.'%'),$attributeConditions[$name]);
                }
            }
        }
        if(empty($whereCondsArray)==false){
            $queryConditions['where']=' WHERE '.implode(' AND ',$whereCondsArray);
        }
        if(empty($havingCondsArray)==false){
            $queryConditions['having']=' HAVING '.implode(' AND ',$havingCondsArray);
        }
        return $queryConditions;
    }

}
