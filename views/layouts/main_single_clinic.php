<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php if(!empty($_GET['page'])): ?><?php if(!empty($this->pageTitle)&&$this->pageTitle!=Yii::app()->name): ?><title><?php echo $this->pageTitle; ?></title><?php else: ?><title><?php echo ("Страница №".$_GET['page']." - ".(isset($this->ctype)?$this->ctype:''))." - ".Yii::app()->name; ?></title><?php endif; ?><?php else: ?><title><?php echo (isset($this->pageTitle)?strip_tags($this->pageTitle):''); ?></title><?php endif; ?>
    <?php if(!empty($this->pageDescription)): ?><meta name="description" content="<?php echo strip_tags($this->pageDescription); ?>" /><?php endif; ?>
    <?php if(!empty($this->pageKeywords)): ?><meta name="keywords" content="<?php echo strip_tags($this->pageKeywords); ?>" /><?php endif; ?>
    <meta name="viewport" content="width=device-width, target-densitydpi=high-dpi, maximum-scale=1.0, minimum-scale=1.0, initial-scale=1">
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/css/style.css">
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/css/media-queries.css">
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/css/animate.css">
    <link rel="canonical" href="http://medbooking.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/css/owl.carousel.css">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/quo.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/owl.carousel.min.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/scripts.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/clinic_single.js"></script>
    <script>
        $(document).ready(function() {
            $("#gallery").owlCarousel({
                items : 3,
                lazyLoad : true
            });
        });
    </script>
</head>
<body>
<div id="clinic_single" class="page clinic_single">
    <?php $this->renderPartial('//layouts/elements/header'); ?>
            <?php echo $content; ?>
</div>
<div class="footer footer_three clearfix">
    <div class='call_btn'><a href='tel:+7 (499) 705-35-35'></a></div>
</div>
<div class="popup_search main">
    <?php $this->renderPartial('//layouts/elements/search'); ?>
</div>
<?php $this->renderPartial('//layouts/elements/specialist',array('data'=>$this->data,'clinic_id'=>$this->clinic_id)); ?>
<?php $this->renderPartial('//layouts/elements/menu'); ?>
<?php $this->renderPartial('//layouts/elements/order'); ?>

</body>
</html>