<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updateSite', 'class="form-inline"');?>
<table class="table">
<?php foreach($settings as $set) : ?>
<tr>
	<td class="text-center"><?=$set->configkey?></td>
	<td>
	<div class="col-xs-3">
	<?php
	$set_data = array (
		'name'	=>	$set->configkey,
		'class'	=>	'form-control',
		'value'	=>	$set->configvalue);
	echo form_error($set->configkey);
	echo form_input($set_data);
	?>
	</div>
	</td>
	<td><?=$set->desc?></td>
</tr>
<?php endforeach; ?>
<?php foreach($settings2 as $set2) : ?>
<tr>
	<td class="text-center"><?=$set2->configkey?></td>
	<td>
	<div class="col-xs-3">
	<?php
	$set2_data = array (
		'name'	=>	$set2->configkey,
		'class'	=>	'form-control',
		'value'	=>	$set2->configvalue);
	echo form_error($set2->configkey);
	echo form_input($set2_data);
	?>
	</div>
	</td>
	<td><?=$set2->desc?></td>
</tr>
<?php endforeach; ?>
<?php foreach($settings3 as $set3) : ?>
<tr>
	<td class="text-center"><?=$set3->configkey?></td>
	<td>
	<div class="col-xs-3">
	<?php
	$set3_data = array (
		'name'	=>	$set3->configkey,
		'class'	=>	'form-control');
	echo form_password($set3_data);
	?>
	</div>
	</td>
	<td><?=$set3->desc?></td>
</tr>
<?php endforeach; ?>
</table>
<div class="text-center">
    <?php
    $but1 = array (
      'name'  =>  'sent',
      'type'  =>  'submit',
      'content' =>  '更新',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin" class="btn btn-primary" accesskey="h">回管理選單</a>
</div>
<?=form_close()?>