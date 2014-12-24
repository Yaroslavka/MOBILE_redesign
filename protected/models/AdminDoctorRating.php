<?php

class AdminDoctorRating extends CActiveRecord
{

    const START_RATE1=1;
    const START_RATE2=1;
    const START_RATE3=0;
    const START_RATE4=2.5;
    const START_COMM=0;
    const START_MODD=100;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function beforeValidate()
    {
        $this->rate1=empty($this->rate1)?self::START_RATE1:2*($this->rate1);
        if(empty($this->rate2)){
            $this->rate2=self::START_RATE2;
        } else{
            $diff=date('Y')-$this->rate2;
            if($diff<10){
                $this->rate2=$diff;
            } else{
                $this->rate2=9.5;
            }
        }
        $this->rate3=empty($this->rate3)?self::START_RATE3:$this->rate3;
        $this->rate4=empty($this->rate4)?self::START_RATE4:$this->rate4;
        $this->comm=empty($this->rate4)?self::START_COMM:$this->comm;
        $this->modificator=empty($this->modificator)?self::START_MODD:$this->modificator;
        $this->rate1=str_replace(",",".",round($this->rate1,2));
        $this->rate2=str_replace(",",".",round($this->rate2,2));
        $this->rate3=str_replace(",",".",round($this->rate3,2));
        $this->rate4=str_replace(",",".",round($this->rate4,2));
        $this->rate=0;
        $this->rate10=0;
        $this->rate=$this->rate1+$this->rate2+$this->rate3+$this->rate4;
        if(!empty($this->comm)&&$this->comm*3>=9){
            $this->rate=$this->rate+9.9;
        } else{
            $this->rate=$this->rate+($this->comm*3);
        }
        $this->rate=$this->rate/4;
        if(!empty($this->modificator)){
            if($this->modificator>0){
                $this->rate10=$this->rate*$this->modificator/100;
            } else{
                $this->rate10=$this->rate(1-$this->modificator/100);
            }
        } else{
            $this->rate10=$this->rate*0.9;
        }
        if($this->rate10>10){
            $this->rate10=10;
        }
        $this->rate=str_replace(",",".",round($this->rate,2));
        $this->rate10=str_replace(",",".",round($this->rate10,2));
        return parent::beforeValidate();
    }

    public function tableName()
    {
        return 'doctor_rating2';
    }

    public function rules()
    {
        return array(
            array('nid','required'),
            array('rate1, rate2, rate3, rate4, rate, rate10','default','setOnEmpty'=>true,'value'=>null),
            array('rate1, rate2, rate3, rate4, rate, rate10','numerical'),
            array('comm, modificator','numerical','integerOnly'=>true),
            array('nid','exist','attributeName'=>'id','className'=>'AdminDoctor'),
            array('reviews, services, allRecord, dayRecord','safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'modificator'=>'Модификатор',
            'rate1'=>'Рейтинг (степень)',
            'rate2'=>'Рейтинг (стаж)',
            'rate3'=>'Рейтинг (API)',
            'rate4'=>'Рейтинг (отзывы)',
            'rate10'=>'Рейтинг (отзывы)',
            'rate'=>'Рейтинг (взвешенный)',
            'comm'=>'Количество отзывов',
            'reviews'=>'Количество положительных отзывов',
            'services'=>'Количество услуг',
            'allRecord'=>'Количество записавшихся всего',
            'dayRecord'=>'Количество записавшихся за день',
        );
    }

    public function relations()
    {
        return array(
            'n'=>array(self::BELONGS_TO,'AdminDoctor','nid'),
        );
    }

}
