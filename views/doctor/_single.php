<a class="doctor_snippet_description" href="<?php echo Yii::app()->createUrl('/doctor/single',array('id'=>$model->translit));?>">
    <div class="doctor_wrapper clearfix">
        <div class="left">
            <div class="inner">
               <?php $this->renderPartial('//doctor/elements/photo',array('model'=>$model));?>
            </div>
            <?php $this->renderPartial('//doctor/elements/rate',array('model'=>$model));?>
        </div>
        <div class="right">
            <div class="column_doctor">
               <div class="name"><?php echo!empty($model->lname)?$model->lname:'';?></div>
                <div class="second_name"><?php echo!empty($model->fname)?$model->fname:'';?> <?php echo!empty($model->sname)?$model->sname:'';?></div>
                <p class="doctor_category"><?php echo!empty($model->speciality)?$model->speciality:'';?></p>
                <?php if(!empty($model->startyear)):?><span class="experience"><?php echo Yii::t('app',"Стаж {n} год|Стаж {n} года|Стаж {n} лет",array(date("Y")-$model->startyear));?></span><?php endif;?>
                <?php $this->renderPartial('//doctor/elements/price',array('model'=>$model));?>
            </div>
        </div>
    </div>
</a>
<?php $this->renderPartial('//doctor/elements/clinics',array('model'=>$model));?>