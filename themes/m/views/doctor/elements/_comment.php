<?php if(!empty($data)): ?><strong class="title">Отзывы пациентов о докторе:</strong>
<div class="reviews_clinic_items_wrapper">
    <?php foreach($data as $value): ?>
    <div class="reviews_clinic_items">
        <div class="reviews_clinic_item">
            <div class="top clearfix">
                <div class="rate_pro rate_box">
                    <div class="rate_box_inner">
                        <div class="num_box clearfix">
                            <div class="rate_num_wrapper clearfix">
                                <?php if(!empty($value->doctor_value)): ?>
                                <?php if(!empty($value->doctor_value)): ?>
                                <?php for($index=0; $index<$value->doctor_value; $index++): ?>
                                <div class="active box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                                <?php if(!empty($value->doctor_value)&&(10-$value->doctor_value)>0): $count=10-$value->doctor_value; ?>
                                <?php for($index=0; $index<$count; $index++): ?>
                                <div class="box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                                <?php else: ?>
                                <?php for($index=0; $index<10; $index++): ?>
                                <div class="box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                            <strong><?php echo!empty($value->doctor_value)?$value->doctor_value:'';?></strong>
                            <span>Профессионализм</span>
                        </div>
                    </div>
                </div>
                <div class="rate_note rate_box">
                    <div class="rate_box_inner">
                        <div class="num_box clearfix">
                            <div class="rate_num_wrapper clearfix">
                                <?php if(!empty($value->attention_value)): ?>
                                <?php if(!empty($value->attention_value)): ?>
                                <?php for($index=0; $index<$value->attention_value; $index++): ?>
                                <div class="active box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                                <?php if(!empty($value->attention_value)&&(10-$value->attention_value)>0): $count=10-$value->attention_value; ?>
                                <?php for($index=0; $index<$count; $index++): ?>
                                <div class="box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                                <?php else: ?>
                                <?php for($index=0; $index<10; $index++): ?>
                                <div class="box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                            <strong><?php echo!empty($value->attention_value)?$value->attention_value:'';?></strong>
                            <span>Внимание</span>
                        </div>
                    </div>
                </div>
                <div class="rate_price rate_box">
                    <div class="rate_box_inner">
                        <div class="num_box clearfix">
                            <div class="rate_num_wrapper clearfix">
                                <?php if(!empty($value->price_value)): ?>
                                <?php if(!empty($value->price_value)): ?>
                                <?php for($index=0; $index<$value->price_value; $index++): ?>
                                <div class="active box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                                <?php if(!empty($value->price_value)&&(10-$value->price_value)>0): $count=10-$value->price_value; ?>
                                <?php for($index=0; $index<$count; $index++): ?>
                                <div class="box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                                <?php else: ?>
                                <?php for($index=0; $index<10; $index++): ?>
                                <div class="box"></div>
                                <?php endfor; ?>
                                <?php endif; ?>
                            </div>
                            <strong><?php echo!empty($value->price_value)?$value->price_value:'';?></strong>
                            <span>Цена / Качество</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <?php echo!empty($value->description)?$value->description:'';?>
                <strong class="name">&mdash; <?php echo!empty($value->name)?$value->name:'';?></strong>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if(!empty($this->limit)): ?><a class="more" href="<?php echo Yii::app()->createUrl('/doctor/comment',array('id'=>$model->id,'limit'=>$this->limit)); ?>">Еще отзывы</a><?php endif; ?>
</div><?php endif; ?>