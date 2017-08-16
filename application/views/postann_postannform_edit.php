    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <div class="bg-info text-center"><h2><?=$function_name?></h2></div>
    <div class="text-center"><p><?php echo "歡迎，" . $user['realname'] . "，您已登入帳號[" . $user['username'] . "]"?></hp1></div>
    <?=form_open_multipart($function_key, 'class="well form-horizontal"');?>
    <div class="form-group">
        <div class="row">
            <div class="col-md-11">
            <div class="form-inline">
                公告等級：
                <?=form_dropdown($type_data);?>連續發公告：<?=form_dropdown($serialpost_data);?>內部公告：<?=form_dropdown($local_data);?>使用 HTML：<?=form_dropdown($html_data);?>
            </div>
            </div>
        </div>    
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-1">
                標題：
            </div>
            <div class="col-md-10">
            <?=form_error('title')?>
            <?=form_input($this->security->xss_clean($title_data));?>
            </div>
        </div>    
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-11">
            <?=form_label('★標題和內容一定要寫！')?>
            <?=form_error('comment')?>
            <?=form_textarea($this->security->xss_clean($comment_data));?>
            </div>
        </div>    
    </div>
