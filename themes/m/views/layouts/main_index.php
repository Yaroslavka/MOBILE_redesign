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
    <link rel="canonical" href="http://medbooking.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/quo.js"></script>
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/scripts.js"></script>
</head>
<body class="body_main">
<div id="main" class="page main">
    <div class="header">
        <a class="logo" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>"><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/img/logo_mb_m.png" alt=""></a>
    </div>
    <div class="content">
        <div class="top">
            <div class="search_section">
                <?php echo $content; ?>
            </div>
        </div>
        <div class="bottom">
            <?php $this->renderPartial('//layouts/elements/bottom'); ?>
            <a href="http://medbooking.com" class="more_url">Перейти на основную версию сайта</a>
        </div>
    </div>
</div>
<div class="footer footer_two clearfix">
    <div class="bottom">
        <div class="menu_wrapper_bottom">
            <div class="all_specialist"><span><?php echo $this->link; ?></span></div>
            <div class="menu_footer main_menu_footer"><a href="#nav-panel"></a></div>
        </div>
    </div>
</div>
<?php $this->renderPartial('//layouts/elements/category',array('data'=>$this->data)); ?>
<?php $this->renderPartial('//layouts/elements/subway',array('data'=>$this->dataSubway)); ?>
<?php $this->renderPartial('//layouts/elements/menu'); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
 
  ga('create', 'UA-44015389-3', 'auto');
  ga('send', 'pageview');
 
</script>
</body>
</html>