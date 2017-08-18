<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open($function_key . "/" . $newuser->oid,'class="well form-horizontal"');?>
<div class="form-group"> 
  <label class="col-md-4 control-label">使用者單一登入帳號：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		<?=form_label($newuser->openid_id,'openid_id',array('class' => 'form-control'));?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">使用者姓名：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		<?=form_label($newuser->fullname,'fullname',array('class' => 'form-control'));?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">使用者電子郵件：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
		<?=form_label($newuser->email,'email',array('class' => 'form-control'));?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">使用者單位：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
		<?=form_label($newuser->school_number,'school_number',array('class' => 'form-control'));?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">使用者身分：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		<?=form_label($newuser->job,'job',array('class' => 'form-control'));?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">新建帳號確認？</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
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
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">綁定系統內帳號：</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		<?=form_dropdown($userid_data);?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label"><?=form_checkbox($acc_data)?><?=form_label('以oid帳號建立系統內帳號');?></label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		<?=form_dropdown($partid_data);?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">是否阻擋此帳號？</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
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
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label"></label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
		<a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/deleteOidUser/<?=$newuser->oid?> " class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> 刪除</a>
  </div>
</div>
</div>
<div class="text-center">
    <?=form_button($but1);?> ｜ 
    <?=$button ?>
</div>
<?=form_close()?>