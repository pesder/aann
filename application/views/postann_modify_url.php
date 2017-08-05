        <tr>
            <td colspan="2">
            相關連結如要註解請用 "!" 隔開，註解內不可再次包含 '!' 或空白 ' '，否則網址解析會有錯誤
            </td>
        </tr>
        <tr>
            <td>
                <div class="text-center">相關連結</div>

            </td>
            <td>
                
                <?php
                    
                    $countfile = count($annurl);
                    for ($i = 0; $i < $countfile; $i++) {
                        $j = $i + 1;
                        $name = "網址 " . $j . "︰";
                        $label_att = array ('class' => '');
                        $url_data = array (
                            'name'  =>  'url' . $j,
                            'id'    =>  'url' . $j,
                            'class' =>  'form-control',
                            'value' =>  $annurl[$i]
                        
                        );
                        echo "<div class=\"row\">";
                        echo "<div class=\"col-sm-1 text-right\">";
                        echo form_label($name, 'name', $label_att);
                        echo "</div>";
                        echo "<div class=\"col-sm-8\">";
                        echo form_input($url_data);
                        echo "</div>";
                        echo "</div>";
                         
                    }
                    for ($i = $countfile; $i < $urlnum; $i++) {
                        $j = $i + 1;
                        $name2 = "網址 " . $j . "︰";
                        $label_att2 = array ('class' => '');
                        $url2_data = array (
                            'name'  =>  'url' . $j,
                            'id'    =>  'url' . $j,
                            'class' =>  'form-control'
                        );
                        echo "<div class=\"row\">";
                        echo "<div class=\"col-sm-1 text-right\">";
                        echo form_label($name2, 'name', $label_att2);
                        echo "</div>";
                        echo "<div class=\"col-sm-8\">";
                        echo form_input($url2_data);
                        echo "</div>";
                        echo "</div>";
                         
                    }
                ?>
                
                
            </td>
        
        </tr>
