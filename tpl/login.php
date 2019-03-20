<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="<?php setHome(); ?>/tpl/assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

</head>
<!-- END HEAD -->

<body class=" login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="<?php setHome(); ?>/login">
            <img src="<?php setHome(); ?>/tpl/assets/layouts/remax_vantagem/img/logo-default-white.png" alt="Remax Vantagem" />
        </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <form class="login-form" action="<?php setHome(); ?>/tpl/actions/login.php" method="post">
            <h3 class="form-title font-green">Sejam Bem-Vindos!</h3>
            <?php
            if (isset($_SESSION['msn_login'])) {
                echo $_SESSION['msn_login'];
                unset($_SESSION['msn_login']);
            }
            ?>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Email</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Digite o Seu Email Remax" name="email" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Senha</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Digite a Sua Senha" name="pass" /> </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-block green uppercase">Entrar</button>
            </div>
            <div class="create-account">
                <p>
                    <a href="javascript:;" id="forget-password" class="uppercase">Esqueceu a Senha?</a>
                </p>
            </div>
        </form>
        <!-- END LOGIN FORM -->
        <!-- BEGIN FORGOT PASSWORD FORM -->
        <form class="forget-form" action="<?php setHome(); ?>/tpl/actions/recuperar_acesso.php" method="post">
            <h3 class="font-green">Esqueceu a Senha?</h3>
            <p>Digite o Seu Email Remax e receberá um email com um link para recuperar o vosso acesso. </p>
            <div class="form-group">
                <input class="form-control placeholder-no-fix" type="email" autocomplete="off" placeholder="Digite o Seu Email Remax" name="recovery_email" /> </div>
            <div class="form-actions">
                <button type="button" id="back-btn" class="btn green btn-outline">Voltar</button>
                <button type="submit" class="btn btn-success uppercase pull-right">Recuperar Acesso</button>
            </div>
        </form>
        <!-- END FORGOT PASSWORD FORM -->        
    </div>
    <!-- BEGIN COPYRIGHT -->
    <div class="copyright"><?= date("Y"); ?> &copy; Remax Vantagem</div>
    <!-- END COPYRIGHT -->

    <?php include 'tpl/inc/core_footer.inc.php'; ?>

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?php setHome(); ?>/tpl/assets/pages/scripts/login.min.js" type="text/javascript"></script>
    <script src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="<?php setHome(); ?>/tpl/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
</body>

</html>