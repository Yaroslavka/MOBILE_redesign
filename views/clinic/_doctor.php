<a href="<?php echo Yii::app()->createUrl('/doctor/single',array('id'=>$model->translit));?>" class="doctor_snippet_description">
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
                
                    <?php if(!empty($this->category)):?>
                    <?php $price=$model->firstPriceCategory($this->category); ?>
                    <?php if(!empty($price)):?><?php foreach($price as $value):?>
                    <?php if($value->price!='00'):?><strong class="price">Прием:  <?php echo $value->price?$value->price.' руб.':'&mdash;';?></strong><?php else:?><strong class="price">Прием: Бесплатно</strong><?php endif;?><?php break; ?><?php endforeach;?><?php endif;?><?php else:?><?php if(!empty($model->firstPrice)):?><?php foreach($model->firstPrice as $value):?><?php if($value->price!='00'):?><strong class="price">Прием:<?php echo $value->price?$value->price.' руб.':'&mdash;';?></strong><?php else:?><strong class="price">Прием: Бесплатно</strong><?php endif;?><?php break; ?><?php endforeach;?><?php endif;?><?php endif;?>
                
            </div>
            <?php $this->renderPartial('//doctor/elements/rate',array('model'=>$model));?>
        </div>
    </div>
</a>