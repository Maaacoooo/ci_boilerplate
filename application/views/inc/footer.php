<div class="pull-right hidden-xs">
    <strong>&copy; 2017 <a href="#">Cong Tibshrani</a></strong>. All rights
    reserved.
</div>     	
	<?php if(ENVIRONMENT == 'development'): ?>
    <span class="label bg-blue">{elapsed_time}s</span>
	<span class="label bg-green">{memory_usage}</span>
	<?php endif ?>
	<span class="label bg-black">Your IP: <?=$_SERVER['REMOTE_ADDR']?></span>