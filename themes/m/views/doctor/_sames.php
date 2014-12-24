<a class="sames_doctor_wrapper" href="<?php echo Yii::app()->createUrl('/doctor/single',array('id'=>$model->translit));?>">
    <div class="left">
       <?php $this->renderPartial('//doctor/elements/photo',array('model'=>$model));?>
    </div>
    <div class="right">
        <div class="description clearfix">
            <p class="doctor_name">
                <span class="name"><?php echo!empty($model->lname)?$model->lname:'';?></span> 
                <span class="second_name"><?php echo!empty($model->fname)?$model->fname:'';?> <?php echo!empty($model->sname)?$model->sname:'';?></span>
            </p>
            <p class="doctor_category"><?php echo!empty($model->speciality)?$model->speciality:'';?></p>
        </div>
        <?php $this->renderPartial('//doctor/elements/rate',array('model'=>$model));?>
    </div>
</a>