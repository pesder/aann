<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updatePart2');?>
<table class="table">
<tr>
	<td class="text-center">處室簡碼：</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('pid')?>
	<?php
	$pid_data = array (
		'name'	=>	'pid',
		'class'	=>	'form-control',
		'value'	=>	$parttb->pid);
	echo form_input($pid_data);
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">中文名稱：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('partname')?>
	<?php
		$partname_data = array (
		'name'	=>	'partname',
		'class'	=>	'form-control',
		'value'	=>	$parttb->partname);
	echo form_input($partname_data);
	?>
	</div>★
	</td>
</tr>
<tr>
	<td class="text-center">備註資料：</td>
	<td>
	<div class="col-xs-8">
		
	<?php
		$partident_data = array (
		'name'	=>	'partident',
		'class'	=>	'form-control',
		'value'	=>	$parttb->partident);
	echo form_input($partident_data);
	?>
	</div>
	</td>
</tr>

</table>
<div class="text-center">
    <?php
    $but1 = array (
      'name'  =>  'sent',
      'type'  =>  'submit',
      'content' =>  '送出',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin" class="btn btn-primary" accesskey="h">回管理選單</a>
</div>
<?=form_close()?>