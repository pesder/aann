<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updateSMTP', 'class="well form-horizontal"');?>
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
	
	if ($set->sort == 4) {
		echo form_error($set->configkey);
		echo form_password($set_data);
	} else
	{
		echo form_error($set->configkey);
		echo form_input($set_data);
	}
	?>
	</div>
	</td>
	<td><?=$set->desc?></td>
</tr>
<?php endforeach; ?>
</table>
<div class="text-center">
    <?=form_button($but1);?> ｜ 
    <?=$button ?>
</div>
<?=form_close()?>