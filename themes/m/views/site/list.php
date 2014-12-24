<div class="article_list_wrapper">
<?php if(!empty($data)): ?>
    <ul class="blogs_items_wrapper">
<?php foreach($data as $value): ?>
        <li><?php echo $this->renderPartial('_blog',array('model'=>$value)); ?></li>
<?php endforeach; ?>
    </ul>
    <?php if(!empty($pages)&&!empty($count)):?><?php $this->widget('CLinkPager',array('maxButtonCount'=>5,'cssFile'=>false,'htmlOptions'=>array('class'=>'paginator iblock clearfix'),'pages'=>$pages,'header'=>'','firstPageLabel'=>'&laquo;','lastPageLabel'=>'&raquo;','nextPageLabel'=>'&#8250;','prevPageLabel'=>'&#8249;'));?><?php endif;?>
<?php endif; ?>
</div>