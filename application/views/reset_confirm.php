<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<?=form_open('Reset/confirm');?>
<table class="table">
<div class="bg-warning text-center"><h3>歡迎回來，<?=$userdata->realname?>，我們接到您重設密碼的要求，請在下面輸入您的新密碼</h3></div>
<tr>
	<td class="text-center">使用者帳號：</td>
	<td>
	<div class="col-xs-3">
	<?php
	echo form_radio('userid',$userdata->userid , TRUE);
	echo form_label($userdata->username);
	?>
	</div>
	</td>
</tr>
<tr>
	<td class="text-center">發佈消息的密碼：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('userpass')?>
	<?php
		$userpass_data = array (
		'name'	=>	'userpass',
		'class'	=>	'form-control');
	echo form_password($userpass_data);
	?>
	</div>(大小寫有差)
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
    ?>
</div>
<?=form_close()?>