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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<style>
    .error{
        color:#D70810;
    }
</style>
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'registar-contrato-cliente-comprador';

include_once 'tpl/actions/conexao.php';

$user_store_code = strtoupper($_SESSION['user_store_code']);
$user_id_loja = $_SESSION['user_id_loja'];
$year = date('Y');
$nc = "";

$sql_nc = "SELECT livro_registo.num_contrato nc FROM livro_registo INNER JOIN id_angariacao ON id_angariacao.angariacao_id = livro_registo.angariacao_id WHERE id_angariacao.loja = $user_id_loja AND INSTR(livro_registo.num_contrato,'{$year}') > 0 ORDER BY livro_registo.id DESC LIMIT 1";
$result_nc = $conn->query($sql_nc);
if ($result_nc->num_rows > 0) {
    while ($row_nc = $result_nc->fetch_assoc()) {
        $nc_full = $row_nc['nc'];
    }
    $nc_exp = explode('/', $nc_full);
    $nc_int = intval(next($nc_exp));
    $nc_last = intval(end($nc_exp));

    $nc = $user_store_code . "/" . ($nc_int + 1) . "/{$year}";
} else {
    $nc = "$user_store_code/1/{$year}";
}
// SELECT OPTIONS CONSULTORES
$sql_cons = "
    SELECT consultor.id id_consultor, consultor.nome nome_consultor
    FROM colaboradores consultor
    INNER JOIN user_loja ON user_loja.user = consultor.id and user_loja.loja = $user_id_loja
    INNER JOIN cargo ON cargo.user = consultor.id and cargo.tipo_cargo in (1,2,18,19)
    WHERE consultor.status = 1
    ORDER BY consultor.nome ASC
";
$result_cons = $conn->query($sql_cons);
if ($result_cons->num_rows > 0) {
    while ($row_cons = $result_cons->fetch_assoc()) {
        $id_cons = $row_cons['id_consultor'];
        $nome_cons = $row_cons['nome_consultor'];
        $opt_consultores_loja .= "\n<option value='$id_cons'>$nome_cons</option>";
    }
} else {
    $opt_consultores_loja = '<option>Erro no sistema, informe o Marketing</option>';
}

// SELECT OPTIONS LISTA DE FREGUESIAS
$sql_freg = "
    SELECT freglist.id freglist_id, freglist.nome freglist_nome FROM lista_freguesia freglist
