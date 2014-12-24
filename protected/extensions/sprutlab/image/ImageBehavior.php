<?php

class ImageBehavior extends CActiveRecordBehavior
{

    public $file_name='file_image';
    public $name='image';
    public $file_size=3;
    public $versions=array();
    public $file_upload='files';
    public $root_upload='webroot';
    private $_file_image;

    public function getFile_image()
    {
        return $this->_file_image;
    }

    public function afterSave($event)
    {
        if(!empty($_FILES)){
            // сохраняем файл
            $fileimage=CUploadedFile::getInstance($this->getOwner(),$this->file_name);
            if(!empty($fileimage)){
                if(!empty($this->getOwner()->translit)){
                    $filename=$this->getOwner()->translit.'.'.$fileimage->getExtensionName();
                } else{
                    $filename=md5($this->getOwner()->primaryKey.time()).'.'.$fileimage->getExtensionName();
                }
                $folder=YiiBase::getPathOfAlias($this->root_upload.'.'.$this->file_upload.'.'.$this->getOwner()->tableName());
                if(!is_dir($folder)){
                    mkdir($folder,0755,true);
                }
                if(!empty($this->versions)){
                    foreach($this->versions as $key=> $value){
                        $folder=YiiBase::getPathOfAlias($this->root_upload.'.'.$this->file_upload.'.'.$this->getOwner()->tableName().'.'.$key);
                        if(!is_dir($folder)){
                            mkdir($folder,0755,true);
                        }
                    }
                }
                $path=$this->file_upload.DIRECTORY_SEPARATOR.$this->getOwner()->tableName();
                if($fileimage->saveAs($path.DIRECTORY_SEPARATOR.$filename)){
                    $this->getOwner()->updateByPk($this->getOwner()->primaryKey,array($this->name=>$filename));
                    if(!empty($this->versions)){
                        $_image=Yii::app()->image->load($path.DIRECTORY_SEPARATOR.$filename);
                        foreach($this->versions as $key=> $value){
                            $_image->cresize($value['centeredpreview'][0],$value['centeredpreview'][1], Image::WIDTH);
                            $_image->save($path.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$filename);
                        }
                    }
                }
            }
        }
        return parent::afterSave($event);
    }
    
    public function uploadServerFile()
    {
        if(!empty($this->getOwner()->image)){
            $exttype=image_type_to_extension($this->getOwner()->image,false);
            if(!empty($this->getOwner()->translit)){
                $filename=$this->getOwner()->translit.'.'.$exttype;
            } else{
                $filename=md5($this->getOwner()->primaryKey.time()).'.'.$exttype;
            }
            $folder=YiiBase::getPathOfAlias($this->root_upload.'.'.$this->file_upload.'.'.$exttype);
            if(!is_dir($folder)){
                mkdir($folder,0755,true);
            }
            if(!empty($this->versions)){
                foreach($this->versions as $key=> $value){
                    $folder=YiiBase::getPathOfAlias($this->root_upload.'.'.$this->file_upload.'.'.$exttype.'.'.$key);
                    if(!is_dir($folder)){
                        mkdir($folder,0755,true);
                    }
                }
            }
            $path=$this->file_upload.DIRECTORY_SEPARATOR.$this->getOwner()->tableName();
            //file_put_contents($path, file_get_contents($link));
            if(copy($this->getOwner()->image, $path.DIRECTORY_SEPARATOR.$filename)){
                $this->getOwner()->updateByPk($this->getOwner()->primaryKey,array($this->name=>$filename));
                if(!empty($this->versions)){
                    $_image=Yii::app()->image->load($path.DIRECTORY_SEPARATOR.$filename);
                    foreach($this->versions as $key=> $value){
                        $_image->cresize($value['centeredpreview'][0],$value['centeredpreview'][1], Image::WIDTH);
                        $_image->save($path.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$filename);
                    }
                }
                return 1;
            }
        }
        return 0;
    }

    public $typemode;

