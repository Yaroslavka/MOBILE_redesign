<p class="name"><a href="<?php echo Yii::app()->createUrl('/doctor/single',array('id'=>$model->translit));?>"><?php echo!empty($model->lname)?$model->lname:'';?> <?php echo!empty($model->fname)?$model->fname:'';?> <?php echo!empty($model->sname)?$model->sname:'';?></a></p>
<div class="doctor_wrapper clearfix">
    <div class="left">
        <div class="inner">
            <a href="<?php echo Yii::app()->createUrl('/doctor/single',array('id'=>$model->translit));?>"><?php $this->renderPartial('//doctor/elements/photo',array('model'=>$model));?></a>
        </div>
        <strong><?php if(!empty($model->startyear)):?><?php echo Yii::t('app',"стаж {n} год|стаж {n} года|стаж {n} лет",array(date("Y")-$model->startyear));?><?php endif;?></strong>
    </div>
    <div class="right">
        <div class="column_doctor">
            <p class="doctor_category"><?php echo!empty($model->speciality)?$model->speciality:'';?></p>
            <?php if(!empty($model->description)):?><div class="description">
                <?php echo MobiController::cutString($model->description,300);?>
            </div><?php endif;?>
            <?php $this->renderPartial('//doctor/elements/price',array('model'=>$model));?>
        </div>
        <?php $this->renderPartial('//doctor/elements/rate',array('model'=>$model));?>
        <?php $this->renderPartial('//doctor/elements/record',array('model'=>$model));?>
    </div>
</div>