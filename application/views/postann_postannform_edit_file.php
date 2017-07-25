        <tr>
            <td><div class="row">
                <div class="col-sm-2 text-center">相關附件</div>
                </div>
            </td>
            <td>
                <div class="row">
                <?php
                    for ($i = 0; $i < $ulfilenum->configvalue; $i++) {
                        $j = $i + 1;
                        $name = "附件 " . $j . "︰";
                        $label_att = array ('class' => 'col-sm-1');
                        $urlfile_data = array (
                            'name'  =>  'urlfile' . $j,
                            'id'    =>  'urlfile' . $j,
                            'class' =>  'col-sm-8'
                        
                        );
                        echo "<div class=\"col-sm-9\">";
                        echo form_label($name, 'name', $label_att);
                        echo form_upload($urlfile_data);
                         echo "</div>";
                    }
                ?>
                
                </div>
            </td>
        
        </tr>
