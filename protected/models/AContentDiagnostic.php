<?php

/**
 * Created by PhpStorm.
 * User: serg
 * Date: 04.02.14
 * Time: 17:40
 */
class AContentDiagnostic extends CModel
{

    public $id;
    public $title;
    public $status;
    public $author;
    public $email;
    public $sames;
    public $rating;
    public $irating;
    public $translit;
    public $is_desc;
    public $is_irating;
    public $is_subway;
    public $is_det_desc;
    public $is_img;
    public $is_address;
    public $is_mtitle;
    public $is_mdesc;
    public $prices;
    public $is_mkeys;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('id,title,is_irating,status,author,rating,is_desc,is_det_desc,is_img,is_address,is_mtitle,is_mdesc,is_mkeys,sames,translit,is_subway,prices,irating','safe'),
            array('id,title,is_irating,status,author,rating,is_desc,is_det_desc,is_img,is_address,is_mtitle,is_mdesc,is_mkeys,sames,translit,is_subway,prices,irating','safe','on'=>'search'),
        );
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeNames()
    {
        return array('id','title','translit','sames','status','rating','is_irating','is_desc','is_subway','is_det_desc','is_img','is_address','prices','irating','email','author','is_mtitle','is_mdesc','is_mkeys');
    }

    public function attributeNamesSmall()
    {
        return array('id','title','translit','sames','status','rating','is_irating','is_desc','is_subway','is_det_desc','is_img','is_address','prices');
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'email'=>'E-mail',
            'title'=>'Название',
            'translit'=>'URL',
            'prices'=>'Цена',
            'status'=>'Статус',
            'sames'=>'Схожесть',
            'author'=>'Автор',
            'rating'=>'Рейтинг',
            'irating'=>'iРейтинг',
            'is_desc'=>'Короткое',
            'is_det_desc'=>'Полное',
            'is_img'=>'Лого',
            'is_address'=>'Адрес',
            'is_mtitle'=>'MT',
            'is_mdesc'=>'MD',
            'is_mkeys'=>'MK',
            'is_subway'=>'Метро',
            'is_irating'=>'iРейтинг',
        );
    }

    public function attributeConditions()
    {
        return array(
            'id'=>'c.id=%s',
            'title'=>'c.title LIKE %s',
            'translit'=>'c.title LIKE %s',
            'sames'=>'c.sames LIKE %s',
            'email'=>'c.email LIKE %s',
            'status'=>'c.status=%s',
            'author'=>'u.email LIKE %s or u.username LIKE %s or u.email LIKE %s',
            'rating'=>'c.start_rate=%s',
            'irating'=>'rs.rate=%s',
            'is_irating'=>array(
                '0'=>'rs.rate=0 OR rs.rate IS NULL',
                '1'=>'rs.rate=0>0',
            ),
            'is_desc'=>array(
                '0'=>'CHAR_LENGTH(c.description)=0',
                '1'=>'CHAR_LENGTH(c.description)>0',
            ),
            'is_det_desc'=>array(
                '0'=>'CHAR_LENGTH(c.body)=0',
                '1'=>'CHAR_LENGTH(c.body)>0',
            ),
            'is_img'=>array(
                '0'=>'CHAR_LENGTH(c.image)=0',
                '1'=>'CHAR_LENGTH(c.image)>0',
            ),
            'is_address'=>array(
                '0'=>'CHAR_LENGTH(c.address)=0',
                '1'=>'CHAR_LENGTH(c.address)>0',
            ),
            'is_mtitle'=>array(
                '0'=>'CHAR_LENGTH(c.meta_title)=0',
                '1'=>'CHAR_LENGTH(c.meta_title)>0',
            ),
            'is_mdesc'=>array(
                '0'=>'CHAR_LENGTH(c.meta_description)=0',
                '1'=>'CHAR_LENGTH(c.meta_description)>0',
            ),
            'is_mkeys'=>array(
                '0'=>'CHAR_LENGTH(c.meta_keywords)=0',
                '1'=>'CHAR_LENGTH(c.meta_keywords)>0',
            ),
        );
    }

    public function attributesForHaving()
    {
        return array(
            'is_subway'=>'boolean',
            'is_irating'=>'boolean',
            'prices'=>'int',
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
        $model=new AContentDiagnostic;
        $queryConditions=$this->getQueryConditions($model);
        $command=Yii::app()->db->createCommand('SELECT
            c.id AS id,
            c.title AS title,
            c.translit AS translit,
            c.status AS status,
            c.start_rate AS rating,
            c.email AS email,
            c.sames AS sames,
            if(CHAR_LENGTH(c.description)=0, 0, 1) AS is_desc,
            if(CHAR_LENGTH(concat(u.email,u.username))=0, u.email, if(CHAR_LENGTH(u.email)=0, u.username, u.email)) author,
            if(CHAR_LENGTH(c.image)=0, 0, 1) AS is_img,
            if(rs.rate=0 OR rs.rate IS NULL, 0, 1) AS is_irating,
            if(CHAR_LENGTH(c.address)=0, 0, 1) AS is_address,
            if(CHAR_LENGTH(c.body)=0, 0, 1) AS is_det_desc,
            if(CHAR_LENGTH(c.meta_title)=0, 0, 1) AS is_mtitle,
            if(CHAR_LENGTH(c.meta_description)=0, 0, 1) AS is_mdesc,
            if(CHAR_LENGTH(c.meta_keywords)=0, 0, 1) AS is_mkeys,
            if(count(cs.id)>0, 1, 0) AS is_subway,
            rs.rate AS irating,
            (SELECT COUNT(id) FROM clinic_price WHERE (clinic_id=c.id)) AS prices 
        FROM clinic_diagnostic c
            LEFT JOIN subway_clinic_diagnostic cs ON c.id = cs.clinic_id
            LEFT JOIN clinic_diagnostic_rating rs ON c.id = rs.nid
            LEFT JOIN user AS u ON c.uid = u.uid
        '.$queryConditions['where'].'
        GROUP BY c.id
        '.$queryConditions['having'].'
        ORDER BY title
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
        if(empty($model)||empty($_GET['AContentDiagnostic'])){
            return $queryConditions;
        }
        $attributeConditions=$model->attributeConditions();
        $attributesForHaving=$model->attributesForHaving();
        $model->unsetAttributes();
        $model->attributes=$_GET['AContentDiagnostic'];
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
