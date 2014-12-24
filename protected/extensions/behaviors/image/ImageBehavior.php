<?php

class ImageBehavior extends CActiveRecordBehavior
{
        public $file_name='file_image';
        public $name='image';
        public $file_size=3;
        
        public $file_upload='files';
        public $root_upload='webroot';
        
        private $_file_image;
        
        public function getFile_image()
        {
                return $this->_file_image;
        }
        
        public function afterSave($event)
        {
                if(!empty($_FILES))
                {
                        // сохраняем файл
                        $fileimage=CUploadedFile::getInstance($this->getOwner(),$this->file_name);
                        if(!empty($fileimage))
                        {
                                $filename=$this->getOwner()->primaryKey.'.'.$fileimage->getExtensionName();

                                $folder=YiiBase::getPathOfAlias($this->root_upload.'.'.$this->file_upload.'.'.$this->getOwner()->tableName());
                                if(!is_dir($folder))
                                        mkdir($folder, 0755, true);

                                $folder=YiiBase::getPathOfAlias($this->root_upload.'.'.$this->file_upload.'.'.$this->getOwner()->tableName().'.'.$this->name);
                                if(!is_dir($folder))
                                        mkdir($folder, 0755, true);

                                if($fileimage->saveAs($this->file_upload.DIRECTORY_SEPARATOR.$this->getOwner()->tableName().DIRECTORY_SEPARATOR.$this->name.DIRECTORY_SEPARATOR.$filename))
                                        $this->getOwner()->updateByPk($this->getOwner()->primaryKey,array($this->name=>$this->file_upload.DIRECTORY_SEPARATOR.$this->getOwner()->tableName().DIRECTORY_SEPARATOR.$this->name.DIRECTORY_SEPARATOR.$filename));
                        }
                }   
                
                parent::afterSave($event);
        }
        
        public function attach($owner)
        {
                parent::attach($owner);

                $validators = $this->getOwner()->getValidatorList();
                
                $params = array(
                                'types'=>'jpg,jpeg,png,gif',
                                'allowEmpty'=>true,
                                'wrongType'=>Yii::t('app','Допустимы только файлы следующих форматов: {extensions}.'),
                                'maxSize'=>1024*1024*($this->file_size),
                                'tooLarge'=>Yii::t('app','Указан файл объемом более '.$this->file_size.'Мб. Пожалуйста, укажите файл меньшего размера.'),
                                'maxFiles'=>$this->file_size,
                        );                
                
                $validator = CValidator::createValidator('file', $this->getOwner(), $this->file_name, $params);
                
                $validators->add($validator);
        }
        
        public function beforeDelete($event)
        {
                if (file_exists($this->getOwner()->{$this->name}))
                        @unlink($this->getOwner()->{$this->name});                    
                
                parent::beforeDelete($event);
        }
}
