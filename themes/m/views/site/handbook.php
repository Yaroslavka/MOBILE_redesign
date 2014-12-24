<div class="article">
    <h1><?php echo $model->name; ?></h1>
    <?php echo !empty($model->description)?$model->description:''; ?>
</div>
<?php if(!empty($model->category_node_s)): ?>
<div class="article_list_wrapper">
    <h2 class="title">Памятка пациента</h2>
    <ul>
        <?php foreach($model->category_node_s as $value): ?>
        <li>
            <?php echo $this->renderPartial('_blog',array('model'=>$value));?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<div class="social_container clearfix">
    <div class="soc_box soc_g"><a class="google" rel="publisher" href="https://plus.google.com/104496212479886693452" target="_blank"></a></div>
    <div class="soc_box soc_f"><a class="facebook" target="_blank" href="http://www.facebook.com/medbooking"></a></div>
    <div class="soc_box soc_v"><a class="vk" target="_blank" href="http://vk.com/medbooking"></a></div>
    <div class="soc_box soc_t"><a class="twitter" rel="nofollow" target="_blank" href="https://twitter.com/medbookingru"></a></div>
</div>
<?php if(!empty($model->category_illness_s)): ?>
<div class="list_items_disease_wrapper">
    <strong class="title"><?php echo $model->name; ?> - заболевания</strong>
    <ul>
        <?php foreach($model->category_illness_s as $value): ?>
        <?php if(!empty($value->i_i->title)): ?>
        <li><a href="<?php echo Yii::app()->createUrl('/site/illnessSingle',array('id'=>$value['i_i']['translit']));?>"><?php echo $value->i_i->title;?></a></li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<?php if(!empty($model->category_services_s)): ?>
<div class="list_items_service_wrapper">
    <strong class="title"><?php echo $model->name; ?> - услуга</strong>
    <ul>
        <?php foreach($model->category_services_s as $value): ?>
        <?php if(!empty($value->p_s->title)): ?>
        <li><a href="<?php echo Yii::app()->createUrl('/site/serviceSingle',array('id'=>$value['p_s']['translit']));?>"><?php echo $value->p_s->title;?></a></li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<?php if(!empty($data)): ?>
<div class="similar_doctors_wrapper">
    <strong class="title">Специалисты<?php echo !empty($model['name_spec'])?', '.$model['name_spec']:''; ?>:</strong>
    <div class="similar_doctors_items">
        <div class="inner">
            <?php foreach($data as $value): ?>
            <div class="similar_doctors_item clearfix">
                <?php echo $this->renderPartial('//doctor/_patient',array('model'=>$value));?>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="column_btn_doctor clearfix">
            <span class="order_btn">
                <?php if(!empty($model['name_translit'])): ?>
                <a href="<?php echo Yii::app()->createAbsoluteUrl('/doctor/index',array('category'=>$model['name_translit'])); ?>">Показать всех специалистов</a>
                <?php else: ?>
                <a href="<?php echo Yii::app()->createAbsoluteUrl('/doctor/index'); ?>">Показать всех специалистов</a>
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>
<?php endif; ?>