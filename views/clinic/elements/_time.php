<?php if(!empty($string)): ?><?php $data=explode("-",$string); ?><?php if(!empty($data[0])): ?><span><?php echo $data[0];?></span><?php endif; ?><?php if(!empty($data[1])): ?><span><?php echo $data[1];?></span><?php endif; ?><?php else: ?>&mdash;<?php endif; ?>