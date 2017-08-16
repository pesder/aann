<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updatePart1','class="well form-horizontal"');?>
<div class="form-group"> 
  <label class="col-md-4 control-label">選擇要修改的處室</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
    <?=form_error('partid')?>
	<?=form_dropdown($partid_data);	?>
  </div>
</div>
</div>
<div class="text-center">
    <?=form_button($but1);?> ｜ 
    <?=$button ?>
</div>
<?=form_close()?>