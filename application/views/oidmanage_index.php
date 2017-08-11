<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<h1><?=$h1?></h1>
<?php foreach ($h1group as $index => $name): ?>
<a href="<?=config_item('base_url');?>/index.php<?=$index?>" class="btn btn-info"><?=$name?></a> 
<?php endforeach; ?>
<h1><?=$h2?></h1>
<?php foreach ($h2group as $index => $name): ?>
<a href="<?=config_item('base_url');?>/index.php<?=$index?>" class="btn btn-info"><?=$name?></a> 
<?php endforeach; ?>
<?php if (!empty($newuser)) : ?>
<h1>新進 Openid</h1>
<?php foreach ($newuser as $index => $name): ?>
<div class="btn btn-info">
    <?=$name->fullname?>
</div> 
<?php endforeach; ?>
<?php endif; ?>