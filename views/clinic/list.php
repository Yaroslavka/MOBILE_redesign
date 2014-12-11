<?php Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD); ?>
<div class="search_content_section clearfix">
    <div class="left_box">
        <p class="title"><?php echo $this->pageH1; ?></p>
        <div class="infoabout"><?php echo !empty($this->pageTop)?$this->pageTop:''; ?></div>
    </div>
    <div class="right_box"></div>
</div>
<div class="sort_container">
   <div class="sort_top"><a class="sort_title" href="#">Сортировать</a></div>
    <ul class="sort_list clearfix">
        <li class="<?php echo !empty($_GET['sort'])&&($_GET['sort']=='title_asc.asc')?'active ':''; ?>alphabet_up"><?php echo $sort->link('title_asc',null,array('rel'=>'nofollow'));?></li>
        <li class="<?php echo !empty($_GET['sort'])&&($_GET['sort']=='title_desc.desc')?'active ':''; ?>alphabet_down"><?php echo $sort->link('title_desc',null,array('rel'=>'nofollow'));?></li>
        <li class="<?php echo !empty($_GET['sort'])&&($_GET['sort']=='title_asc.asc')?'active ':''; ?>rate_up"><?php echo $sort->link('rating_asc',null,array('rel'=>'nofollow'));?></li>
        <li class="<?php echo !empty($_GET['sort'])&&($_GET['sort']=='title_desc.desc')?'active ':''; ?>rate_down"><?php echo $sort->link('rating_desc',null,array('rel'=>'nofollow'));?></li>
    </ul>
</div>
<div class="clinic_items_wrapper"><?php if(!empty($data)): ?>
    <?php foreach($data as $value): ?>
    <div class="clinic_item">
        <?php $this->renderPartial('//clinic/_single',array('model'=>$value)); ?>
    </div>
    <?php endforeach; ?>
    <?php if(!empty($pages)&&!empty($count)): ?><?php $this->widget('CLinkPager',array('maxButtonCount'=>5,'cssFile'=>false,'htmlOptions'=>array('class'=>'paginator iblock'),'pages'=>$pages,'header'=>'','firstPageLabel'=>'&laquo;','lastPageLabel'=>'&raquo;','nextPageLabel'=>'&#8250;','prevPageLabel'=>'&#8249;')); ?><?php endif; ?>
<?php endif; ?></div>