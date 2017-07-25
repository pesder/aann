        <tr>
            <td><div class="row">
                <div class="col-sm-2 text-center">公告時效</div>
                </div>
            </td>
            <td>
                <div class="row">
                <div class="col-sm-1">
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
                </div>
            </td>
        
        </tr>
