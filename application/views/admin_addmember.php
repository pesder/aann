<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/addMember');?>
<table class="table">
<tr>
	<td class="text-center">屬於哪一處室：</td>
	<td>
	<div class="col-xs-3">
	<?php
	$partid_data = array (
		'name'	=>	'partid',
		'class'	=>	'form-control',
		'options'	=>	$options
	);
	echo form_dropdown($partid_data);
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">發佈公告的帳號：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('username')?>
	<?php
		$username_data = array (
		'name'	=>	'username',
		'class'	=>	'form-control');
	echo form_input($username_data);
	?>
	</div>★(英文或數字，最多10個字元)
	</td>
</tr>
<tr>
	<td class="text-center">中文真實姓名：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('realname')?>
	<?php
		$realname_data = array (
		'name'	=>	'realname',
		'class'	=>	'form-control');
	echo form_input($realname_data);
	?>
	</div>★
	</td>
</tr>
<tr>
	<td class="text-center">發佈消息的密碼：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('userpass')?>
	<?php
		$userpass_data = array (
		'name'	=>	'userpass',
		'class'	=>	'form-control');
	echo form_password($userpass_data);
	?>
	</div>★
	</td>
</tr>
<tr>
	<td class="text-center">電子信箱：</td>
	<td>
	<div class="col-xs-3">
	<?php
		$email_data = array (
		'name'	=>	'email',
		'class'	=>	'form-control');
	echo form_input($email_data);
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">備註資料：</td>
	<td>
	<div class="col-xs-8">
	<?php
		$userident_data = array (
		'name'	=>	'userident',
		'class'	=>	'form-control');
	echo form_input($userident_data);
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">是否為此處室的管理者：</td>
	<td>
	<div class="col-xs-8">
	<?php
	echo form_radio('rootuid',0 , TRUE);
	echo form_label('否');
	echo form_radio('rootuid',1 , FALSE);
	echo form_label('是');
	?> (一個處室只可設管理者一人)
	</div>
	</td>
</tr>

</table>
<div class="text-center">
    <?php
    $but1 = array (
      'name'  =>  'sent',
      'type'  =>  'submit',
      'content' =>  '新增',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin" class="btn btn-primary" accesskey="h">回管理選單</a>
</div>
<?=form_close()?>