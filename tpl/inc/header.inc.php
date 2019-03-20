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
    $user_nivel_acesso = $_SESSION['user_nivel_acesso'];

    switch ($user_nivel_acesso) {
        // Menu Admin
        case 1:
            $_SESSION['menu_admin'] = "session_menu_admin";
            include_once 'tpl/inc/menus/menu_admin.inc.php';
            break;
        // Menu Recursos Humanos
        case 3:
            $_SESSION['menu_rh'] = "session_menu_rh";
            include_once 'tpl/inc/menus/menu_rh.inc.php';
            break;
        // Menu Coordenadores de Lojas
        case 4:
            $_SESSION['menu_coordenacao'] = "session_menu_coordenacao";
            include_once 'tpl/inc/menus/menu_coordenacao.inc.php';
            break;
        // Menu Controle de Qualidade
        case 5:
            $_SESSION['menu_qualidade'] = "session_menu_qualidade";
            include_once 'tpl/inc/menus/menu_qualidade.inc.php';
            break;
        // Menu Gestão de Contratos
        case 6:
            $_SESSION['menu_contratos'] = "session_menu_contratos";
            include_once 'tpl/inc/menus/menu_contratos.inc.php';
            break;
        // Menu Controle de Produto
        case 7:
            $_SESSION['menu_cotrole_produto'] = "session_menu_cotrole_produto";
            include_once 'tpl/inc/menus/menu_cotrolo_produto.inc.php';
            break;
        // Menu Controle de Produto (Diretores de Zona)
        case 8:
            $_SESSION['menu_cotrole_produto_dz'] = "session_menu_cotrole_produto_dz";
            include_once 'tpl/inc/menus/menu_cotrolo_produto_dz.inc.php';
            break;
        default:
            include_once 'tpl/inc/menus/menu_padrao.inc.php';
    }
    ?>    
    <!-- END HEADER MENU -->
</div>
<!-- END HEADER -->