<?php
/**
 * Widget to manage gallery.
 * Requires Twitter Bootstrap styles to work.
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
class GalleryManager extends CWidget
{
    /** @var Gallery Model of gallery to manage */
    public $gallery;
    /** @var string Route to gallery controller */
    public $controllerRoute = false;
    public $assets;

    public function init()
    {
        $this->assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
    }


    public $htmlOptions = array();


    /** Render widget */
    public function run()
    {
        /** @var $cs CClientScript */
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($this->assets . '/galleryManager.css');

        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('jquery.ui');

        if (YII_DEBUG) {
            if(strstr($_SERVER['REQUEST_URI'], 'site')||(!isset($_GET['r'])))
                    $cs->registerScriptFile($this->assets . '/bootstrap.min.js');
            
            $cs->registerScriptFile($this->assets . '/jquery.iframe-transport.js');
            $cs->registerScriptFile($this->assets . '/jquery.galleryManager.js');
        } else {
            if(strstr($_SERVER['REQUEST_URI'], 'site')||(!isset($_GET['r'])))
                    $cs->registerScriptFile($this->assets . '/bootstrap.min.js');
            
            $cs->registerScriptFile($this->assets . '/jquery.iframe-transport.min.js');
            $cs->registerScriptFile($this->assets . '/jquery.galleryManager.min.js');
        }

        if ($this->controllerRoute === null)
            throw new CException('$controllerRoute must be set.', 500);

        $opts = array(
            'hasName:' => $this->gallery->name ? true : false,
            'hasDesc:' => $this->gallery->description ? true : false,
            'uploadUrl' => Yii::app()->createUrl('/gallery/ajaxUpload', array('gallery_id' => $this->gallery->id)),
            'deleteUrl' => Yii::app()->createUrl('/gallery/delete'),
            'updateUrl' => Yii::app()->createUrl('/gallery/changeData'),
            'arrangeUrl' => Yii::app()->createUrl('/gallery/order'),
            'nameLabel' => Yii::t('app', 'Name'),
            'descriptionLabel' => Yii::t('app', 'Description'),
        );

        if (Yii::app()->request->enableCsrfValidation) {
            $opts['csrfTokenName'] = Yii::app()->request->csrfTokenName;
            $opts['csrfToken'] = Yii::app()->request->csrfToken;
        }
        $opts = CJavaScript::encode($opts);
        $src = "$('#{$this->id}').galleryManager({$opts});";
        $cs->registerScript('galleryManager#' . $this->id, $src);
        $model = new GalleryPhoto();

        $cls = "GalleryEditor ";
        if (!($this->gallery->name)) $cls .= 'no-name';

        if (!($this->gallery->description)) {
            $cls .= (($cls != ' ') ? '-' : '') . 'no-desc';
        }
        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] .= ' ' . $cls;
        else
            $this->htmlOptions['class'] = $cls;
        $this->htmlOptions['id'] = $this->id;

        $this->render('galleryManager', array(
            'model' => $model,
        ));
    }

}
