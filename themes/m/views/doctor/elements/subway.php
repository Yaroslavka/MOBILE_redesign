<?php if(!empty($data)):?>
<?php foreach($data as $value):?>
<?php if(!empty($value['sub']['title'])): ?><p class="beside_subway"><span class="color_subway col-color-<?php echo $value['sub']['line']['id'];?>"><?php echo $value['sub']['title'];?></span>(<?php echo!empty($value['lin1'])?'<i class="car"></i>'.$value['lin1']:' - ';?>, <?php echo!empty($value['lin2'])?'<i class="people"></i>'.$value['lin2']:' - ';?>)</p><?php endif; ?>
<?php endforeach;?>
<?php endif;?>