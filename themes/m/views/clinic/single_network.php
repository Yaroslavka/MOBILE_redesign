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
                            <?php echo MobiController::cutString($model->description, 300);?>
                        </div><?php endif;?>
                <?php $this->renderPartial('//clinic/elements/record',array('model'=>$model));?>
            </div>
                <div class="clinic_tabs clearfix">
                    <span data-content-tab='doctors_tab' class="tab_doc active"></span>
                    <span data-content-tab='info_tab' class="tab_info">О клинике</span>
                    <span data-content-tab='reviews_tab' class="tab_review">Отзывы</span>
                </div>
                <div class="tab_content">
                    <div class="active tab_box doctors_tab">
                        <div class="doctor_items_conrainer">
                            <?php $this->renderPartial('//clinic/elements/doctor'); ?>
                        </div>

                   </div>
                    <div class="tab_box info_tab info_clinic">
                                                <div class="similar_clinic_wrapper similar_doctors_wrapper">
                            <?php $this->renderPartial('//clinic/elements/similar',array('model'=>$model)); ?>
                        </div>
                    </div>
                    <div class="tab_box reviews_tab reviews_clinic_wrapper">
                        <?php $this->renderPartial('//clinic/elements/comments',array('model'=>$model)); ?>
                    </div>
                </div>
        </div>
    </div>
</div>