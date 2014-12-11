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
                <div class="day_row clearfix">
                    <div class="day day_Monday">Пн</div>
                    <div class="day day_Tuesday">Вт</div>
                    <div class="day day_Wednesday">Ср</div>
                    <div class="day day_Thursday">Чт</div>
                    <div class="day day_Friday">Пт</div>
                    <div class="day day_Saturday">Сб</div>
                    <div class="day day_Sunday">Вс</div>
                </div>
                <div class="time_row clearfix">
                    <?php if(!empty($model->regime_byd)): ?>
                    <div class="time_weekdays_all"><?php $this->renderPartial('//clinic/elements/_time_w',array('string'=>$model->regime_byd));?></div>
                    <?php else: ?>
                    <div class="time_days time_Monday"><?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_mon));?></div>
                    <div class="time_days time_Tuesday"><?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_tue));?></div>
                    <div class="time_days time_Wednesday"><?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_wed));?></div>
                    <div class="time_days time_Thursday"><?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_thu));?></div>
                    <div class="time_days time_Friday"><?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_fri));?></div>
                    <?php endif; ?>
                    <div class="weekend time_Saturday"><?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_sat));?></div>
                    <div class="weekend time_Sunday"><?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_sun));?></div>
                </div>
            </div>
        </div>
    </div>
</a>