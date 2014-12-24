<div class="content">
    <div class="clinic_items_wrapper">
        <?php Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD); ?>
        <div class="clinic_item">
            <div class="clinic_wrapper clearfix">
                <div class="top_clinic_single clearfix">
                    <p class="name"><?php echo $model->title;?></p>
                    <div class="column_doctor">
                        <?php if(!empty($model->description)):?><div class="description desc_middle"><?php echo MobiController::cutString($model->description,500);?></div><?php endif;?>
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
                    <div class="doctor_address_map">
                       <div class="close_wrapper_map">
                           <div class="close"></div>
                       </div>
                        <div class="map clearfix">
                            <div id="new_map" style="width:100%;height:1px;" data-lat="<?php echo!empty($model->lat)?$model->lat:'';?>" data-lng="<?php echo!empty($model->lng)?$model->lng:'';?>" ></div>
                            <div id="small_map" style="width:100%;height:273px;" data-lat="<?php echo!empty($model->lat)?$model->lat:'';?>" data-lng="<?php echo!empty($model->lng)?$model->lng:'';?>" id="map-<?php echo $model->id;?>" class="yandex-map-list"></div>
                            <div class="beside_subway_wrapper">
                            <p class="address_doctor"><span><?php echo !empty($model->address)?$model->address:'';?></span></p>
                            <?php $this->renderPartial('//clinic/elements/subway',array('data'=>$model->clinic_subway_s));?>
                        </div>
                        </div>
                    </div>
                </div>
                <?php $this->renderPartial('//clinic/elements/record',array('model'=>$model));?>
                <div class="clinic_tabs clearfix">
                    <span data-content-tab='doctors_tab' class="tab_doc active"></span>
                    <span data-content-tab='info_tab' class="tab_info">О клинике</span>
                    <span data-content-tab='reviews_tab' class="tab_review">Отзывы</span>
                </div>
                <div class="tab_content">
                    <div class="active tab_box doctors_tab">
                        <div class="doctor_items_conrainer">
                            <?php $this->renderPartial('//clinic/elements/doctor'); ?>
                        </div>
                        <div class="similar_clinic_wrapper similar_doctors_wrapper">
                            <?php $this->renderPartial('//clinic/elements/similar',array('model'=>$model)); ?>
                        </div>
                   </div>
                    <div class="tab_box info_tab info_clinic">
                        <?php $this->renderPartial('//clinic/elements/info',array('model'=>$model));?>
                    </div>
                    <div class="tab_box reviews_tab reviews_clinic_wrapper">
                        <?php $this->renderPartial('//clinic/elements/comments',array('model'=>$model)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>