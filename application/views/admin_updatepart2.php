<div class="form-group"> 
  <label class="col-md-4 control-label">此處室是否刪除：</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-info-sign"></i></span>
    <a href="<?=config_item('base_url');?>/index.php/Admin/delete_part" class="btn btn-danger" onclick="return confirm('確定要刪除處室嗎？')"><i class="glyphicon glyphicon-trash"></i> 刪除</a>(經刪除，所有組員和該公告將一起清除！)
  </div>
</div>
</div>