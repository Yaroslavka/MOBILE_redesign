<strong class="title">Информация о клинике</strong>
<?php echo!empty($model->underbody)?$model->underbody:'';?>
<?php if(!empty($model->gImages)):?>
<div class="gallery_container">
   <div id="gallery" class="owl-carousel">
        <?php foreach($model->gImages as $key=> $value):?>
            <?php if(!empty($value['small'])):?>
               <div class="item">
                    <img class="lazyOwl" alt="" data-src="http://medbooking.com/<?php echo $value['big'];?>" >
                </div>
            <?php endif;?>
        <?php endforeach;?>
    </div>
</div>
<?php endif;?>
<?php echo!empty($model->body)?$model->body:'';?>






