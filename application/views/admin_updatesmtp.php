<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updateSMTP', 'class="form-inline"');?>
<table class="table .table-condensed">
<tr><th>設定鍵</th><th>設定值</th><th>說明</th>
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
</table>
<div class="text-center">
    <?php echo form_button($but1);?> ｜ 
    <?php echo $button ?>
</div>
<?=form_close()?>