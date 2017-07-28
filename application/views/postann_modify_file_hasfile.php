        <tr>
            <td class="align-middle">
                <div class="text-center">相關附件</div>
                
            </td>
            <td>
            <?php foreach ($annfile as $index => $row) :?>
            <div class="row">
            <div class="col-sm-8">
            <?php if ($filenotthere[$index] == 1):?>
            
            <a href="<?=config_item('base_url');?>/index.php/Main/download/<?=$head->partid?>/<?=$body->userid?>/<?php echo $row?>">★相關附件 <?php echo $index + 1?>：<?php echo $annfilereadable[$index]?></a>
            <?php else:?>
            ★相關附件 <?php echo $index + 1?>：<?php echo $annfilereadable[$index]?>
            <?php endif;?>
            </div>
            <div class="col-sm-1">
            <a href="<?=config_item('base_url');?>/index.php/PostAnn/deleteFile/<?=$head->tid?>/<?=$head->partid?>/<?=$body->userid?>/<?php echo $row?>" class="btn btn-danger">刪除</a>
            </div>
            </div>
            <?php endforeach; ?>
                
                <?php
                    for ($i = 0; $i < $file_empty; $i++) {
                        $j = $i + 1;
                        $name = "附件 " . $j . "︰";
                        $label_att = array ('class' => '');
                        $urlfile_data = array (
                            'name'  =>  'urlfile' . $j,
                            'id'    =>  'urlfile' . $j,
                            'class' =>  ''
                        
                        );
                        echo "<div class=\"row\">";
                        echo "<div class=\"col-sm-1 text-right\">";
                        echo form_label($name, 'name', $label_att);
                        echo "</div>";
                        echo "<div class=\"col-sm-8\">";
                        echo form_upload($urlfile_data);
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
                
                
            </td>
        
        </tr>
