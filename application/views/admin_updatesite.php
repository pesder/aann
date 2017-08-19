<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updateSite', 'class="well form-horizontal"');?>
<table class="table .table-condensed">
<tr><th><span class="glyphicon glyphicon-cog"></span> 設定鍵</th><th><span class="glyphicon glyphicon-pencil"></span> 設定值</th><th><span class="glyphicon glyphicon-list-alt"></span> 說明</th>
<?php foreach($settings as $set) : ?>
<tr>
	<td class="text-center"><?=$set->configkey?></td>
	<td>
	<div class="col-md-12">
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
	<div class="col-md-12">
	<?php
	$set2_data = array (
		'name'	=>	$set2->configkey,
		'class'	=>	'form-control');
	
	if ($set2->sort == 2) {
		echo form_password($set2_data);
	} else
	{
		$set2_data['value'] = $set2->configvalue;
		echo form_error($set2->configkey);
		echo form_input($set2_data);
	}
	
	?>
	</div>
	</td>
	<td><?=$set2->desc?></td>
</tr>
<?php endforeach; ?>
</table>
<div class="text-center">
    <?=form_button($but1);?> ｜ 
    <?=$button ?>
</div>
<?=form_close()?>