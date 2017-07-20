    <?php
        $orig_str = array('1.1','2.1','3.1');
        $rep_str = array('普通','重要','急件');
    ?>
    <?php
    print_r($site);
    echo "<br>";
    print_r($head);
    echo "<br>";
    print_r($body);
    echo "<br>";
    print_r($annurl);
    ?>
    <a href="<?=config_item('base_url');?>/index.php/Main" class="btn btn-primary">回首頁</a>
    <div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th width = "6%">單位</th>
            <th width = "49%"><?=$head->partname ?></th>
            <th width = "10%">發公告者</th>
            <th width = "10%"><?=$realname->realname ?></th>
            <th width = "6%">來源</th>
            <th width = "14%"><?=$body->ip?></th>
          </tr>
            <tr>
            <th>標題</th>
            <th><?=$head->subject ?></th>
            <th>相關網址</th>
            <th><?php print_r($hasurl);?></th>
            <th>人氣</th>
            <th><?=$head->hits?></th>
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
            <th>相關附件</th>
            <th><?php print_r($hasfile);?></th>
            <th>管理</th>
            <th>編修　　刪除</th>
          </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="6">
            <?=nl2br($body->comment) ?>
            </td>
        </tr>
        <?php foreach ($annurl as $row) :?>
        <tr>
            <td colspan="6">
            <a href="<?php echo $row?>">★相關網址：<?php echo $row?></a>
            </td>
        </tr>        
        <?php endforeach; ?>
        <?php foreach ($annfile as $row) :?>
        <tr>
            <td colspan="6">
            <a href="<?=config_item('base_url');?>/index.php/Main/download/<?=$head->partid?>/<?=$body->userid?>/<?php echo $row?>">★相關附件：<?php echo $row?></a>
            </td>
        </tr>        
        <?php endforeach; ?>
        </tbody>
      </table>
      
    </div>