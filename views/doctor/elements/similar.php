<?php if(!empty($model->sameBlock)):?><strong class="title_similar">Похожие врачи:</strong>
<div class="similar_doctors_items">
    <?php foreach($model->sameBlock as $value):?>
    <div class="similar_doctors_item clearfix">
        <?php $this->renderPartial('_sames',array('model'=>$value)); ?>
    </div>
    <?php endforeach;?>
</div><?php endif;?>