<div class="fixed_category_list menu_search_specialist_wrapper menu_specialist_wrapper">
<?php if(!empty($data)): ?>
    <ul class="items-modal-list">
<?php foreach($data as $value): ?>
        <li class="clearfix"><a class="<?php echo $value['active']?"active":'';?>" href="<?php echo Yii::app()->createUrl('/site/serviceList',array('theme'=>$value['translit']));?>"><?php echo $value['title'];?></a><div class="count"><?php echo $value['count'];?></div></li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>
</div>