";
$result_freg = $conn->query($sql_freg);
if ($result_freg->num_rows > 0) {
    while ($row_freg = $result_freg->fetch_assoc()) {
        $freglist_id = $row_freg['freglist_id'];
        $freglist_nome = $row_freg['freglist_nome'];
        $opt_freguesias .= "\n<option value='$freglist_id'>$freglist_nome</option>";
    }
} else {
    $opt_freguesias = '<option>Erro no sistema, informe o Marketing</option>';
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
                                    <a href="<?php setHome(); ?>/coordenacao">Coordenação</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Registar Contrato de Cliente Comprador</span>
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
                                                        <i class="fa fa-book font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase"> Registar Contrato de Cliente Comprador</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['msg_cliente_comprador'])) {
                                                                echo $_SESSION['msg_cliente_comprador'];
                                                                unset($_SESSION['msg_cliente_comprador']);
                                                            }
                                                            ?>
                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/tpl/actions/add_cliente_comprador.php" method="post">
                                                                <div class="form-group ">
                                                                    <div class="col-md-3 margin-bottom-15">
                                                                        <label class="control-label">Nº do Contrato <span class="error">*</span></label>
                                                                        <div>
                                                                            <input type="text" class="form-control" name="num_contrato" value="<?= $nc; ?>" readonly required />
                                                                        </div>                                              
                                                                    </div>
                                                                    <div class="col-md-3 margin-bottom-15">
                                                                        <label class="control-label">Data do Contrato <span class="error">*</span></label>
                                                                        <div>
                                                                            <input name="data_contrato" class="form-control form-control-inline input-medium date-picker" size="16" type="text" required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 margin-bottom-15">
                                                                        <label class="control-label">Estado do Contrato <span class="error">*</span></label>
                                                                        <select name="estado_contrato" class="form-control" required>
                                                                            <option value="1">Activo</option>
                                                                        </select>                                             
                                                                    </div>
                                                                    <div class="col-md-3 margin-bottom-15">
                                                                        <label class="control-label">Regime <span class="error">*</span></label>
                                                                        <select name="tipo_regime" class="form-control" required>
                                                                            <option value="1">Exclusivo</option>
                                                                        </select>                                             
                                                                    </div>
                                                                </div>
                                                                <div class="form-group ">
                                                                    <div class="col-md-3 margin-bottom-15">
                                                                        <label class="control-label">Tipo do Negócio <span class="error">*</span></label>
                                                                        <select name="tipo_negocio" class="form-control" required>
                                                                            <option value="4">Compra</option>
                                                                        </select>                                             
                                                                    </div>
                                                                    <div class="col-md-3 margin-bottom-15">
                                                                        <label class="control-label">Duração do Contrato <span class="error">*</span> (em dias)</label>
                                                                        <div>
                                                                            <input type="text" maxlength="3" class="form-control numeros" name="dur_contrato" required />
                                                                        </div>                                              
                                                                    </div>
                                                                    <div class="col-md-6 margin-bottom-15">
                                                                        <label class="control-label">Consultor <span class="error">*</span></label>
                                                                        <select name="consultor" class="form-control select2" required>
                                                                            <option value=""></option>
                                                                            <?= $opt_consultores_loja; ?>
                                                                        </select>                                             
                                                                    </div>                                                                    
                                                                </div>
                                                                <div class="form-group ">
                                                                    <div class="col-md-12 margin-bottom-15">
                                                                        <label class="control-label">Nome do Cliente <span class="error">*</span></label>
                                                                        <div>
                                                                            <input type="text" class="form-control" name="nome_cliente" required />
                                                                        </div>                                              
                                                                    </div>                                                                    
                                                                </div>
                                                                <div class="form-group ">
                                                                    <div class="col-md-3 margin-bottom-15">
                                                                        <label class="control-label">NIF do Cliente</label>
                                                                        <div>
                                                                            <input type="text" maxlength="9" class="form-control" name="nif_cliente" />
                                                                        </div>                                              
                                                                    </div>
                                                                    <div class="col-md-6 margin-bottom-15">
                                                                        <label class="control-label">Email do Cliente</label>
                                                                        <div>
                                                                            <input type="text" class="form-control" name="email_cliente" />
                                                                        </div>                                              
                                                                    </div>
                                                                    <div class="col-md-3 margin-bottom-15">
                                                                        <label class="control-label">Telefone do Cliente</label>
                                                                        <div>
                                                                            <input type="text" maxlength="9" class="form-control" name="telefone_cliente" />
                                                                        </div>                                              
                                                                    </div>
                                                                </div>
                                                                <div class="form-group ">
                                                                    <div class="col-md-6 margin-bottom-15">
                                                                        <label class="control-label">Nome da Rua/Avenida <span class="error">*</span></label>
                                                                        <div>
                                                                            <input type="text" class="form-control" name="arruamento_cliente" required />
                                                                        </div>                                              
                                                                    </div>
                                                                    <div class="col-md-2 margin-bottom-15">
                                                                        <label class="control-label">Número <span class="error">*</span></label>
                                                                        <div>
                                                                            <input type="text" class="form-control" name="num_porta" required />
                                                                        </div>                                              
                                                                    </div>
                                                                    <div class="col-md-2 margin-bottom-15">
                                                                        <label class="control-label">Andar</label>
                                                                        <div>
                                                                            <input type="text" class="form-control" name="complemento" />
                                                                        </div>                                              
                                                                    </div>
                                                                    <div class="col-md-2 margin-bottom-15">
                                                                        <label class="control-label">Código Postal <span class="error">*</span></label>
                                                                        <div>
                                                                            <input maxlength="8" type="text" class="form-control cep" name="cod_postal" required />
                                                                        </div>                                              
                                                                    </div>
                                                                </div>
                                                                <div class="form-group ">
                                                                    <div class="col-md-12 margin-bottom-15">
                                                                        <label class="control-label">Freguesia do Cliente <span class="error">*</span></label>
                                                                        <select name="freguesia" class="form-control select2" required>
                                                                            <option value=""></option>
                                                                            <?= $opt_freguesias; ?>
                                                                        </select>                                              
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-md-12 text-center margin-bottom-15">
                                                                        <button type="submit" name="finalizar" class="btn green-meadow btn-block bold uppercase">Adicionar Cliente Comprador</button>
                                                                    </div>
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
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datepicker({
        format: "dd/mm/yyyy",
        weekStart: 0,
        language: "pt",
        autoclose: true
    });
</script>
<script type="text/javascript" src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-mask/jquery.mask.min.js"></script>
<script>
//    $('.money').mask("#,##0.00", {reverse: true});
//    $('.money').mask("###0.00", {reverse: true});
//    $('.money').mask("#.##0,00", {reverse: true});
    $('.money').mask("#########################################################.##0", {reverse: true});
    $('.comissao').mask("#.##0,00", {reverse: true});
    $('.nif').mask("000000000", {reverse: true});
    $('.phone').mask("0000000000000000", {reverse: true});
    $('.cep').mask("0000-000", {reverse: true});
    $('.numeros').mask("###", {reverse: true});
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>