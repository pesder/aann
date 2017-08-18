<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updateAnn', 'class="well form-horizontal"');?>
<table class="table .table-condensed container-fluid">
<tr><th><span class="glyphicon glyphicon-cog"></span> 設定鍵</th><th><span class="glyphicon glyphicon-pencil"></span> 設定值</th><th><span class="glyphicon glyphicon-list-alt"></span> 說明</th>
</tr>
<?php foreach($settings as $set) : ?>
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
</table>
<div class="text-center">
    <?=form_button($but1);?> ｜ 
    <?=$button ?>
</div>
<?=form_close()?>