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
<link href="<?php setHome(); ?>/tpl/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />
<style>
    .erro {color: #FF0000;}
    textarea {
        resize: vertical;
        min-height: 60px;
        max-height: 200px;
    }
</style>
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'atualizar-meu-perfil';
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
                                    <a href="<?php setHome(); ?>">Início</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Atualizar o Meu Perfil</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- BEGIN PROFILE CONTENT -->
                                        <div class="profile-content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="portlet light ">
                                                        <div class="portlet-title tabbable-line">
                                                            <div class="caption caption-md">
                                                                <i class="icon-globe theme-font hide"></i>
                                                                <span class="caption-subject font-blue-madison bold uppercase">A Minha Conta</span>
                                                            </div>
                                                            <ul class="nav nav-tabs">
                                                                <li class="<?= $_SESSION['act_info']; ?>">
                                                                    <a href="#personal_info" data-toggle="tab">Informações Pessoais</a>
                                                                </li>
                                                                <li class="<?= $_SESSION['act_pass']; ?>">
                                                                    <a href="#change_password" data-toggle="tab">Alterar Senha</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="tab-content">
                                                                <!-- PERSONAL INFO TAB -->
                                                                <div class="tab-pane <?= $_SESSION['act_info']; ?>" id="personal_info">
                                                                    <div class="note note-info font-blue-steel">
                                                                        <h4 class="block bold">Confirme as Suas Informações</h4>
                                                                        <p class="font-red-thunderbird">Se alguma informação estiver em branco ou incorreta, <span class="bold">informe os Recursos Humanos</span> </p>
                                                                    </div>
                                                                    <form role="form" class="margin-bottom-40">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Nome</label>
                                                                            <input type="text" placeholder="<?= $_SESSION['user_nome']; ?>" class="form-control" readonly />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Email</label>
                                                                            <input type="text" placeholder="<?= $_SESSION['user_email']; ?>" class="form-control" readonly />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Telefone</label>
                                                                            <input type="text" placeholder="<?= $_SESSION['user_fone']; ?>" class="form-control" readonly />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Loja</label>
                                                                            <input type="text" placeholder="<?= $_SESSION['user_nome_loja']; ?>" class="form-control" readonly />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Cargo</label>
                                                                            <input type="text" placeholder="<?= $_SESSION['user_nome_cargo']; ?>" class="form-control" readonly />
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Gestor</label>
                                                                            <input type="text" placeholder="<?= $_SESSION['user_nome_gestor']; ?>" class="form-control" readonly />
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <!-- END PERSONAL INFO TAB -->
                                                                <!-- CHANGE PASSWORD TAB -->
                                                                <div class="tab-pane <?= $_SESSION['act_pass']; ?>" id="change_password">
                                                                    <?php
                                                                    if (isset($_SESSION['msn_new_pass'])) {
                                                                        echo $_SESSION['msn_new_pass'];
                                                                        unset($_SESSION['msn_new_pass']);
                                                                        unset($_SESSION['act_pass']);
                                                                        $_SESSION['act_info'] = "active";
                                                                    }
                                                                    ?>
                                                                    <form method="post" action="<?php setHome(); ?>/tpl/actions/alterar_senha.php">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Senha Atual</label>
                                                                            <input type="password" name="senha_atual" class="form-control" /> </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Nova Senha</label>
                                                                            <input type="password" name="nova_senha" class="form-control" /> </div>
                                                                        <div class="form-group">
                                                                            <label class="control-label">Confirme a Nova Senha</label>
                                                                            <input type="password" name="conf_nova_senha" class="form-control" /> </div>
                                                                        <div class="margin-top-10">
                                                                            <button type="submit" class="btn green"> Alterar Senha </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <!-- END CHANGE PASSWORD TAB -->                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END PROFILE CONTENT -->
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