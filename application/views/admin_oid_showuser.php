<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h1><?=$function_name?></h1></div>
<?php if (!empty($list)) : ?>
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
          <?php foreach ($list as $row): ?>
          <tr>
            <td><?=$row->openid_id?></td>
            <td><?=$row->fullname?></td>
            <td><?=$row->email?></td>
            <td><?=$row->school_number?></td>
            <td><?=$row->job?></td>
            <td><a href="<?=config_item('base_url');?>/index.php/Admin/confirmNewuser/<?=$row->oid?>" class="btn btn-success">編輯</a></td>
            <td><a href="<?=config_item('base_url');?>/index.php/Admin/deleteOidUser/<?=$row->oid?>" class="btn btn-danger">刪除</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
<?php endif; ?>
<?php if (!empty($message)) : ?>
<h1><?=$message?></h1>
<?php endif; ?>
<?php echo $button ?>