<div class="cell clinic-name"><a href="<?php echo Yii::app()->createUrl('/clinic/single',array('id'=>$model->translit));?>"><?php echo $model->title;?></a></div>
<div class="cell clinic-rate"><?php $this->renderPartial('//clinic/elements/rate_small',array('model'=>$model));?></div>
<div class="cell clinic-price"><p><span class="count"><?php echo $this->servicePrice($category_id,$model->id); ?></span></p></div>
<div class="special-wrapper">
    <div class="similar_doctors_wrapper">
        <div class="similar_doctors_items"><?php if(!empty($model->clinic_doctor_s)): ?>
            <?php foreach($model->clinic_doctor_s as $value): ?>
            <div class="similar_doctors_item clearfix">
                <div data-img="<?php $this->renderPartial('//doctor/elements/_photo',array('model'=>$value->d));?>" class="left"><a href="/doctor/<?php echo $value->d->translit;?>"></a></div>
                <div class="right">
                    <div class="description clearfix">
                        <p class="doctor_name"><a href="/doctor/<?php echo $value->d->translit;?>"><?php echo!empty($value->d->lname)?$value->d->lname:'';?> <?php echo!empty($value->d->fname)?$value->d->fname:'';?> <?php echo!empty($value->d->sname)?$value->d->sname:'';?></a></p>
                        <p class="doctor_category"><?php echo!empty($value->d->speciality)?$value->d->speciality:'';?></p>
                    </div>
                    <?php $this->renderPartial('//doctor/elements/rate_small',array('model'=>$value->d));?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?></div>
    </div>
</div>
