<a class="clinic_snippet_description" href="<?php echo Yii::app()->createUrl('/clinic/single',array('id'=>$model->translit));?>">
    <p class="name"><?php echo $model->title;?></p>
    <div class="clinic_wrapper clearfix">
        <div class="right">
            <div class="column_doctor">
                <div class="doctor_address_map">
                    <div class="beside_subway_wrapper">
                        <p class="address_doctor"><span><?php echo !empty($model->address)?$model->address:'';?></span></p>
                        <?php $this->renderPartial('//clinic/elements/subway',array('data'=>$model->clinic_subway_s));?>
                    </div>
                </div>
            </div>
            <?php $this->renderPartial('//clinic/elements/rate',array('model'=>$model));?>
            <div class="shedule_container">
                <div class="time_row clearfix">
                    <?php if(!empty($model->regime_byd)): ?>
                    <div class="time_weekdays_all">Пн - Пт: <?php $this->renderPartial('//clinic/elements/_time_w',array('string'=>$model->regime_byd));?></div>
                    <?php else: ?>
                    <div class="time_days time_Monday">Пн: <?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_mon));?></div>
                    <div class="time_days time_Tuesday">Вт: <?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_tue));?></div>
                    <div class="time_days time_Wednesday">Ср: <?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_wed));?></div>
                    <div class="time_days time_Thursday">Чт: <?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_thu));?></div>
                    <div class="time_days time_Friday">Пт: <?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_fri));?></div>
                    <?php endif; ?>
                    <div class="weekend time_Saturday">Сб: <?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_sat));?></div>
                    <div class="weekend time_Sunday">Вс: <?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_sun));?></div>
                </div>
            </div>
        </div>
    </div>
</a>