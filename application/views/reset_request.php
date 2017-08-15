<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
<div class="bg-warning text-center"><h3>請輸入您在系統中使用的帳號與電子郵件，以便發送重設資訊</h3></div>
<?=form_open('Reset/userRequestPassword');?>
<table class="table">
<tr>
	<td class="text-center">發佈公告的帳號：</td>
	<td>
	<div class="col-xs-3">
		<?=form_error('username')?>
	<?=form_input($username_data);	?>
	</div>★
	</td>
</tr>
<tr>
	<td class="text-center">電子信箱：</td>
	<td>
	<div class="col-xs-3">
	<?=form_error('email')?>
	<?=form_input($email_data);?>
	</div>★
	</td>
</tr>
</table>
<div class="text-center">
    <?=form_button($but1);?>
</div>
<?=form_close()?>