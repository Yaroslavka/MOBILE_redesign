<div class="title_box">Врачи <span class="count"><?php echo count($this->doctors); ?></span> <a href="#" class="select_specialist">Специализация</a></div>
<div class="doctor_items_wrapper doctor_items_wrapper_menu"><?php if(!empty($this->doctors)): ?>
    <?php foreach($this->doctors as $value): ?>
    <div class="doctor_item">
        <?php $this->renderPartial('//clinic/_doctor',array('model'=>$value)); ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?></div>