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
    <script src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/js/scripts.js"></script>
</head>
<body>
<div id="page_404" class="page page_404">
    <div class="header">
        <a class="logo" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
            <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/themes/m/img/logo.png" alt="">
        </a>
    </div>
    <div class="content">
        <div class="top">
            <?php echo $content; ?>
        </div>
        <div class="bottom">
            <?php $this->renderPartial('//layouts/elements/bottom'); ?>
        </div>
    </div>
    <div class="footer">
      <div class="top">
          <p>© Сopyright 2013 Medbooking.com. <br> Все права защищены.</p>
      </div>
    </div>
</div>
</body>
</html>