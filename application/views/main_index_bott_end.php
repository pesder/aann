<div class="text-center">   <a href="<?=config_item('base_url');?>/index.php/Main/go_page/<?php echo $current - 1 ?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"> 上一頁</a> <a href="<?=config_item('base_url');?>/index.php/Main" class="btn btn-success"><span class="glyphicon glyphicon-home"></span> 回首頁</a> 總公告數： <?php echo $pages['total'] ?>篇公告，<?php echo $pages['pages']?>頁，目前在第 <?php echo $current?> 頁。</div>