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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'adicionar-equipas';

include_once("tpl/actions/conexao.php");

$consultor = $chefe_equipa = "";
$select = "
    SELECT consultor.id id_consultor, consultor.nome nome_consultor, chefe_equipa.id id_chefe_equipa, chefe_equipa.nome nome_chefe_equipa
    FROM colaboradores consultor
    INNER JOIN colaboradores chefe_equipa ON chefe_equipa.id = consultor.id
    INNER JOIN cargo ON cargo.user = consultor.id
    WHERE consultor.status = 1 AND cargo.tipo_cargo in (1, 2, 18, 19)
    ORDER BY consultor.nome ASC
";
$result = $conn->query($select);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $consultor .= "<option value='{$row['id_consultor']}'>{$row['nome_consultor']}</option> \n"; 
        $chefe_equipa .= "<option value='{$row['id_chefe_equipa']}'>{$row['nome_chefe_equipa']}</option> \n"; 
    }
}
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
                                    <a href="<?php setHome(); ?>">Adicionar Equipas</a>                                            
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-home font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase"> Adicionar Equipas</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['msg_adicionar_equipas'])) {
                                                                echo $_SESSION['msg_adicionar_equipas'];
                                                                unset($_SESSION['msg_adicionar_equipas']);
                                                            }
                                                            ?>
                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/tpl/actions/add_equipas.php" method="post">
                                                                <div class="form-group">
                                                                    <div class="col-md-6">
                                                                        <label class="control-label">Selecione o Consultor <span class="erro">* </span></label>
                                                                        <select name="consultor" class="form-control select2" required>
                                                                            <option value=""></option>
                                                                            <?= $consultor; ?>                                                                        
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="control-label">Selecione o Responsável pela Angariação <span class="erro">* </span></label>
                                                                        <select name="responsavel_angariacao" class="form-control select2" required>
                                                                            <option value=""></option>
                                                                            <?= $chefe_equipa; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="control-label">Nome da Equipa (Aparecerá no Espelho) <span class="erro">* </span></label>
                                                                        <input name="nome_equipa" maxlength="80" class="form-control form-control-inline" type="text" required />
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="control-label">Selecione o Enquadramento <span class="erro">* </span></label>
                                                                        <select name="enquadramento" class="form-control select2" required>
                                                                            <option value=""></option>
                                                                            <option value="1">Equipa</option>
                                                                            <option value="2">Parceria</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="control-label">Contabilização dos Consultores <span class="erro">* </span></label>
                                                                        <input name="contabilizacao_consultores" maxlength="2" class="form-control form-control-inline numeros" type="text" required />
                                                                    </div>
                                                                </div>
                                                                <div class="form-actions margin-top-30">
                                                                    <button name="btn_add_equipas" type="submit" class="btn blue btn-block bold uppercase">Adicionar Consultor</button>
                                                                </div>
                                                            </form>
                                                        </div>
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
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-mask/jquery.mask.min.js"></script>
<script>
    $('.numeros').mask("###", {reverse: true});
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>