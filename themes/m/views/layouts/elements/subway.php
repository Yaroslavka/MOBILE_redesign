<div class="menu_subway_wrapper">
<?php if(!empty($data)): ?>
    <form action="/">
        <div class="text_wrapper">
            <input type="text" placeholder="Поиск">
        </div>
        <ul class="items-modal-list">
<?php foreach($data as $value): ?>
<?php if(Yii::app()->controller->id=='clinic'): ?><?php if(!empty($this->category)): ?>
            <li><a class="<?php echo $value['active']?"active":'';?>" href="<?php echo Yii::app()->createUrl('/clinic/index',array('category'=>$this->category,'subway'=>$value['translit']));?>"><?php echo $value['title'];?></a></li>
<?php else: ?>
            <li><a class="<?php echo $value['active']?"active":'';?>" href="<?php echo Yii::app()->createUrl('/clinic/index',array('subway'=>$value['translit']));?>"><?php echo $value['title'];?></a></li>
<?php endif; ?><?php else: ?><?php if(!empty($this->subway)): ?>
            <li><a class="<?php echo $value['active']?"active":'';?>" href="<?php echo Yii::app()->createUrl('/doctor/index',array('category'=>$this->category,'subway'=>$value['translit']));?>"><?php echo $value['title'];?></a></li>
<?php else: ?>
            <li><a class="<?php echo $value['active']?"active":'';?>" href="<?php echo Yii::app()->createUrl('/doctor/index',array('subway'=>$value['translit']));?>"><?php echo $value['title'];?></a></li>
<?php endif; ?><?php endif; ?>
<?php endforeach; ?>
        </ul>
        <div class="submit_wrapper">
            <input type="submit" value="">
        </div>
    </form>
<?php endif; ?>
</div>