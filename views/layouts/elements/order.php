<div class="order_wrapper">
    <div class="top"><span class="close"></span></div>
    <div class="middle">
        <div class="title_box">
            <strong>Запишитесь</strong>
            <span>на прием к врачу</span>
        </div>
        <div class="title_box_home">
            <strong>Вызвать</strong>
            <span>доктора на дом</span>
        </div>
        <div class="form_box">
            <p class="doctor_order_name" id="doctor-name"></p>
            <p class="clinic_order_name" id="clinic-name"></p>
            <?php echo CHtml::beginForm('/', 'post',array('class'=>'form-doctor-comment')); ?>
                <?php echo CHtml::hiddenField('sn','m.medbooking.com'); ?>
                <?php echo CHtml::hiddenField('AApiRecord[gender]','1'); ?>
                <?php echo CHtml::hiddenField('AApiRecord[sogl]','1'); ?>
                <?php echo CHtml::hiddenField('sms',''); ?>
                <?php echo CHtml::hiddenField('AApiRecord[clinic_id]',''); ?>
                <?php echo CHtml::hiddenField('AApiRecord[clinic_name]',''); ?>
                <?php echo CHtml::hiddenField('AApiRecord[doctor_id]',''); ?>
                <?php echo CHtml::hiddenField('AApiRecord[doctor_name]',''); ?>
                <?php echo CHtml::hiddenField('AApiRecord[home]',0); ?>
                <?php echo CHtml::hiddenField('AApiRecord[route]','http://m.medbooking.com'); ?>
                <div class="row name_text">
                    <input type="text" value="" name="AApiRecord[name]">
                </div>
                <div class="row phone_text">
                    <input  type="text" value="" name="AApiRecord[telephone]">
                    <div class="error">Заполните поле телефона</div>
                </div>
                <div class="row btn_send">
                    <input type="submit" value="Записаться к врачу">
                </div>
            </form>
        </div>
    </div>
    <div class="bottom">
        <p class="description">
            После отправки с Вами в ближайшее<br>
            время свяжется специалист call-центра<br>
            и уточнит все интересующие Вас вопросы.<br>
        </p>
        <p class="copy_order">
            Отправляя заявку, Вы соглашаетесь<br>
            с пользовательским соглашением<br>
        </p>
    </div>
</div>
<div class="order_wrapper_exit">
    <div class="top"><span class="close"></span></div>
    <div class="middle">
        <div class="title_box">
            <strong>Вы действительно</strong>
            <span>хотите прервать запись?</span>
        </div>
        <div class="btn_box">
            <span class="btn_no">Нет, продолжить запись!</span>
            <span class="btn_yes">Да, прервать.</span>
        </div>
    </div>
    <div class="bottom">
        <p class="description">
            Если у Вас возникли вопросы,<br>
            позвоните нам по телефону <br>
        </p>
        <p class="copy_order">
            <span>+7 (499) 705-35-35 </span><br>
            или закажите обратный звонок.<br>
        </p>
    </div>
</div>
<div class="success_order">
    <div class="top"><span class="close"></span></div>
    <div class="content_success">
        <span class="title">Ваша заявка принята!</span>
        <p>
            В ближайшее время с Вами свяжется <br>
            специалист call-центра и уточнит все <br>
            интересующие Вас вопросы <br>
        </p>
    </div>
</div>