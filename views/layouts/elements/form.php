<div class="title">Записывайтесь на прием online!</div>
<form action="<?php echo Yii::app()->createUrl('doctor/index'); ?>">
    <div class="select_category fake_select">
        <i class="icon"></i>
        <input readonly type="text" placeholder="Выберите специалиста">
    </div>
    <div class="select_subway fake_select">
        <i class="icon"></i>
        <input readonly type="text" placeholder="Выберите ст.метро">
    </div>
    <div class="search_btn">
        <input type="submit" value="Найти специалиста">
    </div>
</form>