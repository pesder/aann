 <h1 class="bg-primary text-center"><?=$message?></h1>

<div class="container-flud">
    <div class="row">
        <?php if (!empty($userlist)) : ?>
        <?php foreach ($userlist as $index => $name): ?>
        <div class="col-sm-3">
        <a href="<?=config_item('base_url');?>/index.php/Admin/updateMember2/<?=$index?>" class="btn btn-info"><?=$name?></a>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>