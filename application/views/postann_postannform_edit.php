    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <div class="text-center"><p><?php echo "歡迎，" . $user['realname'] . "，您已登入帳號[" . $user['username'] . "]"?></hp1></div>
    <?=form_open('PostAnn/postAnnForm');?>
    <div>
      <table class="table table-striped container-fluid">
        <thead>
        </thead>
        <tbody>
        <tr>
            <td  colspan="2"><div class="row"><div class="col-sm-1 text-center">公告等級</div><div class="col-sm-10">連續發公告：內部公告：</div></div></td></tr>
        <tr>
             <td  colspan="2"><div class="row"><div class="col-sm-1">
             <?php 
            	$type_data = array (
	        	'name'	=>	'type',
		        'class'	=>	'form-control',
                'options' => $typelist);
        	echo form_dropdown($type_data);
            ?></div>
            <div class="col-sm-1 text-center">標題：</div>
            <div class="col-sm-8">
            <?=form_error('title')?>
            <?php
                $title_data = array (
	        	'name'	=>	'title',
                'id'    =>  'title',
                'class'	=>	'form-control'
                );
                echo form_input($title_data);
            ?></div></div></td></tr>
        <tr >
            <td  colspan="2"><div class="row"><div class="col-sm-11">★標題和內容一定要寫！</div></div>
            <div class="row"><div class="col-sm-11">
            <?=form_error('comment')?>
            <?php
                $comment_data = array (
	        	'name'	=>	'comment',
                'id'    =>  'comment',
                'class'	=>	'form-control'
                );
                echo form_textarea($comment_data);
            ?></div></div></td>
        </tr>
