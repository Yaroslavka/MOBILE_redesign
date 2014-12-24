<?php if(!empty($model->clinic_network_s)): ?><div class="network_accordion_wrapper">
        <p><span class="coint"><?php echo count($model->clinic_network_s); ?></span> <?php echo Yii::t('app',"клиника|клиники|клиник",array(count($model->clinic_network_s)));?> в сети</p>
</div><?php endif; ?>