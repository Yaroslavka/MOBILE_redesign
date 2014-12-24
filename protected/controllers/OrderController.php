<?php

class OrderController extends MobiController
{

    private function order()
    {
        if(!empty($_POST['timeslot_id'])){
            $timeslot=AdminTimeslot::model()->findByPk($_POST['timeslot_id']);
            if(empty($timeslot->dc->d->translit)){
                throw new CHttpException(404,'Страница не найдена ТАЙМСЛОТ.');
            }
            $route=Yii::app()->createUrl('/doctor/single',array('id'=>$timeslot->dc->d->translit));
        }
        if(!empty($_POST['clinic_id'])){
            $clinic=AdminClinic::model()->findByPk($_POST['clinic_id']);
            if(empty($clinic->translit)){
                throw new CHttpException(404,'Страница не найдена КЛИНИКА.');
            }
            $route=Yii::app()->createUrl('/clinicMobi/single',array('id'=>$clinic->translit));
            if(!empty($_POST['doctor_id'])){
                $doctor=AdminDoctor::model()->findByPk($_POST['doctor_id']);
                if(empty($doctor->translit)){
                    throw new CHttpException(404,'Страница не найдена ДОКТОР.');
                }
                $route=Yii::app()->createUrl('/doctorMobi/single',array('id'=>$doctor->translit));
            }
        } else{
            throw new CHttpException(404,'Страница не найдена.');
        }
        return array('route'=>$route,'clinic'=>$clinic,'doctor'=>!empty($doctor)?$doctor:null,'timeslot'=>!empty($timeslot)?$timeslot:null);
    }

    public function actionIndex()
    {
        if(Yii::app()->request->isPostRequest){
            $this->renderPartial('index',$this->order(),false,true);
        } else{
            throw new CHttpException(404,'Шаблон не найден.');
        }
    }

    public function actionHome()
    {
        if(Yii::app()->request->isPostRequest){
            $this->renderPartial('home',$this->order(),false,true);
        } else{
            throw new CHttpException(404,'Шаблон не найден.');
        }
    }

    public function actionCall()
    {
        $this->renderPartial("call",array(),false,true);
    }

    private function districts()
    {
        $data=AdminDistrict::model()->with(array('dline','clinic_lines'))->findAll(array('order'=>'dline.title, t.title','condition'=>'clinic_lines.id IS NOT NULL'));
        $array=array();
        if(!empty($data)){
            $_district='';
            foreach($data as $value){
                if(empty($_district)||$_district!=$value->dline->title){
                    $_district=$value->dline->title;
                    $array[$value->dline->id]=array(
                        'title'=>!empty($value->dline->title)?$value->dline->title:'',
                        'translit'=>!empty($value->dline->translit)?$value->dline->translit:'',
                        'items'=>array(),
                    );
                }
                $array[$value->dline->id]['items'][$value->id]['title']=!empty($value->title)?$value->title:'';
                $array[$value->dline->id]['items'][$value->id]['translit']=!empty($value->translit)?$value->translit:'';
            }
        }
        return $array;
    }

    public function actionSubway()
    {
        $this->renderPartial("subway",array('data'=>$this->districts()),false,true);
    }

    public function actionDistrict()
    {
        $this->renderPartial("district",array('data'=>$this->districts()),false,true);
    }

    public function actionCategory()
    {
        $this->renderPartial("services",array(),false,true);
    }

    public function actionDoctorClinicRegime($j=0,$l=0)
    {
        if(!empty($_REQUEST['doctor_id'])&&!empty($_REQUEST['clinic_id'])){
            $flag=AdminTimeslot::model()->with(array('dc'))->find('DATE(t.start_date)>=CURDATE() AND dc.doctor_id=:doctor_id AND dc.clinic_id=:clinic_id',array(':doctor_id'=>$_REQUEST['doctor_id'],':clinic_id'=>$_REQUEST['clinic_id']));
            $data=$this->getDataDate($_REQUEST['doctor_id'],$_REQUEST['clinic_id'],$j,$l);
            if(!empty($flag)){
                $this->renderPartial('//doctor/elements/_timeslot_single',array('j'=>$j,'flag'=>$flag,'clinic_id'=>$_REQUEST['clinic_id'],'doctor_id'=>$_REQUEST['doctor_id'],'data'=>$data),false,true);
            } else{
                $model=new AApiRecord;
                $clinic_id=$_REQUEST['clinic_id'];
                $doctor_id=$_REQUEST['doctor_id'];
                if(!empty($clinic_id)){
                    $clinic=AdminClinic::model()->findByPk($clinic_id);
                    if(empty($clinic->id)) throw new CHttpException(404,'Страница не найдена КЛИНИКА.');
                    $model->clinic_id=$clinic->id;
                    $model->clinic_name=$clinic->title;
                    $model->route=Yii::app()->createUrl('/clinicMobi/single',array('id'=>$clinic->translit));
                }
                if(!empty($doctor_id)){
                    $doctor=AdminDoctor::model()->findByPk($doctor_id);
                    if(empty($doctor->id)) throw new CHttpException(404,'Страница не найдена ДОКТОР.');
                    if(empty($clinic)){
                        $dclinic=AdminClinicDoctor::model()->findByAttributes(array('doctor_id'=>$doctor->id));
                        if(!empty($dclinic->id)){
                            $clinic=$dclinic->clinic;
                            $model->clinic_id=$clinic->id;
                            $model->clinic_name=$clinic->title;
                        }
                    }
                    $model->doctor_id=$doctor->id;
                    $model->doctor_name=$doctor->fio;
                    $model->route=Yii::app()->createUrl('/doctor/single',array('id'=>$doctor->translit));
                }
                $this->renderPartial('//doctorMobi/elements/_timeslot_single',array('model'=>$model,'clinic'=>!empty($clinic)?$clinic:null,'doctor'=>!empty($doctor)?$doctor:null),false,true);
            }
        }
    }

    /**
     * Выбор календаря
     * @param type $doctor_id
     * @param type $clinic_id
     * @return type
     */
    private function getDataDate($doctor_id,$clinic_id=null,$j,$l=0)
    {
        $data=array();
        $doctor_id=addslashes(CHtml::encode($doctor_id));
        if(!empty($clinic_id)) $clinic_id=addslashes(CHtml::encode($clinic_id));
        $k=(1+$j)*7+$l;
        for($i=$j*7; $i<$k; $i++){
            if(!empty($clinic_id)&&!empty($doctor_id)){
                $data[$i]['items']=AdminTimeslot::model()->with(array('dc'))->findAll(array('condition'=>'DATE(t.start_date)=DATE_ADD(CURDATE(), INTERVAL '.$i.' DAY) AND dc.doctor_id='.$doctor_id.' AND dc.clinic_id='.$clinic_id,'order'=>'t.start_date ASC'));
            } elseif(!empty($doctor_id)){
                $data[$i]['items']=AdminTimeslot::model()->with(array('dc'))->findAll(array('condition'=>'DATE(t.start_date)=DATE_ADD(CURDATE(), INTERVAL '.$i.' DAY) AND dc.doctor_id='.$doctor_id,'order'=>'t.start_date ASC'));
            }
            $data[$i]['day']=mb_substr(strftime("%a",time()+24*3600*$i),0,4);
            $data[$i]['date']=date("d.m.y",time()+24*3600*$i);
        }
        if(empty($j)){
            $data[0]['date']='Сегодня';
            $data[1]['date']='Завтра';
        }
        return $data;
    }

}
