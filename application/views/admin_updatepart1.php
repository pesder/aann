<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/updatePart1');?>
<table class="table">
<tr>
	<td class="text-center">選擇要修改的處室</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('partid')?>
	<?php echo form_dropdown($partid_data);	?>
	</div>
	</td>
</tr>

</table>
<div class="text-center">
    <?php echo form_button($but1);?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin" class="btn btn-primary" accesskey="h">回管理選單</a>
</div>
<?=form_close()?>