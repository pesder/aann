<div class="bg-primary text-center"><h1><?=$site?></h1></div>
<div class="bg-info text-center"><h2><?=$function_name?></h2></div>
    <div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title"></h2>
            </div>
            <div class="panel-body">
            <div class="text-center">
                <?=$but_Admin?>
            </div>    
            </div>
        </div>
    </div>
    <div class="text-center">
    <?=$button ?>
    </div>
    <?if ( ! empty($message)):?>
    <h2><?=$message?></h2>
    <? endif;?>