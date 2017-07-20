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
    ?>
    <a href="<?=config_item('base_url');?>/index.php/Main" class="btn btn-primary">回首頁</a>
    <div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th width = "6%">單位</th>
            <th width = "49%"><?=$head->partname ?></th>
            <th width = "10%">發公告者</th>
            <th width = "10%"></th>
            <th width = "6%">來源</th>
            <th width = "14%"><?php
            if(filter_var($body->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            {
               echo preg_replace('/(?!\d{1,3}\.\d{1,3}\.)\d/', '?', $body->ip);
            } elseif (filter_var($body->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
            {
                echo preg_replace('/(?!\d{1,4}\:)\d/', '?', $body->ip);
            }

            ?></th>
          </tr>
            <tr>
            <th>標題</th>
            <th><?=$head->subject ?></th>
            <th>相關網址</th>
            <th><?=$body->url?></th>
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
            <th></th>
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
        </tbody>
      </table>
      
    </div>