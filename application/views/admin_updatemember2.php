<tr>
	<td class="text-center">此組員是否刪除：</td>
	<td>
	<div class="col-xs-8">
	<a href="<?=config_item('base_url');?>/index.php/Reset/requestPassword" class="btn btn-warning">重設密碼用</a> <a href="<?=config_item('base_url');?>/index.php/Admin/deleteMember" class="btn btn-danger">刪除</a> <a href="<?=config_item('base_url');?>/index.php/Admin/disableMember" class="btn btn-warning">停用</a>
	</div>
	</td>
</tr>
</table>
<div class="text-center">
    <?php
    $but1 = array (
      'name'  =>  'sent',
      'type'  =>  'submit',
      'content' =>  '修改',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin/updateMember1" class="btn btn-primary" accesskey="h">回處室選單</a> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin" class="btn btn-primary" accesskey="h">回管理選單</a>
</div>
<?=form_close()?>