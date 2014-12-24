<?php

class MobiController extends CController
{

    public $pageTitle='';
    public $pageTop='';
    public $pageDescription='';
    public $pageKeywords;
    public $pageCanonical;
    public $pageH1;
    public $link;
    public $data;
    public $dataSubway;

    public function servicePrice($category_id,$clinic_id)
    {
        $model=AdminNodeServicePrice::model()->findByAttributes(array('category_id'=>$category_id,'clinic_id'=>$clinic_id));
        if(!empty($model->price)){
            if($model->price == '00'){
                return 'по запросу';
            }else{
                return $model->price.' руб.';
            }
        }
        return "&mdash;";
    }

    protected function categories($category)
    {
        $data=array();
        $categories=Yii::app()->db->createCommand("SELECT * FROM category WHERE level=2 ORDER BY name")->queryAll();
        if(!empty($categories)){
            foreach($categories as $value){
                $data[]=array(
                    'translit'=>$value['translit'],
                    'title'=>$value['name'],
                    'title_clinic'=>$value['name_clinic'],
                    'title_spec'=>$value['name_spec'],
                    'title_service'=>$value['name_service'],
                    'home'=>$value['home'],
                    'child'=>$value['child'],
                    'active'=>!empty($category)&&($category==$value['translit'])?true:false,
                );
            }
        }
        return $data;
    }

    protected function subways($subway)
    {
        $data=array();
        $subways=Yii::app()->db->createCommand("SELECT * FROM subway ORDER BY title")->queryAll();
        if(!empty($subways)){
            foreach($subways as $value){
                $data[]=array(
                    'translit'=>$value['translit'],
                    'title'=>$value['title'],
                    'active'=>!empty($subway)&&($subway==$value['translit'])?true:false,
                );
            }
        }
        return $data;
    }

    /**
     * Нормальная обрезка текста
     * @param type $string
     * @param type $maxlen
     * @return type 
     */
    public function cutString($string,$maxlen)
    {
        $len=(mb_strlen($string)>$maxlen)?mb_strripos(mb_substr($string,0,$maxlen),' '):$maxlen;
        $cutStr=mb_substr($string,0,$len);
        return (mb_strlen($string)>$maxlen)?''.$cutStr.' ...':''.$cutStr.'';
    }

    public function initAlias()
    {
        if(!Yii::app()->request->isPostRequest){
            $this->pageTitle=Yii::app()->name;
            $request=str_replace("?ajax=1","",Yii::app()->request->requestUri);
            $alias=Yii::app()->db->createCommand()->select('*')->from('alias')->where("url=:url OR CONCAT(url,'/')=:url",array(':url'=>$request))->queryRow();
            if($request==''){
                $alias=Yii::app()->db->createCommand("SELECT * FROM alias WHERE url='/' LIMIT 1")->queryRow();
            }
            $aliasH1=$alias;
            $this->pageH1=isset($aliasH1['h1'])?$aliasH1['h1']:'';
            if(!empty($alias)){
                $this->pageTitle=isset($alias['meta_title'])?$alias['meta_title']:null;
                $this->pageDescription=isset($alias['meta_description'])?$alias['meta_description']:null;
                $this->pageCanonical=isset($alias['url_canonical'])?$alias['url_canonical']:null;
                $this->pageTop=isset($alias['top'])?$alias['top']:null;
            }
        }
    }

    public function blogInitAlias()
    {
        if(!Yii::app()->request->isPostRequest){
            $this->pageTitle=Yii::app()->name;
            $request=str_replace("?ajax=1","",Yii::app()->request->requestUri);
            $alias=Yii::app()->db->createCommand()->select('*')->from('alias')->where("url=:url OR CONCAT(url,'/')=:url",array(':url'=>$request))->queryRow();
            if($request==''){
                $alias=Yii::app()->db->createCommand("SELECT * FROM alias WHERE url='/' LIMIT 1")->queryRow();
            }
            $aliasH1=$alias;
            if(!empty($_GET['category'])&&(!empty($_GET['page'])||!empty($_GET['sort']))){
                $_path=Yii::app()->request->requestUri;
                $path=explode("/page/",$_path);
                if(!empty($path[0])){
                    $_url=$path[0];
                } else{
                    $_url=$_path;
                }
                $_data=parse_url($_url);
                if(!empty($_data['path'])){
                    $aliasH1=Yii::app()->db->createCommand()->select('*')->from('alias')->where("url LIKE '%:url%'",array(':url'=>$_data['path']))->queryRow();
                }
            } elseif(!empty($_GET['page'])){
                $_path=Yii::app()->request->requestUri;
                $path=explode("/page/",$_path);
                if(!empty($path[0])){
                    $_url=$path[0];
                } else{
                    $_url=$_path;
                }
                $_data=parse_url($_url);
                if(!empty($_data['path'])){
                    $aliasH1=Yii::app()->db->createCommand()->select('*')->from('alias')->where("url = '".$_data['path']."'")->queryRow();
                } else{
                    $aliasH1=Yii::app()->db->createCommand()->select('*')->from('alias')->where("url LIKE '/'")->queryRow();
                }
            }
            $this->pageH1=isset($aliasH1['h1'])?$aliasH1['h1']:'';
            if(!empty($alias)){
                $this->pageTitle=isset($alias['meta_title'])?$alias['meta_title']:null;
                $this->pageDescription=isset($alias['meta_description'])?$alias['meta_description']:null;
                $this->pageKeywords=isset($alias['meta_keywords'])?$alias['meta_keywords']:null;
                $this->pageCanonical=isset($alias['url_canonical'])?$alias['url_canonical']:null;
                $this->pageTop=isset($alias['top'])?$alias['top']:null;
            }
            if(empty($this->pageCanonical)&&!empty($_GET['title'])){
                $pdata=parse_url(Yii::app()->request->requestUri);
                if(!empty($pdata['path'])){
                    $this->pageCanonical='http://medbooking.com'.$pdata['path'];
                }
            }
        }
    }

    protected function getAError($array=null)
    {
        if(!empty($_GET)){
            $get=$_GET;
            if(!empty($array)){
                foreach($array as $value){
                    if(!empty($get[$value])){
                        unset($get[$value]);
                    }
                }
            }
            if(!empty($get)){
                throw new CHttpException(404,"Страница не существует на сайте.");
            }
        }
    }

    public function init()
    {
        Yii::app()->theme='m';
        return parent::init();
    }

}
