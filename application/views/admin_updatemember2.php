<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updateMember2');?>
<table class="table">
<tr>
	<td class="text-center">屬於哪一處室：</td>
	<td>
	<div class="col-xs-3">
	<?php
	$partid_data = array (
		'name'	=>	'partid',
		'class'	=>	'form-control',
		'options'	=>	$options,
		'selected'	=>	$userdata->partid
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
		'class'	=>	'form-control',
		'value'	=>	$userdata->username);
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
		'class'	=>	'form-control',
		'value'	=>	$userdata->realname);
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
	</div>(大小寫有差，不修改不需填寫，留空即可)
	</td>
</tr>
<tr>
	<td class="text-center">電子信箱：</td>
	<td>
	<div class="col-xs-3">
	<?php
		$email_data = array (
		'name'	=>	'email',
		'class'	=>	'form-control',
		'value'	=>	$userdata->email);
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
		'class'	=>	'form-control',
		'value'	=>	$userdata->userident);
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
	if($partdata == $userdata->userid)
	{
	echo form_radio('rootuid',0 , FALSE);
	echo form_label('否');
	echo form_radio('rootuid',1 , TRUE);
	echo form_label('是');
	} else
	{
	echo form_radio('rootuid',0 , TRUE);
	echo form_label('否');
	echo form_radio('rootuid',1 , FALSE);
	echo form_label('是');
	}

	?> (一個處室只可設管理者一人)
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">此組員是否刪除：</td>
	<td>
	<div class="col-xs-8">
<a href="<?=config_item('base_url');?>/index.php/Admin/deleteMember" class="btn btn-danger">刪除</a> <a href="<?=config_item('base_url');?>/index.php/Admin/disableMember" class="btn btn-warning">停用</a>
	</div>
	</td>
</tr>
</table>
<div class="text-center">
    <?php
    $but1 = array (
      'name'  =>  'sent',
      'type'  =>  'submit',
      'content' =>  '修改',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin/updateMember1" class="btn btn-primary" accesskey="h">回處室選單</a> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin" class="btn btn-primary" accesskey="h">回管理選單</a>
</div>
<?=form_close()?>