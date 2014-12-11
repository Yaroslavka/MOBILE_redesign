<?php Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD); ?>
<div class="doctor_items_wrapper">
    <div class="doctor_item">
        <div class="top_doctor_single clearfix">
            <div class="doctor_wrapper clearfix">
                <div class="left">
                    <div class="inner">
                        <?php $this->renderPartial('//doctor/elements/photo',array('model'=>$model));?>
                    </div>
                </div>
                <div class="right">
                    <div class="column_doctor">
                        <div class="name"><?php echo!empty($model->lname)?$model->lname:'';?></div>
                        <div class="second_name"><?php echo!empty($model->fname)?$model->fname:'';?> <?php echo!empty($model->sname)?$model->sname:'';?></div>
                        <p class="doctor_category"><?php echo!empty($model->speciality)?$model->speciality:'';?></p>
                        <?php if(!empty($model->startyear)):?><span class="experience"><?php echo Yii::t('app',"Стаж {n} год|Стаж {n} года|Стаж {n} лет",array(date("Y")-$model->startyear));?></span><?php endif;?>
                        <?php $this->renderPartial('//doctor/elements/price',array('model'=>$model));?>
                    </div>
                    <?php $this->renderPartial('//doctor/elements/rate',array('model'=>$model));?>
                    <?php $this->renderPartial('//doctor/elements/record',array('model'=>$model));?>
                </div>
            </div>
        </div>
        <div class="wrapper_doctor_address_map">
            <?php $this->renderPartial('//doctor/elements/clinics',array('model'=>$model));?>
        </div>
        <div class="doctor_info_wrapper">
            <strong class="title">Информация о враче</strong>
            <?php if(!empty($model->doctor_service_s)):?>
                <ul>
                    <?php foreach($model->doctor_service_s as $value):?>
                        <li><?php echo CHtml::link($value->s->title,array('site/serviceSingle','id'=>$value->s->translit));?></li>
                    <?php endforeach;?>
                </ul>
            <?php endif;?>
            <?php if(!empty($model->professional)):?><?php echo $model->professional;?><?php endif;?>
            <?php if(!empty($model->education)):?><?php echo $model->education;?><?php endif;?>
            <?php if(!empty($model->associated)):?><?php echo $model->associated;?><?php endif;?>
            <?php if(!empty($model->awards)):?><?php echo $model->awards;?><?php endif;?>
        </div>
        <div class="reviews_clinic_wrapper">
            <?php $this->renderPartial('//doctor/elements/comments',array('model'=>$model)); ?>
        </div>
        <div class="similar_doctors_wrapper">
            <?php $this->renderPartial('//doctor/elements/similar',array('model'=>$model)); ?>
        </div>
    </div>
</div>