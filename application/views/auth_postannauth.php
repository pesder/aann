<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Auth/postAnnAuth','class="well form-horizontal"');?>
<div class="form-group"> 
  <label class="col-md-4 control-label">選擇處室</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
    <?=form_error('partid')?>
	<?=form_dropdown($partid_data);	?>
  </div>
</div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label">使用者帳號</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  	<?=form_error('username')?>
	<?=form_input($username_data);?>
    </div>
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label">密碼</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	<?=form_error('userpass')?>
	<?=form_password($userpass_data);?>
    </div>
  </div>
</div>

</table>
<div class="text-center">

    <?=form_button($but1);?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Main/" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-home"></span> 回首頁</a>
    <a href="<?=config_item('base_url');?>/index.php/Reset/userRequestPassword/" class="btn btn-primary btn-xm" accesskey="f"><span class="glyphicon glyphicon-question-sign"></span> 忘記密碼</a>
</div>
<?=form_close()?>