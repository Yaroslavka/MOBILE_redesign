<div class="left">
    <a href="<?php echo Yii::app()->createUrl('/doctor/single',array('id'=>$model->translit));?>"><?php $this->renderPartial('//doctor/elements/photo',array('model'=>$model));?></a>
</div>
<div class="right">
    <div class="description clearfix">
        <p class="doctor_name"><a href="<?php echo Yii::app()->createUrl('/doctor/single',array('id'=>$model->translit));?>"><?php echo!empty($model->lname)?$model->lname:'';?> <?php echo!empty($model->fname)?$model->fname:'';?> <?php echo!empty($model->sname)?$model->sname:'';?></a></p>
        <p class="doctor_category"><?php echo!empty($model->speciality)?$model->speciality:'';?></p>
    </div>
    <?php $this->renderPartial('//doctor/elements/rate_small',array('model'=>$model));?>
</div>