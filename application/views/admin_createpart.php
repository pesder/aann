<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open($function_key,'class="well form-horizontal"');?>
<div class="form-group"> 
  <label class="col-md-4 control-label">處室簡碼：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
    <?=form_input($pid_data);?>
  </div>
</div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label">中文名稱：</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-pushpin"></i></span>
  	<?=form_error('partname')?>
	<?=form_input($partname_data);?>
    </div>
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label">備註資料：</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
  	<?=form_input($partident_data);?>
    </div>
  </div>
</div>