    public function newSaver()
    {
        if(!empty($this->getOwner()->image)){
            if(file_exists(Yii::getPathOfAlias('webroot').'/'.$this->getOwner()->image)){
                $fileimage=Yii::app()->image->load($this->getOwner()->image);
                if($fileimage->ext){
                    $filename=$this->getOwner()->translit.'.'.$fileimage->ext;
                    $folder=YiiBase::getPathOfAlias($this->root_upload.'.'.$this->file_upload.'.'.$this->getOwner()->tableName());
                    if(!is_dir($folder)){
                        mkdir($folder,0755,true);
                    }
                    if(!empty($this->versions)){
                        foreach($this->versions as $key=> $value){
                            $folder=YiiBase::getPathOfAlias($this->root_upload.'.'.$this->file_upload.'.'.$this->getOwner()->tableName().'.'.$key);
                            if(!is_dir($folder)){
                                mkdir($folder,0755,true);
                            }
                        }
                    }
                    $path=$this->file_upload.DIRECTORY_SEPARATOR.$this->getOwner()->tableName();
                    if($fileimage->save($path.DIRECTORY_SEPARATOR.$filename)){
                        $this->getOwner()->updateByPk($this->getOwner()->primaryKey,array($this->name=>$filename));
                        if(!empty($this->versions)){
                            $_image=Yii::app()->image->load($path.DIRECTORY_SEPARATOR.$filename);
                            foreach($this->versions as $key=> $value){
                                $_image->cresize($value['centeredpreview'][0],$value['centeredpreview'][1],Image::WIDTH);
                                $_image->save($path.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$filename);
                            }
                        }
                    }
                    unlink(Yii::getPathOfAlias('webroot').'/'.$this->getOwner()->image);
                    return true;
                }
            }
        }
        return false;
    }

    public function attach($owner)
    {
        parent::attach($owner);
        $validators=$this->getOwner()->getValidatorList();
        $params=array(
            'types'=>'jpg,jpeg,png,gif',
            'allowEmpty'=>true,
            'wrongType'=>Yii::t('app','Допустимы только файлы следующих форматов: {extensions}.'),
            'maxSize'=>1024*1024*($this->file_size),
            'tooLarge'=>Yii::t('app','Указан файл объемом более '.$this->file_size.'Мб. Пожалуйста, укажите файл меньшего размера.'),
            'maxFiles'=>$this->file_size,
        );
        $validator=CValidator::createValidator('file',$this->getOwner(),$this->file_name,$params);
        $validators->add($validator);
    }

    public function getImagePath($key)
    {
        $path='';
        if(!empty($this->getOwner()->image)){
            $path=$this->file_upload.DIRECTORY_SEPARATOR.$this->getOwner()->tableName().DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$this->getOwner()->image;
        }
        return $path;
    }
    
    public function getImageChildPath($key)
    {
        $path='';
        if(!empty($this->getOwner()->image)){
            $path=$this->file_upload.DIRECTORY_SEPARATOR.'doctor'.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$this->getOwner()->image;
        }
        return $path;
    }

    public function getImageReal()
    {
        $path='';
        if(!empty($this->getOwner()->image)){
            $path=$this->file_upload.DIRECTORY_SEPARATOR.$this->getOwner()->tableName().DIRECTORY_SEPARATOR.$this->getOwner()->image;
        }
        return $path;
    }

    public function beforeDelete($event)
    {
        if(!empty($this->versions)){
            $path=$this->file_upload.DIRECTORY_SEPARATOR.$this->getOwner()->tableName();
            if(file_exists($path.DIRECTORY_SEPARATOR.$this->getOwner()->{$this->name})){
                @unlink($path.DIRECTORY_SEPARATOR.$this->getOwner()->{$this->name});
            }
            foreach($this->versions as $key=> $value){
                if(file_exists($path.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$this->getOwner()->{$this->name})){
                    @unlink($path.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$this->getOwner()->{$this->name});
                }
            }
        }
        return parent::beforeDelete($event);
    }

}
