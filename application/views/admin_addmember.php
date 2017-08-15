<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open($function_key);?>
<table class="table">
<tr>
	<td class="text-center">屬於哪一處室：</td>
	<td>
	<div class="col-xs-3">
	<?=form_dropdown($partid_data);	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">發佈公告的帳號：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('username')?>
	<?=form_input($username_data);?>
	</div>★(英文或數字，最多10個字元)
	</td>
</tr>
<tr>
	<td class="text-center">中文真實姓名：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('realname')?>
	<?=form_input($realname_data);?>
	</div>★
	</td>
</tr>
<tr>
	<td class="text-center">發佈消息的密碼：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('userpass')?>
	<?=form_password($userpass_data);?>
	</div>★
	</td>
</tr>
<tr>
	<td class="text-center">電子信箱：</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('emal')?>
	<?=form_input($email_data);?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">備註資料：</td>
	<td>
	<div class="col-xs-8">
	<?=form_input($userident_data);?>
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