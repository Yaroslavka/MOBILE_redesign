<?php if(!empty($model->clinic_network_s)): ?><div class="network_panel">
    <p><span class="coint"><?php echo count($model->clinic_network_s); ?></span> <?php echo Yii::t('app',"клиника|клиники|клиник",array(count($model->clinic_network_s)));?> в сети</p>
</div>
<div class="network_list_wrapper">
    <div class="similar_clinic_wrapper similar_doctors_wrapper">
        <?php foreach($model->clinic_network_s as $value): ?>
        <div class="similar_doctors_items">
            <div class="similar_doctors_item clearfix">
                <?php $this->renderPartial('//clinic/_clinic',array('model'=>$value));?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div><?php endif; ?>