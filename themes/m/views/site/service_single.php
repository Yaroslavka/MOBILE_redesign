<ul class="sort_table_items">
    <li><?php echo $sort->link('title',null,array('rel'=>'nofollow'));?></li>
    <li><?php echo $sort->link('rating',null,array('rel'=>'nofollow'));?></li>
    <li><?php echo $sort->link('price',null,array('rel'=>'nofollow'));?></li>
</ul>
<div class="article">
    <strong class="title_services"><?php echo!empty($model->title)?$model->title:'';?></strong>
    <?php echo!empty($model->body)?$model->body:'';?>
</div>
<?php if(!empty($data)&&!empty($sort)): ?><div class="service-clinic-table">
   <div class="sort_table_wrapper"><a href="#"><span>Сортировать</span></a></div>
    <div class="row  title_row clearfix">
        <div class="cell clinic-name"><span class="sort asc"><span class="sort_table_title">Заголовок</span></span></div>
        <div class="cell clinic-rate"><span class="sort desc"></span></div>
        <div class="cell clinic-price"><span><span class="sort_table_title_price">Цена</span></span></div>
    </div>
    <div class="sort-clinic">
        <?php foreach($data as $value): ?>
	<?php if(!empty($model->category_id)): ?><div class="row clearfix">
            <?php $this->renderPartial('//clinic/_service',array('model'=>$value,'category_id'=>$model->category_id)); ?>
        </div><?php endif; ?>
        <?php endforeach; ?>
    </div>
</div><?php endif; ?>