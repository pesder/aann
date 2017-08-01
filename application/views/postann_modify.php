    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <div class="bg-info text-center"><h2><?=$function_name?></h2></div>
    <div class="text-center"><p><?php echo "歡迎，" . $user['realname'] . "，您已登入帳號[" . $user['username'] . "]"?></hp1></div>
    <?=form_open_multipart('PostAnn/modify/' . $head->tid);?>
    <div>
      <table class="table table-striped container-fluid">
        <thead>
        </thead>
        <tbody>
        <tr>
            <td  colspan="2"><div class="row"><div class="col-sm-1 text-center">公告等級</div>
            <div class="form-inline">
            <div class="col-sm-10">
            內部公告：</div></div></div></td></tr>
        <tr>
             <td  colspan="2"><div class="form-group row"><div class="col-sm-1">
             <?php 
            	$type_data = array (
	        	'name'	=>	'type',
		        'class'	=>	'form-control',
                'options' => $typelist,
                'value' =>  $head->type
                );
        	echo form_dropdown($type_data);
            ?></div>
            <div class="col-sm-1 text-center">標題：</div>
            <div class="col-sm-8">
            <?=form_error('title')?>
            <?php
                $title_data = array (
	        	'name'	=>	'title',
                'id'    =>  'title',
                'class'	=>	'form-control col-sm-8',
                'value' =>  $head->subject                );
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
                'class'	=>	'form-control',
                'value' =>  $body->comment
                );
                echo form_textarea($comment_data);
            ?></div></div></td>
        </tr>
