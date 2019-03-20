<?php
if (!isset($_SESSION['login'], $_SESSION['menu_coordenacao']) && !isset($_SESSION['menu_admin']) && !isset($_SESSION['menu_qualidade'])) {
    ?>
    <script type="text/javascript">location.href = "<?php setHome(); ?>/tpl/actions/logout.php";</script>
    <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/tpl/actions/logout.php" /></noscript>
    <?php
    exit;
}
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'relatorios';
?>

<div class="page-wrapper">
    <div class="page-wrapper-row">
        <div class="page-wrapper-top">

            <?php include 'tpl/inc/header.inc.php'; ?>

        </div>
    </div>
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h5 class="bold font-blue-dark">
                                    <span id="dataextenso"></span> | <span id="horas"></span>
                                </h5>
                            </div>
                            <!-- END PAGE TITLE -->

                        </div>
                    </div>
                    <!-- END PAGE HEAD-->
                    <!-- BEGIN PAGE CONTENT BODY -->
                    <div class="page-content">
                        <div class="container">
                            <!-- BEGIN PAGE BREADCRUMBS -->
                            <ul class="page-breadcrumb breadcrumb">
                                <li>
                                    <a href="<?php setHome(); ?>/coordenacao">Coordenação</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Relatórios</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light">
                                                <div class="portlet-body">
                                                    <div class="note note-success">
                                                        <h4 class="block bold font-green-steel">Relatórios da Coordenação dos Consultores</h4>
                                                        <p>Selecione uma das opções abaixo.</p>
                                                    </div>
                                                    <div class="row">
                                                        <a href="<?php setHome(); ?>/coordenacao/relatorio-angariacao" class="col-md-2 col-sm-12 col-xs-12">
                                                            <div class="color-demo popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="Relatório de Angariação">
                                                                <div class="color-view bg-blue bg-font-blue bold uppercase"> <i class="fa fa-line-chart fa-2x"></i> <i class="fa fa-plus fa-2x"></i> </div>
                                                                <div class="color-info bg-white c-font-14 sbold hidden-lg hidden-desktop hidden-md">Relatório de Angariação</div>
                                                            </div>
                                                        </a>
                                                        <a href="<?php setHome(); ?>/coordenacao/relatorio-baixa-de-preco" class="col-md-2 col-sm-12 col-xs-12">
                                                            <div class="color-demo popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="Relatório de Baixa de Preço">
                                                                <div class="color-view bg-blue bg-font-blue bold uppercase"> <i class="fa fa-line-chart fa-2x"></i> <i class="fa fa-arrow-down fa-2x"></i> </div>
                                                                <div class="color-info bg-white c-font-14 sbold hidden-lg hidden-desktop hidden-md">Relatório de Baixa de Preço</div>
                                                            </div>
                                                        </a>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PAGE CONTENT INNER -->
                        </div>
                    </div>
                    <!-- END PAGE CONTENT BODY -->
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->                        
            </div>
            <!-- END CONTAINER -->
        </div>
    </div>
    <div class="page-wrapper-row">
        <div class="page-wrapper-bottom">

            <?php include 'tpl/inc/footer.inc.php'; ?>

        </div>
    </div>
</div>

<?php include 'tpl/inc/core_footer.inc.php'; ?>

<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>