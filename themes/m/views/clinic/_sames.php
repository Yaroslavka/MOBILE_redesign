<a class="sames_clinic_wrapper" href="<?php echo Yii::app()->createUrl('/clinic/single',array('id'=>$model->translit));?>">
    <div class="right">
        <div class="description clearfix">
            <p class="doctor_name"><?php echo $model->title;?></p>
            <div class="doctor_address_map">
                <div class="beside_subway_wrapper">
                    <p class="address_doctor"><span><?php echo !empty($model->address)?$model->address:'';?></span></p>
                    <?php $this->renderPartial('//clinic/elements/subway',array('data'=>$model->clinic_subway_s));?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->renderPartial('//clinic/elements/rate',array('model'=>$model));?>
</a>