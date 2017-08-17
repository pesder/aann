<div class="form-group"> 
  <label class="col-md-4 control-label">此組員是否刪除：</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
    <div class="col-md-12">
	<a href="<?=config_item('base_url');?>/index.php/Reset/requestPassword" class="btn btn-warning"><span class="glyphicon glyphicon-repeat"> 重設密碼</a> <a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/deleteMember" class="btn btn-danger"><span class="glyphicon glyphicon-trash"> 刪除</a> <a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/disableMember" class="btn btn-warning"><span class="glyphicon glyphicon-remove-circle"> 停用</a>
	</div>
  </div>
</div>
</div>
<div class="text-center">
    <?=form_button($but1);?> ｜ 
    <?=$button ?>
</div>
<?=form_close()?>