<?=form_open('Auth/postAnnAuth');?>
<table class="table">
<tr>
	<td class="text-center">使用者帳號</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('username')?>
	<?php
	$username_data = array (
		'name'	=>	'username',
		'class'	=>	'form-control',
		'value'	=>	$s_date,
		'placeholder'	=> '選擇日期');
	echo form_input($username_datadate_data);
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">密碼</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('userpass')?>
	<?php
		$userpass_data = array (
		'name'	=>	'userpass',
		'class'	=>	'form-control');
	echo form_input($userpass_data);
	?>
	</div>
	</td>
</tr>

</table>
    <?php
    $but1 = array (
      'name'  =>  'sent',
      'type'  =>  'submit',
      'content' =>  '送出',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Control/" class="btn btn-primary" accesskey="h">回主選單</a>
<?=form_close()?>