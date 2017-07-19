<!DOCTYPE html>
<html lang="zh-Hant-TW">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo config_item('web_title'); ?></title>
    <!-- Bootstrap -->
    <link href="<?php echo config_item('base_url'); ?>/plugin/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo config_item('base_url'); ?>/plugin/css/jquery-ui.css" rel="stylesheet">
    <script src="<?php echo config_item('base_url'); ?>/plugin/js/bootstrap.min.js"></script>
    <script src="<?php echo config_item('base_url'); ?>/plugin/js/jquery-3.1.0.min.js"></script>
    <script src="<?php echo config_item('base_url'); ?>/plugin/js/jquery-ui.min.js"></script>
            <script type="text/javascript">
            $(function() {
                $("#datepicker").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $("#datepicker2").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $("#datepicker3").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            });
        </script>
  </head>

  <body>