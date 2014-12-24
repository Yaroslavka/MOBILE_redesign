<div class="menu_specialist_wrapper">
<?php if(!empty($data)): ?>
    <form action="/">
        <div class="text_wrapper">
            <input type="text" placeholder="Поиск">
        </div>
        <ul class="items-modal-list">
<?php foreach($data as $value): ?>
            <li><a class="<?php echo $value['active']?"active":'';?>" href="<?php echo Yii::app()->createUrl('/site/illnessSingle',array('id'=>$value['translit']));?>"><?php echo $value['title'];?></a></li>
<?php endforeach; ?>
        </ul>
        <div class="submit_wrapper">
            <input type="submit" value="">
        </div>
    </form>
<?php endif; ?>
</div>