     
        <?php foreach ($annurl as $index => $row) :?>
        <tr>
            <td colspan="6">
            <a href="<?php echo $row?>">★相關網址 <?php echo $index + 1?>：<?php echo $row?></a>
            </td>
        </tr>
        <?php endforeach; ?>