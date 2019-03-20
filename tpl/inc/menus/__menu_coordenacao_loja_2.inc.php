<?php
session_start();
if (!isset($_SESSION['login'])) {
    echo 'não permitido';
    exit();
}
?>
<!-- BEGIN HEADER MENU -->
<div class="page-header-menu">
    <div class="container">
        <div class="hor-menu hor-menu-light">
            <ul class="nav navbar-nav">
                <li aria-haspopup="true" class="<?php echo ($page == 'home') ? 'active' : ''; ?>">
                    <a href="<?php setHome(); ?>">Início</a>
                </li>
                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown <?php echo ($page == 'coordenacao' || $page == 'nova-angariacao' || $page == 'baixa-de-preco' || $page == 'pesquisar-angariacao' || $page == 'registar-contrato-cliente-comprador' || $page == 'transferir-angariacao' || $page == 'validar-espelho' || $page == 'alerta-correcao-gestor' || $page == 'relatorios' || $page == 'relatorio-angariacao' || $page == 'relatorio-baixa-de-preco' || $page == 'livro-de-registos' || $page == 'atualizar-livro-de-registos' || $page == 'consultar-livro-de-registos') ? 'active' : ''; ?>">
                    <a href="javascript:;">
                        <i class="fa fa-smile-o"></i> Coordenação
                        <span class="arrow"></span>
                    </a>
                    <ul class="dropdown-menu pull-left" style="min-width: 310px">
                        <li <?php echo ($page == 'nova-angariacao') ? 'class="active"' : ''; ?>>
                            <a href="<?php setHome(); ?>/coordenacao/nova-angariacao">
                                <i class="fa fa-plus"></i> Nova Angariação
                            </a>
                        </li>
                        <li <?php echo ($page == 'baixa-de-preco') ? 'class="active"' : ''; ?>>
                            <a href="<?php setHome(); ?>/coordenacao/baixa-de-preco">
                                <i class="fa fa-arrow-down"></i> Baixa de Preço
                            </a>
                        </li>
                        <li <?php echo ($page == 'pesquisar-angariacao') ? 'class="active"' : ''; ?>>
                            <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao">
                                <i class="fa fa-search"></i> Pesquisar Angariação
                            </a>
                        </li>
                        <li <?php echo ($page == 'registar-contrato-cliente-comprador') ? 'class="active"' : ''; ?>>
                            <a href="<?php setHome(); ?>/coordenacao/registar-contrato-cliente-comprador">
                                <i class="fa fa-book"></i> Registar Contrato de Cliente Comprador
                            </a>
                        </li>
                        <li <?php echo ($page == 'transferir-angariacao') ? 'class="active"' : ''; ?>>
                            <a href="<?php setHome(); ?>/coordenacao/transferir-angariacao">
                                <i class="fa fa-exchange"></i> Transferir Angariação
                            </a>
                        </li>
                        <li <?php echo ($page == 'validar-espelho') ? 'class="active"' : ''; ?>>
                            <a href="<?php setHome(); ?>/coordenacao/validar-espelho">
                                <i class="fa fa-envelope"></i> Validar Espelho
                            </a>
                        </li>
                        <li <?php echo ($page == 'alerta-correcao-gestor') ? 'class="active"' : ''; ?>>
                            <a href="<?php setHome(); ?>/coordenacao/alerta-correcao-gestor">
                                <i class="fa fa-bell-o"></i> Alerta para Correção de Gestor
                            </a>
                        </li>
                        <li aria-haspopup="true" class="dropdown-submenu <?php echo ($page == 'relatorios' || $page == 'relatorio-angariacao' || $page == 'relatorio-baixa-de-preco') ? 'active' : ''; ?>">
                            <a href="javascript:;" class="nav-link nav-toggle ">
                                <i class="fa fa-line-chart"></i> Relatórios
                                <span class="arrow"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li aria-haspopup="true" <?php echo ($page == 'relatorio-angariacao') ? 'class="active"' : ''; ?>>
                                    <a href="<?php setHome(); ?>/coordenacao/relatorio-angariacao" class="nav-link "> <i class="fa fa-line-chart"></i> <i class="fa fa-plus"></i> Angariação </a>
                                </li>
                                <li aria-haspopup="true" <?php echo ($page == 'relatorio-baixa-de-preco') ? 'class="active"' : ''; ?>>
                                    <a href="<?php setHome(); ?>/coordenacao/relatorio-baixa-de-preco" class="nav-link "> <i class="fa fa-line-chart"></i> <i class="fa fa-arrow-down"></i> Baixa de Preço </a>
                                </li>
                            </ul>
                        </li>
                        <li aria-haspopup="true" class="dropdown-submenu <?php echo ($page == 'livro-de-registos' || $page == 'atualizar-livro-de-registos' || $page == 'consultar-livro-de-registos') ? 'active' : ''; ?>">
                            <a href="javascript:;" class="nav-link nav-toggle ">
                                <i class="fa fa-book"></i> Livro de Registos
                                <span class="arrow"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li aria-haspopup="true" <?php echo ($page == 'atualizar-livro-de-registos') ? 'class="active"' : ''; ?>>
                                    <a href="<?php setHome(); ?>/coordenacao/atualizar-livro-de-registos" class="nav-link "> <i class="fa fa-book"></i> <i class="fa fa-refresh"></i> Atualizar </a>
                                </li>
                                <li aria-haspopup="true" <?php echo ($page == 'consultar-livro-de-registos') ? 'class="active"' : ''; ?>>
                                    <a href="<?php setHome(); ?>/coordenacao/consultar-livro-de-registos" class="nav-link "> <i class="fa fa-book"></i> <i class="fa fa-search"></i> Consultar </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown ">
                    <a href="javascript:;">
                        <i class="fa fa-calendar"></i> Calendário
                        <span class="arrow"></span>
                    </a>
                    <ul class="dropdown-menu" style="min-width: 210px">
                        <li>
                            <div class="mega-menu-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <a href="#"><i class="fa fa-question"></i> Ítem do Menu</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown ">
                    <a href="javascript:;">
                        <i class="fa fa-envelope"></i> Assinatura de Email
                        <span class="arrow"></span>
                    </a>
                    <ul class="dropdown-menu" style="min-width: 210px">
                        <li>
                            <div class="mega-menu-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="mega-menu-submenu">
                                            <li>
                                                <a href="#"><i class="fa fa-question"></i> Ítem do Menu</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- END MEGA MENU -->
    </div>
</div>