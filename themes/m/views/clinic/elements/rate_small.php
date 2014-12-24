<?php if(!empty($model->rate10)): ?><div class="rate_box">
    <span class="title">рейтинг</span>
    <div class="rate_dinamic"><span class="count"><?php echo str_replace(",",".",round($model->rate10,1));?></span> <div class="desc"><span></span></div></div>
</div><?php endif; ?>