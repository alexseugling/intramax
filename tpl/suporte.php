<?php
if (!isset($_SESSION['login'])) {
    ?>
    <script type="text/javascript">location.href = "<?php setHome(); ?>/login";</script>
    <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/login" /></noscript>
    <?php
    exit;
}
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<style>
    .erro {color: #FF0000;}
    textarea {
        resize: vertical;
        min-height: 60px;
        max-height: 200px;
    }
</style>
<!-- END PAGE LEVEL PLUGINS -->

<?php include 'tpl/inc/head_body.inc.php'; ?>

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
                                    <a href="<?php setHome(); ?>">Início</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Suporte</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php
                                            if (isset($_SESSION['msg_suporte'])) {
                                                echo $_SESSION['msg_suporte'];
                                                unset($_SESSION['msg_suporte']);
                                            }
                                            ?>
                                            <div class="portlet box red-thunderbird ">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-comment"></i> Precisa de Suporte Técnico ou Deseja Relatar um Bug?
                                                    </div>
                                                </div>
                                                <div class="portlet-body form">
                                                    <form class="form-horizontal" role="form" method="post" action="<?php setHome(); ?>/tpl/actions/suporte.php">
                                                        <div class="form-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Nome Completo</label>
                                                                <div class="col-md-9">
                                                                    <input name="nome" type="text" class="form-control" placeholder="Digite o Seu Nome" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Telefone</label>
                                                                <div class="col-md-9">
                                                                    <input name="telefone" type="text" class="form-control phone" placeholder="Digite o Seu Telefone (Somente Números)" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Email</label>
                                                                <div class="col-md-9">
                                                                    <input name="email" type="email" class="form-control" placeholder="Digite o Seu Email" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Assunto da Mensagem</label>
                                                                <div class="col-md-9">
                                                                    <select name="assunto" class="form-control" required>
                                                                        <option></option>
                                                                        <option>Solicitação de Suporte Técnico</option>
                                                                        <option>Desejo Relatar um Problema no Sistema</option>
                                                                        <option>Outros Assuntos</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Mensagem</label>
                                                                <div class="col-md-9">
                                                                    <textarea name="mensagem" class="form-control" placeholder="Digite a Sua Mensagem" required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-actions text-right bold">
                                                            <button type="submit" class="btn blue">Enviar Mensagem</button>
                                                        </div>
                                                    </form>
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
<script type="text/javascript" src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-mask/jquery.mask.min.js"></script>
<script>
//    $('.money').mask("#,##0.00", {reverse: true});
    $('.money').mask("###0.00", {reverse: true});
    $('.phone').mask("000000000", {reverse: true});
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>