    <div class="form-group">
        <div class="row">
            <div class="col-md-11">
            相關連結如要註解請用 "!" 隔開，註解內不可再次包含 '!' 或空白 ' '，否則網址解析會有錯誤
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
                相關連結
            </div>
            <div class="col-md-10">
            <?php
                    $countfile = count($annurl);
                    for ($i = 0; $i < $countfile; $i++) {
                        $j = $i + 1;
                        $name = "網址 " . $j . "︰";
                        $url_data = array (
                            'name'  =>  'url' . $j,
                            'id'    =>  'url' . $j,
                            'class' =>  'form-control',
                            'value' =>  $annurl[$i]
                        );
                        echo '<div class="row">';
                        echo '<div class="input-group">';
                        echo '<span class="input-group-addon">';
                        echo $name;
                        echo '</span>';
                        echo form_input($url_data);
                        echo '</div>';
                        echo "</div>";
                    }
                    for ($i = $countfile; $i < $urlnum; $i++) {
                        $j = $i + 1;
                        $name2 = "網址 " . $j . "︰";
                        $url2_data = array (
                            'name'  =>  'url' . $j,
                            'id'    =>  'url' . $j,
                            'class' =>  'form-control'
                        );
                        echo '<div class="row">';
                        echo '<div class="input-group">';
                        echo '<span class="input-group-addon">';
                        echo $name2;
                        echo '</span>';
                        echo form_input($url2_data);
                        echo '</div>';
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
    </div>    