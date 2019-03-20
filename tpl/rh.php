<?php
if (!isset($_SESSION['login'], $_SESSION['menu_rh']) && !isset($_SESSION['menu_admin'])) {
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
$page = 'rh';
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
                                    <a href="<?php setHome(); ?>/rh"> RH</a>
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
                                                        <h4 class="block bold font-green-steel">Ferramentas dos Recursos Humanos</h4>
                                                        <p>Selecione uma das opções abaixo.</p>
                                                    </div>
                                                    <div class="row">
                                                        <a href="<?php setHome(); ?>/rh/adicionar-colaborador" class="col-md-2 col-sm-12 col-xs-12">
                                                            <div class="color-demo popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="Adicionar Colaborador">
                                                                <div class="color-view bg-blue bg-font-blue bold uppercase"> <i class="fa fa-user-plus fa-2x"></i> </div>
                                                                <div class="color-info bg-white c-font-14 sbold hidden-lg hidden-desktop hidden-md">Adicionar Colaborador</div>
                                                            </div>
                                                        </a>
                                                        <a href="<?php setHome(); ?>/rh/atualizar-colaborador" class="col-md-2 col-sm-12 col-xs-12">
                                                            <div class="color-demo popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="Atualizar Colaborador">
                                                                <div class="color-view bg-blue bg-font-blue bold uppercase"> <i class="fa fa-refresh fa-2x"></i> </div>
                                                                <div class="color-info bg-white c-font-14 sbold hidden-lg hidden-desktop hidden-md">Atualizar Colaborador</div>
                                                            </div>
                                                        </a>
                                                        <a href="<?php setHome(); ?>/rh/remover-colaborador" class="col-md-2 col-sm-12 col-xs-12">
                                                            <div class="color-demo popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="Remover Colaborador">
                                                                <div class="color-view bg-blue bg-font-blue bold uppercase"> <i class="fa fa-user-times fa-2x"></i> </div>
                                                                <div class="color-info bg-white c-font-14 sbold hidden-lg hidden-desktop hidden-md">Remover Colaborador</div>
                                                            </div>
                                                        </a>     
                                                        <a href="<?php setHome(); ?>/rh/localizar-colaborador" class="col-md-2 col-sm-12 col-xs-12">
                                                            <div class="color-demo popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="Localizar Colaborador">
                                                                <div class="color-view bg-blue bg-font-blue bold uppercase"> <i class="fa fa-search fa-2x"></i> </div>
                                                                <div class="color-info bg-white c-font-14 sbold hidden-lg hidden-desktop hidden-md">Localizar Colaborador</div>
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