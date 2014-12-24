<?php

class ClinicUrlRule extends CBaseUrlRule
{

    public $connectionID='db';

    public function createUrl($manager,$route,$params,$ampersand)
    {
        if($route==='clinic/singleNetwork'){
            if(isset($params['id'])){
                $id=addslashes(strip_tags($params['id']));
                if(Yii::app()->controller->id!='default'){
                    $model=Yii::app()->db->createCommand("SELECT * FROM clinic WHERE translit='{$id}' AND status=3")->queryRow();
                }
                if(!empty($model['id'])){
                    return $params['id'];
                }
            }
        }
        return false;
    }

    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
        if(preg_match('%^([\w\-]+)?$%',$pathInfo,$matches)){
            if(!empty($matches[1])&&$matches[0]==$matches[1]){
                $cat=addslashes(strip_tags($matches[1]));
                $model=Yii::app()->db->createCommand("SELECT translit FROM clinic WHERE translit='{$cat}' AND status=3")->queryRow();
                if(!empty($model['translit'])){
                    $_GET['id']=$model['translit'];
                    return 'clinic/singleNetwork';
                }
            }
        }
        return false;
    }

}
