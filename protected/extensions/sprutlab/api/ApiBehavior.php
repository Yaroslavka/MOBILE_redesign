<?php

class ApiBehavior extends CActiveRecordBehavior
{

    public $name='api_status';
    public $host;
    public $method;
    public $flag; // flag controller
    private $url;
    
    private $auth="retuam";
    private $pswd="moller312()";
    
    public function beforeSave($event)
    {
        $this->getOwner()->{$this->name}=1;
        return parent::beforeSave($event);
    }

    public function afterSave($event)
    {
        if(!empty($this->getOwner()->{$this->flag})&&$this->getOwner()->{$this->flag}==2){
//            $this->url="http://".$this->auth.':'.$this->pswd."@".$this->getOwner()->{$this->host}->title."/api/";
//            $ch=curl_init();
//            // после сохранения в API делает возврат на сайт партнера
//            if(!empty($this->getOwner()->isNewRecord)){
//                $data=CJSON::encode(array('jsonrpc'=>"2.0",'method'=>($this->method."Create"),"params"=>$this->getOwner()->attributes,"id"=>$this->getOwner()->primaryKey));
//                curl_setopt($ch,CURLOPT_URL,$this->url."/create");
//            } else{
//                $data=CJSON::encode(array('jsonrpc'=>"2.0",'method'=>($this->method."Update"),"params"=>$this->getOwner()->attributes,"id"=>$this->getOwner()->primaryKey));
//                curl_setopt($ch,CURLOPT_URL,$this->url."/update");
//            }
//            curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
//            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
//            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
//            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//            curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
//            curl_setopt($ch,CURLOPT_VERBOSE,1);
//            curl_setopt($ch,CURLINFO_HEADER_OUT,1);
//            $response=curl_exec($ch); // не применяется
//            curl_close($ch);
//            if(!empty($response)){
//                $r=CJSON::decode($response);
//                if(empty($r['id'])||$r['result']!=$this->getOwner()->primaryKey||$r['result']!='new'){
//                    Yii::app()->db->createCommand("UPDATE ".$this->getOwner()->tableName()." SET {$this->name}=4 WHERE id='{$this->getOwner()->primaryKey}'")->execute();
//                } else {
//                    Yii::app()->db->createCommand("UPDATE ".$this->getOwner()->tableName()." SET {$this->name}=3 WHERE id='{$this->getOwner()->primaryKey}'")->execute();
//                }
//            }
        }
        return parent::afterSave($event);
    }

    public function beforeDelete($event)
    {
        if(!empty($this->getOwner()->{$this->flag})&&$this->getOwner()->{$this->flag}==2){
//            $this->url="http://".$this->auth.':'.$this->pswd."@".$this->getOwner()->{$this->host}->title."/api/";
//            $data=CJSON::encode(array('jsonrpc'=>"2.0",'method'=>($this->method."Delete"),"params"=>$this->getOwner()->attributes,"id"=>$this->getOwner()->primaryKey));
//            $ch=curl_init();
//            curl_setopt($ch,CURLOPT_URL,$this->url."/delete");
//            curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
//            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
//            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
//            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//            curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
//            curl_setopt($ch,CURLOPT_VERBOSE,1);
//            curl_setopt($ch,CURLINFO_HEADER_OUT,1);
//            $response=curl_exec($ch); // не применяется
//            curl_close($ch);
//            if(!empty($response)){
//                $r=CJSON::decode($response);
//                if(empty($r['id'])||$r['result']!=$this->getOwner()->getOwner()->primaryKey){
//                    Yii::app()->db->createCommand("UPDATE ".$this->getOwner()->tableName()." SET {$this->name}=5 WHERE id='{$this->getOwner()->primaryKey}'")->execute();
//                    return false;
//                }
//            }
        }
        return parent::afterDelete($event);
    }

}