<tr>
	<td class="text-center">此組員是否刪除：</td>
	<td>
	<div class="col-xs-8">
	<a href="<?=config_item('base_url');?>/index.php/Reset/requestPassword" class="btn btn-warning">重設密碼用</a> <a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/deleteMember" class="btn btn-danger">刪除</a> <a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/disableMember" class="btn btn-warning">停用</a>
	</div>
	</td>
</tr>
</table>
<div class="text-center">
    <?php echo form_button($but1);?> ｜ 
    <?php echo $button ?>
</div>
<?=form_close()?>