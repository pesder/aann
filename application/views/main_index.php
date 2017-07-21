    <?php
        $orig_str = array('1.1','2.1','3.1');
        $rep_str = array('普通','重要','急件');
    ?>
    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th width = "7%">等級</th>
            <th width = "62%">標題</th>
            <th width = "15%">單位</th>
            <th width = "9%">日期</th>
            <th width = "7%">人氣</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($list as $row): ?>
          <tr>
            <td><?=str_replace($orig_str, $rep_str, $row->type)?></td>
            <td><a href="<?=config_item('base_url');?>/index.php/Main/viewAnn/<?=$row->tid?>"><?=$row->subject?></a></td>
            <td><?=$row->partname?></td>
            <td><?=$row->posttime?></td>
            <td><?=$row->hits?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      
    </div>