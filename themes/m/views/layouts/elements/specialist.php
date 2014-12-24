<?php if(!empty($data)): ?>
<form id="clinic-spec" action="<?php echo Yii::app()->createUrl('/clinic/specialist',array('clinic_id'=>$this->clinic_id));?>">
    <ul class="items_specialist_list_check">
        <?php foreach($data as $value): ?>
        <li><input name="category[]" type="checkbox" value="<?php echo $value['translit']; ?>"><a href="#"><?php echo $value['title'];?></a></li>
        <?php endforeach; ?>
    </ul>
    <div class="btn_wrap_spec"><a href="#">выбрать</a></div>
</form>
<?php endif; ?>