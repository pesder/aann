
    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <div class="bg-info text-center"><h2><?=$function_name?></h2></div>
    <div class="text-center">
        <a href="<?=config_item('base_url');?>/index.php/Main/goPage/<?php echo $current?>" class="btn btn-primary">回上一頁</a> <a href="<?=config_item('base_url');?>/index.php/Main" class="btn btn-primary">回首頁</a>
    </div>
    <div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                標題
            </div>
            <div class="panel-body">
                <?=$this->security->xss_clean($head->subject) ?>
            </div>
            <table class="table">
                <tr>
                <th width = "6%">單位</th>
                <th width = "49%"><?=$head->partname ?></th>
                <th width = "10%">發公告者</th>
                <th width = "10%"><a href="mailto:<?=$user->email?>"><?=$user->realname ?></a></th>
                <th width = "6%">來源</th>
                <th width = "14%"><?=$body->ip?></th>
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
            <th><?php print_r($hasurl);?></th>
            <th>人氣</th>
            <th><?=$head->hits?></th>
          </tr>
                    <tr>
            <th></th>
            <th>
            
            </th>
            <th>相關附件</th>
            <th><?php print_r($hasfile);?></th>
            <th>管理</th>
            <th><a href="<?=config_item('base_url');?>/index.php/Post_ann/modify/<?=$head->tid?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"> 編修</a>　　<a href="<?=config_item('base_url');?>/index.php/Post_ann/deleteAnn/<?=$head->tid?>/<?=$head->partid?>/<?=$body->userid?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"> 刪除</a></th>
          </tr>
            </table>
        </div>
        
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                內文
            </div>
                <div class="panel-body">
                <?=$this->security->xss_clean(nl2br($body->comment)); ?>
                </div>
        </div>
    </div>
    