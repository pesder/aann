<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<?=form_open('Auth/adminAuth');?>
<table class="table">
<tr>
	<td class="text-center">使用者帳號</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('username')?>
	<?php
	$username_data = array (
		'name'	=>	'username',
		'class'	=>	'form-control');
	echo form_input($username_data);
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
	echo form_password($userpass_data);
	?>
	</div>
	</td>
</tr>

</table>
<div class="text-center">
    <?php
    $but1 = array (
      'name'  =>  'sent',
      'type'  =>  'submit',
      'content' =>  '送出',
      'class' =>  'btn btn-primary',
      'accesskey'	=>	's');
    echo form_button($but1);
    ?> ｜ 
    <a href="<?=config_item('base_url');?>/index.php/Main/" class="btn btn-primary" accesskey="h">回主選單</a>
</div>
<?=form_close()?>