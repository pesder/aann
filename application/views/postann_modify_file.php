        <tr>
            <td class="align-middle">
                <div class="text-center">相關附件</div>
                
            </td>
            <td>
                
                <?php
                    for ($i = 0; $i < $ulfilenum->configvalue; $i++) {
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
