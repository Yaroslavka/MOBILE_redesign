<a class="service_item_wrap" href="<?php echo Yii::app()->createUrl('/site/serviceSingle',array('id'=>$model['translit']));?>">
    <h2><?php echo $model->title;?></h2><?php echo!empty($model->description)?"<p>".$model->description."</p>":'';?>
</a>