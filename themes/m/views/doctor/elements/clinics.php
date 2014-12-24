<?php if(!empty($model->doctor_clinic_single)):?>
<?php foreach($model->doctor_clinic_single as $clinic):?>
<a class="doctor_snippet_clinic" href="<?php echo Yii::app()->createUrl('/clinic/single',array('id'=>$clinic->c->translit));?>" data-clinic_name="<?php echo $clinic->c->title; ?>" data-clinic_id="<?php echo $clinic->c->id; ?>">
    <div class="doctor_address_map">
        <p class="clinic_name"><span><?php echo $clinic->c->title; ?></span></p>
        <div class="beside_subway_wrapper">
            <p class="address_doctor"><span><?php echo !empty($clinic->c->address)?$clinic->c->address:'';?></span></p>
            <?php $this->renderPartial('//clinic/elements/subway',array('data'=>$clinic->c->clinic_subway_s));?>
        </div>
    </div>
</a>
<?php endforeach;?>
<?php endif;?>