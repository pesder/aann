        <?php foreach ($annfile as $row) :?>
        <tr>
            <td colspan="6">
            <a href="<?=config_item('base_url');?>/index.php/Main/download/<?=$head->partid?>/<?=$body->userid?>/<?php echo $row?>">★相關附件：<?php echo $row?></a>
            </td>
        </tr>        
        <?php endforeach; ?>