<div class="article_list_wrapper">
<?php if(!empty($data)): ?>
    <ul>
<?php foreach($data as $value): ?>
        <li><?php echo $this->renderPartial('_service',array('model'=>$value)); ?></li>
<?php endforeach; ?>
    </ul>
    <?php if(!empty($pages)&&!empty($count)):?><?php $this->widget('CLinkPager',array('htmlOptions'=>array('class'=>'paginator iblock clearfix'),'pages'=>$pages,'header'=>'','firstPageLabel'=>'&laquo;','lastPageLabel'=>'&raquo;','nextPageLabel'=>'&#8250;','prevPageLabel'=>'&#8249;'));?><?php endif;?>
<?php endif; ?>
</div>