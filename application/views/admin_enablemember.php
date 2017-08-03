<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Admin/enableMember');?>
<table class="table">
<tr>
	<td class="text-center">選擇要歸入哪個處室：</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('partid')?>
	<?php
	$partid_data = array (
		'name'	=>	'partid',
		'class'	=>	'form-control',
		'options'	=>	$options
	);
	echo form_dropdown($partid_data);
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">所有目前停用的組員：</td>
	<td>
	<div class="col-xs-3">
	<?php if (!empty($userdata)) : ?>
	<?php foreach($userdata as $index => $name) : ?>
	<div class="row">
	<?php echo form_radio('userid', $index , FALSE);?>
	<?php echo form_label($name);?>
	</div>
	<?php endforeach; ?>
	<?php endif;?>
	</div>
	</td>
</tr>

</table>
<div class="text-center">
    <?php
    $but1 = array (
      'name'  =>  'sent',
      'type'  =>  'submit',
      'content' =>  '啟用選擇的組員',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Admin" class="btn btn-primary" accesskey="h">回管理選單</a>
</div>
<?=form_close()?>