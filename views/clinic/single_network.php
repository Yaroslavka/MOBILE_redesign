<?php Yii::app()->clientScript->registerScriptFile("http://api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU",CClientScript::POS_HEAD); ?>
<div class="clinic_items_wrapper">
    <div class="clinic_item">
        <div class="clinic_wrapper clearfix">
            <div class="top_clinic_single clearfix">
               
                <div class="left">
                    <?php $this->renderPartial('//clinic/elements/photo',array('model'=>$model));?>
                </div>
                <div class="right">
                    <div class="column_doctor">
                        <p class="name"><?php echo $model->title;?></p>
                    </div>
                    <?php $this->renderPartial('//clinic/elements/rate',array('model'=>$model));?>
                </div>
                <?php if(!empty($model->description)):?><div class="description_network">
                            <?php echo MobiController::cutString($model->description, 200);?>
                        </div><?php endif;?>
                <?php $this->renderPartial('//clinic/elements/record',array('model'=>$model));?>
            </div>
            <div class="network_accordion_wrapper">
                <?php $this->renderPartial('//clinic/elements/clinics_single',array('model'=>$model)); ?>
            </div>
            <div class="info_clinic">
                <?php $this->renderPartial('//clinic/elements/info',array('model'=>$model));?>
            </div>
        </div>
    </div>
</div>