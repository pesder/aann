     <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                相關附件
            </div>
            <ul class="list-group">
                <?php foreach ($annfile as $index => $row) :?>
                <?php if ($filenotthere[$index] == 1):?>
                <li class="list-group-item"><a href="<?=config_item('base_url');?>/index.php/Main/download/<?=$head->partid?>/<?=$body->userid?>/<?php echo $row?>">★相關附件 <?php echo $index + 1?>：<?php echo $annfilereadable[$index]?></a></li>
                <?php else:?>
                <li class="list-group-item">★相關附件 <?php echo $index + 1?>：<?php echo $annfilereadable[$index]?></li>
                <?php endif;?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>