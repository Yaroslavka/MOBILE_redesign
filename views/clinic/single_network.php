<?php Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD); ?>
<div class="clinic_items_wrapper">
    <div class="clinic_item">
        <div class="clinic_wrapper clearfix">
            <div class="top_clinic_single clearfix">
                <p class="name"><?php echo $model->title;?></p>
                <div class="left">
                    <?php $this->renderPartial('//clinic/elements/photo',array('model'=>$model));?>
                </div>
                <div class="right">
                    <div class="column_doctor">
                        <?php if(!empty($model->description)):?><div class="description">
                            <?php echo MobiController::cutString($model->description, 200);?>
                        </div><?php endif;?>
                    </div>
                    <?php $this->renderPartial('//clinic/elements/rate',array('model'=>$model));?>
                </div>
                <?php $this->renderPartial('//clinic/elements/record',array('model'=>$model));?>
            </div>
            <div class="shedule_container">
                <div class="day_row clearfix">
                    <?php if(!empty($model->regime_byd)||!empty($model->regime_mon)||!empty($model->regime_tue)||!empty($model->regime_wed)||!empty($model->regime_thu)||!empty($model->regime_fri)||!empty($model->regime_sat)||!empty($model->regime_sun)):?>
                    <?php if(!empty($model->regime_byd)):?><div class="day day_all"><?php echo $model->regime_byd;?></div><?php else:?>
                    <?php if(!empty($model->regime_mon)):?><div class="day day_Monday"><?php echo $model->regime_mon;?></div><?php endif;?>
                    <?php if(!empty($model->regime_tue)):?><div class="day day_Tuesday"><?php echo $model->regime_tue;?></div><?php endif;?>
                    <?php if(!empty($model->regime_wed)):?><div class="day day_Wednesday"><?php echo $model->regime_wed;?></div><?php endif;?>
                    <?php if(!empty($model->regime_thu)):?><div class="day day_Thursday"><?php echo $model->regime_thu;?></div><?php endif;?>
                    <?php if(!empty($model->regime_fri)):?><div class="day day_Friday"><?php echo $model->regime_fri;?></div><?php endif;?>
                    <?php endif;?>
                    <?php if(!empty($model->regime_sat)):?><div class="day day_Saturday"><?php echo $model->regime_sat;?></div><?php endif;?>
                    <?php if(!empty($model->regime_sun)):?><div class="day day_Sunday"><?php echo $model->regime_sun;?></div><?php endif;?>
                    <?php endif;?>
                </div>
                <div class="time_row clearfix">
                    <?php if(!empty($model->regime_byd)||!empty($model->regime_mon)||!empty($model->regime_tue)||!empty($model->regime_wed)||!empty($model->regime_thu)||!empty($model->regime_fri)||!empty($model->regime_sat)||!empty($model->regime_sun)):?>
                    <?php if(!empty($model->regime_byd)):?><div class="time_weekdays_all"><?php echo $model->regime_byd;?></div><?php else:?>
                    <?php if(!empty($model->regime_mon)):?><div class="time_days time_Monday"><?php echo $model->regime_mon;?></div><?php endif;?>
                    <?php if(!empty($model->regime_tue)):?><div class="time_days time_Tuesday"><?php echo $model->regime_tue;?></div><?php endif;?>
                    <?php if(!empty($model->regime_wed)):?><div class="time_days time_Wednesday"><?php echo $model->regime_wed;?></div><?php endif;?>
                    <?php if(!empty($model->regime_thu)):?><div class="time_days time_Thursday"><?php echo $model->regime_thu;?></div><?php endif;?>
                    <?php if(!empty($model->regime_fri)):?><div class="time_days time_Friday"><?php echo $model->regime_fri;?></div><?php endif;?>
                    <?php endif;?>
                    <?php if(!empty($model->regime_sat)):?><div class="weekend time_Saturday"><?php echo $model->regime_sat;?></div><?php endif;?>
                    <?php if(!empty($model->regime_sun)):?><div class="weekend time_Sunday"><?php echo $model->regime_sun;?></div><?php endif;?>
                    <?php endif;?>
                </div>
            </div>
            <div class="network_accordion_wrapper">
                <?php $this->renderPartial('//clinic/elements/clinics_single',array('model'=>$model)); ?>
            </div>
            <div class="info_clinic">
                <?php $this->renderPartial('//clinic/elements/info',array('model'=>$model));?>
            </div>
        </div>
    </div>
</div>