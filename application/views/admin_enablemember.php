<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open($function_key,'class="well form-horizontal"');?>
<div class="form-group"> 
  <label class="col-md-4 control-label">選擇要歸入哪個處室：</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
    <?=form_error('partid')?>
	<?=form_dropdown($partid_data);	?>
  </div>
</div>
</div>
<div class="form-group"> 
  <label class="col-md-4 control-label">所有目前停用的組員：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		<?php if (!empty($userdata)) : ?>
		<?php foreach($userdata as $index => $name) : ?>
		<div class="row">
		<?=form_radio('userid', $index , FALSE);?>
		<?=form_label($name);?>
		</div>
		<?php endforeach; ?>
		<?php endif;?>
  </div>
</div>
</div>
<div class="text-center">
    <?=form_button($but1); ?> ｜ 
    <?=$button ?>
</div>
<?=form_close()?>