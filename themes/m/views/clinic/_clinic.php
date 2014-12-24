<a class="clinic_snippet_description network_clinic_snippet_description clearfix" href="<?php echo Yii::app()->createUrl('/clinic/single',array('id'=>$model->translit));?>">
    <div class="right">
        <div class="description clearfix">
            <p class="doctor_name doctor_name_network"><?php echo $model->title;?></p>
            <div class="doctor_address_map">
                <div class="beside_subway_wrapper">
                    <p class="address_doctor"><span><?php echo !empty($model->address)?$model->address:'';?></span></p>
                    <?php $this->renderPartial('//clinic/elements/subway',array('data'=>$model->clinic_subway_s));?>
                </div>
                <div class="map">
                    <div style="width:675px;height:273px;" data-lat="<?php echo!empty($model->lat)?$model->lat:'';?>" data-lng="<?php echo!empty($model->lng)?$model->lng:'';?>" id="map-<?php echo $model->id;?>" class="yandex-map-list"></div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->renderPartial('//clinic/elements/rate',array('model'=>$model));?>
</a>