    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <div class="text-center"><p><?php echo "歡迎，" . $user['realname'] . "，您已登入帳號[" . $user['username'] . "]"?></hp1></div>
    <?=form_open('PostAnn/postAnnForm');?>
    <div>
      <table class="table table-striped">
        <thead>
        </thead>
        <tbody>
        <tr>
            <td class="container-fluid"><div class="row"><div class="col-sm-6">公告等級</div></div>
            <div class="row"><div class="col-sm-3">
            <?php 
            	$type_data = array (
	        	'name'	=>	'type',
		        'class'	=>	'form-control',
                'options' => $typelist);
        	echo form_dropdown($type_data);
            ?>
            </div></div>
            </td>
            <td class="container-fluid"><div class="row"><div class="col-sm-12">連續發公告：內部公告：</div></div>
            <div class="row"><div class="col-sm-2">標題：</div><div class="col-sm-10"></div></div></td>
        </tr>
        <tr>
            <td colspan="2">★標題和內容一定要寫！</td>
        </tr>
        </tbody>
      </table>
      
    </div>

<div class="text-center">   <a href="<?=config_item('base_url');?>/index.php/Main/Auth/postAnnAuth" class="btn btn-primary">回到帳號密碼認證頁</a> </div>
    <?=form_close()?>