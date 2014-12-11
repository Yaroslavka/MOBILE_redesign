<?php if(!empty($model->rate10)): ?><div class="rate_box">
    <div class="rate_dinamic"><span class="count"><?php echo str_replace(",",".",round($model->rate10,1));?></span> <span class="desc">10</span></div>
    <span class="title">рейтинг</span>
</div><?php endif; ?>