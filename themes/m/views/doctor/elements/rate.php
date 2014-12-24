<?php if(!empty($model->rate10)): ?><div class="column_rate_reviews">
    <?php $this->renderPartial('//doctor/elements/rate_small',array('model'=>$model));?>
    <?php if(!empty($model->comm)):?><div class="reviews_box_wrap">
        <div class="reviews_box">
            <span><?php echo $model->comm;?></span>
        </div>
    </div><?php endif;?>
</div><?php endif;?>