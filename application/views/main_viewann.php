
    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <div class="bg-info text-center"><h2><?=$function_name?></h2></div>
    <div class="text-center">
        <a href="<?=config_item('base_url');?>/index.php/Main/go_page/<?php echo $current?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"> 回上一頁</a> <a href="<?=config_item('base_url');?>/index.php/Main" class="btn btn-primary"><span class="glyphicon glyphicon-home"></span> 回首頁</a>
    </div>
    
    <div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">標題</h3>
            </div>
            <div class="panel-body">
                <h3><?=html_escape($this->security->xss_clean($head->subject))?></h3>
            </div>
            <table class="table">
                <tr>
                <th class="col-md-1">單位</th>
                <th class="col-md-6"><?=$this->security->xss_clean($head->partname)?></th>
                <th class="col-md-1">發公告者</th>
                <th class="col-md-1"><a href="mailto:<?=$user->email?>" title="寄信給<?=$this->security->xss_clean($user->realname)?>"><?=$this->security->xss_clean($user->realname)?></a></th>
                <th class="col-md-1">來源</th>
                <th class="col-md-2"><?=$body->ip?></th>
                </tr>
                <tr>
            <th>時間</th>
            <th>
            <?php
                if ($head->firsttime == "0000-00-00 00:00:00")
                {
                    echo $head->posttime;
                } else
                {
                    echo $head->firsttime . "　(最新編修時間：" . $head->posttime . ")";
                }
            ?>
            </th>
            <th>相關網址</th>
            <th><?=$hasurl?></th>
            <th>人氣</th>
            <th><?=$head->hits?></th>
          </tr>
                    <tr>
            <th></th>
            <th>
            
            </th>
            <th>相關附件</th>
            <th><?=$hasfile?></th>
            <th>管理</th>
            <th><a href="<?=config_item('base_url');?>/index.php/Post_ann/modify/<?=$head->tid?>" class="btn btn-warning btn-sm" onclick="return confirm('確定要編輯公告嗎？')"><span class="glyphicon glyphicon-pencil"></span> 編修</a> <a href="<?=config_item('base_url');?>/index.php/Post_ann/delete_ann/<?=$head->tid?>/<?=$head->partid?>/<?=$body->userid?>" class="btn btn-danger btn-sm" onclick="return confirm('確定要刪除公告嗎？')"><span class="glyphicon glyphicon-trash"></span> 刪除</a></th>
          </tr>
            </table>
        </div>
        
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">內文</h3>
            </div>
                <div class="panel-body">
                <?=$this->security->xss_clean(nl2br($body->comment)); ?>
                </div>
        </div>
    </div>
    