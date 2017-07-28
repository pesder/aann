        <?php foreach ($annfile as $index => $row) :?>
        <tr>
            <td colspan="6">
            <?php if ($filenotthere[$index] == 1):?>
            <a href="<?=config_item('base_url');?>/index.php/Main/download/<?=$head->partid?>/<?=$body->userid?>/<?php echo $row?>">★相關附件 <?php echo $index + 1?>：<?php echo $annfilereadable[$index]?></a>
            <?php else:?>
            ★相關附件 <?php echo $index + 1?>：<?php echo $annfilereadable[$index]?>
            <?php endif;?>
            </td>
        </tr>        
        <?php endforeach; ?>