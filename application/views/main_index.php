    <?php
        $orig_str = array('1.1','2.1','3.1');
        $rep_str = array('普通','重要','急件');
    ?>
    <div class="bg-primary text-center"><h1><?=$site?></h1></div>
    <a href="<?=config_item('base_url');?>/index.php/Feed_ann/feed/atom" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-signal"></span> atom</a> <a href="<?=config_item('base_url');?>/index.php/Feed_ann/feed/rss" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-signal"></span> rss</a>
    <table class="table table-condensed">
    <tr><td>
    <?=form_open('Main/selectPart','class="form-inline"');?>
    <?php
    echo '<div class="form-group">';
    echo form_error('partid');
    echo form_dropdown($partid_data);
    echo form_button($but1);
    echo '</div>';
    ?>
    <?=form_close()?>
    </td>
    <td>
    <?=form_open('Main/searchKeyword','class="form-inline"');?>
    <?php
    echo '<div class="form-group">';
    echo form_label('關鍵字');
    echo form_error('search');
    echo form_input($search);
    echo form_button($but2);
    echo '</div>';
    ?>
    <?=form_close()?>
    </td>
    <td>
    <?=form_open('Main/setDays','class="form-inline"');?>
    <?php
    echo '<div class="form-group">';
    echo form_label('列出');
    echo form_error('ann_list_days');
    echo form_input($days);
    echo form_label('天前公告');
    echo form_button($but3);
    echo '</div>';
    ?>
    <?=form_close()?>
    </td>
    <td>
    <div class="text-right">
    <a href="<?=config_item('base_url');?>/index.php/Post_ann/postAnnForm" class="btn btn-success"><span class="glyphicon glyphicon-comment"> 發布公告</a> 
    <a href="<?=config_item('base_url');?>/index.php/Main/showManage" class="btn btn-warning"><span class="glyphicon glyphicon-cog"> 管理功能</a>
    </div>
    </td></tr>
 </table>
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
            <td><a href="<?=config_item('base_url');?>/index.php/Main/viewAnn/<?=$row->tid?>"><?=html_escape($this->security->xss_clean($row->subject));?></a></td>
            <td><?=$this->security->xss_clean($row->partname)?></td>
            <td><?=$row->posttime?></td>
            <td><?=$row->hits?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      
    </div>