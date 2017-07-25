        <tr>
            <td><div class="row">
                <div class="col-sm-2 text-center">相關連結</div>
                </div>
            </td>
            <td>
                <div class="row">
                <?php
                    for ($i = 0; $i < $urlnum->configvalue; $i++) {
                        $j = $i + 1;
                        $name = "網址 " . $j . "︰";
                        $label_att = array ('class' => 'col-sm-2');
                        $url_data = array (
                            'name'  =>  'url' . $j,
                            'id'    =>  'url' . $j,
                            'class' =>  'col-sm-8'
                        
                        );
                        echo "<div class=\"col-sm-9\">";
                        echo form_label($name, 'name', $label_att);
                        echo form_input($url_data);
                         echo "</div>";
                    }
                ?>
                
                </div>
            </td>
        
        </tr>
