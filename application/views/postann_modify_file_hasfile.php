    <div class="form-group">
        <div class="row">
            <div class="col-md-1">
                相關附件
            </div>
            <div class="col-md-9">
            <?php foreach ($annfile as $index => $row) :?>
            <div class="row">
                <div class="input-group">
            <?php if ($filenotthere[$index] == 1):?>
                <span class="input-group-addon">
            <a href="<?=config_item('base_url');?>/index.php/Main/download/<?=$head->partid?>/<?=$body->userid?>/<?=$row?>">★相關附件 <?php echo $index + 1?>：
                </span>
            <div class="form-control"><?=$annfilereadable[$index]?></a></div>
            <?php else:?>
                <span class="input-group-addon">★相關附件 <?php echo $index + 1?>：
                </span>
            <div class="form-control"><?=$annfilereadable[$index]?></div>
            <?php endif;?>
                <span class="input-group-btn">
            <a href="<?=config_item('base_url');?>/index.php/Post_ann/deleteFile/<?=$head->tid?>/<?=$head->partid?>/<?=$body->userid?>/<?=$row?>" class="btn btn-danger">刪除</a>
                </span>
                    
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
                            'class' =>  'btn btn-default'
                        );
                        echo '<div class="row">';
                        echo '<div class="input-group">';
                        echo '<span class="input-group-addon">';
                        echo $name;
                        echo '</span>';
                        echo form_upload($urlfile_data);
                        echo '</div>';
                        echo "</div>";
                    }
                    ?>
            </div>
        </div>    
    </div>