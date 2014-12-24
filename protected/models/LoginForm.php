<?php

class LoginForm extends CFormModel
{

    public $username;
    public $password;
    public $rememberMe;
    private $_identity;

    public function rules()
    {
        return array(
            array('username, password','required'),
            array('rememberMe','boolean'),
            array('password','authenticate'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'rememberMe'=>'Запомнить меня',
            'password'=>'Пароль',
            'username'=>'Логин',
        );
    }

    public function authenticate($attribute,$params)
    {
        $this->_identity=new UserIdentity($this->username,$this->password);
        if(!$this->_identity->authenticate()){
            $this->addError('password','Неверный логин или пароль.');
        }
    }

    public function login()
    {
        if($this->_identity===null){
            $this->_identity=new UserIdentity($this->username,$this->password);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode===UserIdentity::ERROR_NONE){
            $duration=$this->rememberMe?3600*24*30:0;
            Yii::app()->user->login($this->_identity,$duration);
            return true;
        }
        return false;
    }

}
