<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h1><?=$function_name?></h1></div>
<?php if (!empty($list)) : ?>
<table class="table table-striped">
        <thead>
          <tr>
            <th class="col-md-2">單一登入帳號</th>
            <th class="col-md-2">姓名</th>
            <th class="col-md-2">電子郵件</th>
            <th class="col-md-2">學校單位</th>
            <th class="col-md-2">職務</th>
            <th class="col-md-1"></th>
            <th class="col-md-1"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($list as $row): ?>
          <tr>
            <td><?=$row->openid_id?></td>
            <td><?=$row->fullname?></td>
            <td><?=$row->email?></td>
            <td><?=$row->school_number?></td>
            <td><?=$row->job?></td>
            <td><a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/confirm_newuser/<?=$row->oid?>" class="btn btn-success" onclick="return confirm('確定要編輯使用者嗎？')"><span class="glyphicon glyphicon-pencil"></span> 編輯</a></td>
            <td><a href="<?=config_item('base_url');?>/index.php/<?=$classname?>/delete_oid_user/<?=$row->oid?>" class="btn btn-danger" onclick="return confirm('確定要刪除使用者嗎？')"><span class="glyphicon glyphicon-trash"></span> 刪除</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
<?php endif; ?>
<?php if (!empty($message)) : ?>
<h1><?=$message?></h1>
<?php endif; ?>
<?=$button ?>