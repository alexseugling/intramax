<!-- BEGIN HEADER -->
<div class="page-header">
    <!-- BEGIN HEADER TOP -->
    <div class="page-header-top">
        <div class="container">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?php setHome(); ?>">
                    <img src="<?php setHome(); ?>/tpl/assets/layouts/remax_vantagem/img/logo-default.png" alt="Remax Vantagem" class="logo-default">
                </a>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler"></a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown dropdown-user dropdown-light">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <span class="bold username username-hide-mobile font-blue">Olá <?= $_SESSION['user_primeiro_nome']; ?>!</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?php setHome(); ?>/atualizar-meu-perfil">
                                    <i class="icon-user"></i> Atualizar o Meu Perfil </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    <li class="dropdown dropdown-extended dropdown-tasks dropdown-dark">
                        <a href="<?php setHome(); ?>/tpl/actions/logout.php" class="dropdown-toggle popovers" data-container="body" data-trigger="hover" data-placement="left" data-content="Clique Aqui Para Sair Com Segurança.">
                            <i class="icon-lock"></i>
                        </a>                                            
                    </li>
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- END HEADER TOP -->
    <?php
    $user_id_cargo = $_SESSION['user_id_cargo'];
    if($user_id_cargo == 9){
       include_once 'tpl/inc/menus/menu_webdesigner.inc.php'; 
    }elseif ($user_id_cargo == 6) {
        include_once 'tpl/inc/menus/menu_coordenacao_loja.inc.php'; 
    }elseif ($user_id_cargo == 16 || $user_id_cargo == 9 || $user_id_cargo == 4 || $user_id_cargo == 8 || $user_id_cargo == 10) {
        include_once 'tpl/inc/menus/menu_coordenacao_qualidade.inc.php'; 
    }elseif ($user_id_cargo == 13) {
        include_once 'tpl/inc/menus/menu_coordenacao_rh.inc.php'; 
    } else {
        include_once 'tpl/inc/menus/menu_simple.inc.php'; 
    }
    ?>    
    <!-- END HEADER MENU -->
</div>
<!-- END HEADER -->