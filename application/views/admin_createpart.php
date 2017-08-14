<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open($function_key);?>
<table class="table">
<tr>
	<td class="text-center">處室簡碼：</td>
	<td>
	<div class="col-xs-3">
	<?php echo form_input($pid_data);?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">中文名稱：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('partname')?>
	<?php echo form_input($partname_data);?>
	</div>★
	</td>
</tr>
<tr>
	<td class="text-center">備註資料：</td>
	<td>
	<div class="col-xs-8">
		
	<?php echo form_input($partident_data);?>
	</div>
	</td>
</tr>