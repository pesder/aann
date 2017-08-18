<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open($function_key . "/" . $newuser->oid,'class="well form-horizontal"');?>
<div class="form-group"> 
  <label class="col-md-4 control-label">使用者單一登入帳號：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
		<?php echo form_label($newuser->openid_id,'openid_id',array('class' => 'form-control'));?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">使用者姓名：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
		<?php echo form_label($newuser->fullname,'fullname',array('class' => 'form-control'));?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">使用者電子郵件：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
		<?php echo form_label($newuser->email,'email',array('class' => 'form-control'));?>
  </div>
</div>
</div>
<table class="table">

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
<tr><td></td><td><a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/deleteOidUser/<?=$newuser->oid?> " class="btn btn-danger">刪除</a></td></tr>
</table>
<div class="text-center">
    <?php echo form_button($but1);?> ｜ 
    <?php echo $button ?>
</div>
<?=form_close()?>