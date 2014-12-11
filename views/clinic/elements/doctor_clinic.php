<div class="count-doctor"><?php echo $count; ?></div>
<?php if(!empty($data)): ?>
<?php foreach($data as $value): ?>
<div class="doctor_item">
    <?php $this->renderPartial('//clinic/_doctor',array('model'=>$value)); ?>
</div>
<?php endforeach; ?>
<?php endif; ?>