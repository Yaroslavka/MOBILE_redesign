<a class="clinic_snippet_description" href="<?php echo Yii::app()->createUrl('/clinic/singleNetwork',array('id'=>$model->translit));?>">
<p class="name"><?php echo $model->title;?></p>
<div class="clinic_wrapper clearfix">
    <div class="left">
        <div class="inner">
           <?php $this->renderPartial('//clinic/elements/photo',array('model'=>$model));?>
        </div>
    </div>
    <div class="right rigth_network">
        <div class="column_doctor column_doctor_network">
            <?php if(!empty($model->description)):?><div class="description">
                <?php echo MobiController::cutString($model->description,360);?>
            </div><?php endif;?>
        </div>
        <?php $this->renderPartial('//clinic/elements/rate',array('model'=>$model));?>
    </div>
</div>
</a>
<?php $this->renderPartial('//clinic/elements/clinics',array('model'=>$model));?>
