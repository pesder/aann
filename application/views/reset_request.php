<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<div class="bg-warning text-center"><h3>請輸入您在系統中使用的帳號與電子郵件，以便發送重設資訊</h3></div>
<?=form_open('Reset/userRequestPassword', 'class="well form-horizontal"');?>
<div class="form-group"> 
  <label class="col-md-4 control-label">發佈公告的帳號：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		<?=form_error('username')?>
		<?=form_input($username_data);	?>
	<span class="input-group-addon">★</span>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">電子信箱：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
		<?=form_error('email')?>
		<?=form_input($email_data);?>
	<span class="input-group-addon">★</span>
  </div>
</div>
</div>
<div class="text-center">
    <?=form_button($but1);?>
</div>
<?=form_close()?>