<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h1><?=$function_name?></h1></div>
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

<a href="<?=config_item('base_url');?>/index.php/Admin/confirmNewuser/<?=$name->oid?>" class="btn btn-info"><?=$name->fullname?></a>


<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($message)) : ?>
<h1><?=$message?></h1>
<?php endif; ?>