<?php
/**
 * Behavior for adding gallery to any model including case of newRecord model without gallery_id attribute
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 * @author Kyrylo Kravchenko <retuam@gmail.com>
 */
class GalleryBehavior extends CActiveRecordBehavior
{
        /** @var string Model attribute name to store created gallery id */
        public $idAttribute;
        /**
         * @var array Settings for image auto-generation
         * @example
         *  array(
         *       'small' => array(
         *              'resize' => array(200, null),
         *       ),
         *      'medium' => array(
         *              'resize' => array(800, null),
         *      )
         *  );
         */
        public $versions;
        /** @var boolean does images in gallery need names */
        public $name;
        /** @var boolean does images in gallery need class names */
        public $nameClass;
        /** @var boolean does images in gallery need descriptions */
        public $description;
        private $_gallery;

        /** 
         * Will remove associated Gallery before object removal 
         */
        public function beforeDelete($event)
        {
                if(!empty($this->getOwner()->{$this->idAttribute}))
                {
                        $gallery = Gallery::model()->findByPk($this->getOwner()->{$this->idAttribute});
			if(!empty($gallery->primaryKey))
	                        $gallery->delete();
                }
                
                parent::beforeDelete($event);
        }
        
        /** @return Gallery Returns gallery associated with model */
        public function getGallery()
        {
                if(empty($this->getOwner()->{$this->idAttribute}))
                {
                        $gallery = new Gallery();
                        $gallery->name = $this->name;
                        $gallery->description = $this->description;
                        $gallery->versions = $this->versions;
                        $gallery->save();

                        $this->getOwner()->{$this->idAttribute} = $gallery->primaryKey;
                }
                
                if(empty($this->_gallery)) 
                        if(!empty($this->getOwner()->{$this->idAttribute}))
                                $this->_gallery = Gallery::model()->findByPk($this->getOwner()->{$this->idAttribute});
                
                return $this->_gallery;
        }
        
        public function attach($owner)
        {
                parent::attach($owner);

                $validators = $this->getOwner()->getValidatorList();
                
                $validator = CValidator::createValidator('numerical', $this->getOwner(), $this->idAttribute, array('integerOnly'=>true));
                
                $validators->add($validator);
        }
        
        /** Will create new gallery after save if no associated gallery exists */
        public function beforeSave($event)
        {
                parent::beforeSave($event);
                
                if(empty($this->getOwner()->{$this->idAttribute})) 
                {
                        $gallery = new Gallery();
                        $gallery->name = $this->name;
                        $gallery->description = $this->description;
                        $gallery->versions = $this->versions;
                        $gallery->save();

                        $this->getOwner()->{$this->idAttribute} = $gallery->primaryKey;
                }
        }
        
        /** @return GalleryPhoto[] Photos from associated gallery */
        public function getGalleryPhotos($limit=10)
        {
                $criteria = new CDbCriteria();
                $criteria->condition = 'gallery_id = :gallery_id';
                $criteria->params[':gallery_id'] = $this->getOwner()->{$this->idAttribute};
                $criteria->order = 'rank ASC';
                $criteria->limit = $limit;
                
                return GalleryPhoto::model()->findAll($criteria);
        }        

        /** Method for changing gallery configuration and regeneration of images versions */
        public function changeConfig()
        {
                /** @var $gallery Gallery */    
                $gallery = Gallery::model()->findByPk($this->getOwner()->{$this->idAttribute});
                if($gallery == null) return;
            
                if(!empty($gallery->galleryPhotos))
                        foreach($gallery->galleryPhotos as $photo) 
                                $photo->removeImages();

                $gallery->name = $this->name;
                $gallery->description = $this->description;
                $gallery->versions = $this->versions;
                $gallery->save();

                if(!empty($gallery->galleryPhotos))
                        foreach($gallery->galleryPhotos as $photo)
                                $photo->updateImages();

                $this->getOwner()->{$this->idAttribute} = $gallery->id;
                $this->getOwner()->saveAttributes($this->getOwner()->getAttributes());
        }
}
