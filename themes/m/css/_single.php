<p class="name"><a href="<?php echo Yii::app()->createUrl('/clinic/single',array('id'=>$model->translit));?>"><?php echo $model->title;?></a></p>
<div class="clinic_wrapper clearfix">
    <div class="right">
        <div class="column_doctor">
            <div class="doctor_address_map">
                <p class="address_doctor">
                    <?php echo CHtml::link((!empty($model->address)?$model->address:''),array('/clinic/single','id'=>$model->translit));?>
                </p>
                <div class="beside_subway_wrapper">
                    <?php $this->renderPartial('//clinic/elements/subway',array('data'=>$model->clinic_subway_s));?>
                </div>
                <div class="map">
                    <div style="width:675px;height:273px;" data-lat="<?php echo!empty($model->lat)?$model->lat:'';?>" data-lng="<?php echo!empty($model->lng)?$model->lng:'';?>" id="map-<?php echo $model->id;?>" class="yandex-map-list"></div>
                </div>
            </div>
            <?php if(!empty($model->description)):?>
            <div class="description">
                <?php echo MobiController::cutString($model->description,495);?>
            </div>
            <?php endif;?>
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
                <div class="time_weekdays_all"><?php $this->renderPartial('//clinic/elements/_time',array('string'=>$model->regime_byd));?></div>
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
        <?php $this->renderPartial('//clinic/elements/record',array('model'=>$model));?>
    </div>
</div>
