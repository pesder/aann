<div class="form-group"> 
  <label class="col-md-4 control-label">此組員是否刪除：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
    <div class="col-md-12">
	<a href="<?=config_item('base_url');?>/index.php/Reset/request_password" class="btn btn-warning" title="重設密碼"><span class="glyphicon glyphicon-repeat"> 重設密碼</a> <a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/delete_member" class="btn btn-danger" title="刪除"><span class="glyphicon glyphicon-trash"> 刪除</a> <a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/disable_member" class="btn btn-warning" title="停用"><span class="glyphicon glyphicon-remove-circle"> 停用</a>
	</div>
  </div>
</div>
</div>
<div class="text-center">
    <?=form_button($but1);?> ｜ 
    <?=$button ?>
</div>
<?=form_close()?>