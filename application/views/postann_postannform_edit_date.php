        <tr>
            <td>
                <div class="text-center">公告時效</div>
            </td>
            <td>
                <div class="row">
                <div class="col-sm-2">
                <?php
                        $dueday = new datetime(date('Y-m-d', time()));
                        $offset = '+' . "$annday->configvalue" . "day";
                        $dueday->modify($offset);
                        $date_data = array (
                            'name'  =>  'dueday',
                            'id'    =>  'datepicker',
                            'class' =>  'form-control',
                            'value' =>  $dueday->format('Y-m-d')
                        );
                        echo form_input($date_data);

                ?>
                </div>
                
                    <?php
                    $but1 = array (
                     'name'  =>  'sent',
                      'type'  =>  'submit',
                      'content' =>  '送出公告',
                      'class' =>  'btn btn-primary',
                       'accesskey'	=>	's');
                     echo form_button($but1);
                    ?>
                </div>
            </td>
        
        </tr>
