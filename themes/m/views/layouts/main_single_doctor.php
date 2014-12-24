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
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/quo.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/scripts.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/doctor_single.js"></script>
</head>
<body>
<div id="doctor_single" class="page doctor_single">
    <?php $this->renderPartial('//layouts/elements/header'); ?>
    <div class="header_top_section clearfix">
      <div class="menu_wrapper_bottom">
          <div class="search_specialist"><a class="full_link items_service" href="/services"><span>Список услуг</span></a></div>
          <div class="menu_footer"><a href="#nav-panel"></a></div>
      </div>
    </div>
    <div class="content">
        <?php echo $content; ?>
    </div>
</div>
<div class="footer footer_three clearfix">
    <div class='call_btn'><a href='tel:+7 (499) 705-35-35'></a></div>
</div>
<div class="popup_search main">
    <?php $this->renderPartial('//layouts/elements/search'); ?>
</div>
<?php $this->renderPartial('//layouts/elements/menu'); ?>
<?php $this->renderPartial('//layouts/elements/order'); ?>
</body>
</html>