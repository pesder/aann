    <?php
        $orig_str = array('1.1','2.1','3.1');
        $rep_str = array('普通','重要','急件');
    ?>
    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <a href="<?=config_item('base_url');?>/index.php/FeedAnn/feed/atom">atom</a> <a href="<?=config_item('base_url');?>/index.php/FeedAnn/feed/rss">rss</a>
    <?=form_open('Main/searchKeyword','class="form-inline"');?>
    <?php
    $search = array(
      'name'  =>  'search',
      'class' =>  'form-control'
    );
    $but1 = array(
                     'name'  =>  'sent',
                      'type'  =>  'submit',
                      'content' =>  '確定',
                      'class' =>  'btn btn-primary',
                       'accesskey'	=>	's');
    echo form_label('搜尋');
    echo form_error('search');
    echo form_input($search);
    echo form_button($but1);
    ?>
    <?=form_close()?>
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
            <td><a href="<?=config_item('base_url');?>/index.php/Main/viewAnn/<?=$row->tid?>"><?=$this->security->xss_clean($row->subject);?></a></td>
            <td><?=$row->partname?></td>
            <td><?=$row->posttime?></td>
            <td><?=$row->hits?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      
    </div>