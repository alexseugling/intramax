<?php
session_start();
ini_set('display_errors', 0);
require_once('dts/ini.php');
require_once('dts/get.php');
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>Intramax | Remax Vantagem</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Intranet Remax Vantagem" name="description" />
        <meta content="Alex Ricardo Seugling" name="author" />
        <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
        <link rel="shortcut icon" href="<?php setHome(); ?>/tpl/assets/layouts/remax_vantagem/img/favicon.ico" />        
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php setHome(); ?>/tpl/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php setHome(); ?>/tpl/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php setHome(); ?>/tpl/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php setHome(); ?>/tpl/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php setHome(); ?>/tpl/assets/layouts/remax_vantagem/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php setHome(); ?>/tpl/assets/layouts/remax_vantagem/css/themes/blue-steel.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php setHome(); ?>/tpl/assets/layouts/remax_vantagem/css/custom.min.css" rel="stylesheet" type="text/css" />
        <style>
            .erro {color: #FF0000;}
            textarea {
                resize: vertical;
                min-height: 80px;
                max-height: 300px;
            }
        </style>
        <!-- END THEME LAYOUT STYLES -->

        <?php getHome(); ?>