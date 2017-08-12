<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/confirmNewuser/' . $newuser->oid);?>
<table class="table">
<tr>
	<td class="text-center">使用者編號：</td>
	<td>
	<div class="col-xs-3">
	<?php 
	echo form_radio('oid',$newuser->oid , TRUE);
	echo form_label($newuser->oid);?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">使用者單一登入帳號：</td>
	<td>
	<div class="col-xs-3">
	<?php echo form_label($newuser->openid_id);?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">使用者姓名：</td>
	<td>
	<div class="col-xs-3">
	<?php echo form_label($newuser->fullname);?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">使用者電子郵件：</td>
	<td>
	<div class="col-xs-3">
	<?php echo form_label($newuser->email);?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">使用者單位：</td>
	<td>
	<div class="col-xs-3">
	<?php echo form_label($newuser->school_number);?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">使用者身分：</td>
	<td>
	<div class="col-xs-3">
	<?php echo form_label($newuser->job);?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">新建帳號確認？</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('new')?>
	<?php
	if ($newuser->new == 1) {
	echo form_radio('new',0 , FALSE);
	echo form_label('確認');
	echo form_radio('new',1 , TRUE);
	echo form_label('未確認');
	} else {
	echo form_radio('new',0 , TRUE);
	echo form_label('確認');
	echo form_radio('new',1 , FALSE);
	echo form_label('未確認');
	}
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">綁定系統內帳號：</td>
	<td>
	<div class="col-xs-3">
	<?php
	echo form_dropdown($userid_data);
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">
	<?php 
	echo "<div class=form-group>";
	echo form_checkbox($acc_data);
	echo form_label('直接以oid帳號建立系統內帳號');
	echo "</div>";
	?></td>
	<td>
	<div class="col-xs-3">
	<?php
	
	echo form_dropdown($partid_data);
	?>
	</div>
	
	</td>
</tr>
<tr>
	<td class="text-center">是否阻擋此帳號？</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('banned')?>
	<?php
	if ($newuser->banned == 1) {
	echo form_radio('banned',0 , FALSE);
	echo form_label('否');
	echo form_radio('banned',1 , TRUE);
	echo form_label('是');
	} else {
	echo form_radio('banned',0 , TRUE);
	echo form_label('否');
	echo form_radio('banned',1 , FALSE);
	echo form_label('是');
	}
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
      'content' =>  '修改',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/OidManage" class="btn btn-primary" accesskey="h">回單一登入選單</a> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin" class="btn btn-primary" accesskey="h">回管理選單</a>
</div>
<?=form_close()?>