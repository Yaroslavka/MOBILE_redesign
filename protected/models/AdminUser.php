<?php

class AdminUser extends CActiveRecord
{

    public $verifyPassword;
    private $_role;

    public function getRole()
    {
        if(empty($this->_role)){
            $this->_role='Guest';
            if(!$this->isNewRecord){
                $_role=Yii::app()->db->createCommand("SELECT * FROM auth_assignment WHERE userid={$this->primaryKey}")->queryRow();
                if(!empty($_role)){
                    $this->_role=$_role['itemname'];
                }
            }
        }
        return $this->_role;
    }

    public function setRole($value)
    {
        $this->_role=$value;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function generate($n=5,$pattern=1)
    {
        $key='';
        $_pattern='123456789';
        if($pattern==2){
            $_pattern='0123456789abcdefghijgklmnopqrstuvwxyz';
        } elseif($pattern==3){
            $_pattern='0123456789abcdef';
        }
        $counter=strlen($_pattern)-1;
        for($i=0; $i<$n; $i++){
            $key.=$_pattern{rand(0,$counter)};
        }
        return $key;
    }

    public function beforeValidate()
    {
        if(empty($this->password)){
            $this->password=$this->generate(6,2);
        }
        if(!empty($this->telephone)){
            $this->telephone=str_replace(array("+","(",")"," ","-","_"),array("","","","","",""),$this->telephone);
        }
        return parent::beforeValidate();
    }

    public function afterSave()
    {
        if(!empty($this->role)){
            Yii::app()->db->createCommand("DELETE FROM auth_assignment WHERE userid='{$this->uid}'")->execute();
            $auth=Yii::app()->authManager;
            $auth->assign($this->role,$this->uid);
        }
        return parent::afterSave();
    }

    public function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return array(
            array('verifyPassword','compare','compareAttribute'=>'password','on'=>'registration','message'=>'Пароли не совпадают.'),
            array('password, telephone, email','required'),
            array('role','required','message'=>'Роль должна быть определена'),
            array('telephone, username','length','max'=>255),
            array('telephone','length','min'=>10,'max'=>10),
            array('telephone','numerical','integerOnly'=>true),
            array('gender','numerical','integerOnly'=>true),
            array('telephone','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('telephone','default','setOnEmpty'=>true,'value'=>null),
            array('email','email','message'=>'Не правильный формат email'),
            array('email','unique','message'=>'Пользователь с таким email уже зарегистрирован'),
            array('telephone','unique','message'=>'Пользователь с таким telephone уже зарегистрирован'),
            array('password','length','max'=>100),
            array('uid, telephone, email, lastvisit, username, gender','safe','on'=>'search'),
        );
    }

    public function behaviors()
    {
        return array(
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
                    'uid',
                    'role',
                    'password',
                    'telephone',
                    'lastvisit',
                    'email',
                    'gender',
                    'username',
                    'update_time',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'clinic_user_s'=>array(self::HAS_MANY,'AdminClinic','uid'),
            'doctor_user_s'=>array(self::HAS_MANY,'AdminDoctor','uid'),
            'record_user_s'=>array(self::HAS_MANY,'AdminRecord','uid'),
            'review_user_s'=>array(self::HAS_MANY,'AdminReview','uid'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'uid'=>'ID',
            'role'=>'Роль',
            'password'=>'Пароль',
            'telephone'=>'Телефон',
            'lastvisit'=>'Последнее посещение',
            'email'=>'E-mail',
            'gender'=>'Пол',
            'username'=>'ФИО',
            'update_time'=>'Время редактирования',
            'create_time'=>'Время добавления',
        );
    }

}
