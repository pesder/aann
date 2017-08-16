    <div class="form-group">
        <div class="row">
            <div class="col-md-1">
                相關附件
            </div>
            <div class="col-md-10">
            <?php
                    for ($i = 0; $i < $ulfilenum; $i++) {
                        $j = $i + 1;
                        $name = "附件 " . $j . "︰";
                        $urlfile_data = array (
                            'name'  =>  'urlfile' . $j,
                            'id'    =>  'urlfile' . $j,
                            'class' =>  'btn btn-default'
                        
                        );
                        echo '<div class="row">';
                        echo '<div class="input-group">';
                        echo '<span class="input-group-addon" id="basic-addon1">';
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
