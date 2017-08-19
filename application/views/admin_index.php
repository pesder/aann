<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h1><?=$function_name?></h1></div>
<h1><?=$h1?></h1>
<div class="text-right"><a href="<?=$classname?>/logout" class="btn btn-warning"><span class="glyphicon glyphicon-logout"> 登出並回到首頁</a></div>
<?php foreach ($h1group as $index => $name): ?>
<a href="<?=config_item('base_url');?>/index.php<?=$index?>" class="btn btn-info"><?=$name?></a> 
<?php endforeach; ?>
<h1><?=$h2?></h1>
<?php foreach ($h2group as $index => $name): ?>
<a href="<?=config_item('base_url');?>/index.php<?=$index?>" class="btn btn-info"><?=$name?></a> 
<?php endforeach; ?>
<h1><?=$h3?></h1>
<?php foreach ($h3group as $index => $name): ?>
<a href="<?=config_item('base_url');?>/index.php<?=$index?>" class="btn btn-info"><?=$name?></a> 
<?php endforeach; ?>
<?php if (!empty($newuser)) : ?>
<h1>新進 Openid</h1>
<table class="table table-striped">
        <thead>
          <tr>
            <th>單一登入帳號</th>
            <th>姓名</th>
            <th>電子郵件</th>
            <th>學校單位</th>
            <th>職務</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($newuser as $row): ?>
          <tr>
            <td><?=$row->openid_id?></td>
            <td><?=$row->fullname?></td>
            <td><?=$row->email?></td>
            <td><?=$row->school_number?></td>
            <td><?=$row->job?></td>
            <td><a href="<?=config_item('base_url');?>/index.php/Admin/confirmNewuser/<?=$row->oid?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"> 編輯</a></td>
            <td><a href="<?=config_item('base_url');?>/index.php/Admin/deleteOidUser/<?=$row->oid?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"> 刪除</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

<?php endif; ?>
<?php if (!empty($message)) : ?>
<h1><?=$message?></h1>
<?php endif; ?>