<?php
if (!isset($_SESSION['login'], $_SESSION['menu_cotrole_produto']) && !isset($_SESSION['menu_admin'])) {
    ?>
    <script type="text/javascript">location.href = "<?php setHome(); ?>/tpl/actions/logout.php";</script>
    <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/tpl/actions/logout.php" /></noscript>
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
$page = 'editar-produto';
$url_controlo_produto_id = $url['2'];
$url_controlo_produto_consultor = $url['3'];
$url_controlo_produto_estado = $url['4'];
$user_id_loja = $_SESSION['user_id_loja'];
$by_user = $_SESSION['user_id'];
include_once("tpl/actions/conexao.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controlo_produto_id = filter_input(INPUT_POST, 'controlo_produto_id', FILTER_SANITIZE_NUMBER_INT);
    $controlo_produto_consultor = filter_input(INPUT_POST, 'controlo_produto_consultor', FILTER_SANITIZE_NUMBER_INT);
    $controlo_produto_estado = filter_input(INPUT_POST, 'controlo_produto_estado', FILTER_SANITIZE_NUMBER_INT);
    if (empty($controlo_produto_id) || empty($controlo_produto_consultor) || empty($controlo_produto_estado)) {
        $_SESSION['msg_editar_produto'] = "<div class='note note-danger bold font-red-intense'><p>Sistema de Proteção Anti-Hacker Ativado</p></div>";
    } else {
        $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_NUMBER_INT);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
        $pre_angariacao = filter_input(INPUT_POST, 'pre_angariacao', FILTER_SANITIZE_NUMBER_INT);
        $estudo_mercado = filter_input(INPUT_POST, 'estudo_mercado', FILTER_SANITIZE_NUMBER_INT);
        $previsao_fecho = filter_input(INPUT_POST, 'previsao_fecho', FILTER_SANITIZE_NUMBER_INT);
        if ($estado == "" || $descricao == "" || $pre_angariacao == "" || $estudo_mercado == "" || $previsao_fecho == "") {
            $_SESSION['msg_editar_produto'] = "<div class='note note-danger bold font-red-intense'><p>Por favor preencha todos os campos.</p></div>";
        } else {
            switch ($estado) {
                case 1:
                    $upd = "
                        UPDATE controlo_produto SET 
                        estado = '$estado', descricao_negocio = '$descricao', pre_angariacao = '$pre_angariacao', 
                        estudo_mercado = '$estudo_mercado', data_insercao = NOW(), previsao_fecho = '$previsao_fecho', 
                        by_user = '$by_user', data_fecho = NULL, data_morte = NULL WHERE id = '$controlo_produto_id' 
                        AND consultor = '$controlo_produto_consultor' AND loja = '$user_id_loja' AND estado = '$controlo_produto_estado'
                    ";
                    if ($conn->query($upd) === TRUE) {
                        $_SESSION['msg_filtrado'] = "<div class='note note-success bold font-green-meadow'><p>Parabéns! Produto Atualizado Com Sucesso</p></div>";
                        ?>
                        <script type="text/javascript">location.href = "<?php setHome(); ?>/controlo_produto/consultor/<?= $controlo_produto_consultor; ?>";</script>
                        <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/controlo_produto/consultor/<?= $controlo_produto_consultor; ?>" /></noscript>
                        <?php
                    } else {
                        $_SESSION['msg_editar_produto'] = "<div class='note note-danger bold font-red-intense'><p>Impossível Atualizar, Informe o Marketing</p></div>";
                    }
                    break;
                case 2:
                    $upd = "
                        UPDATE controlo_produto SET 
                        estado = '$estado', descricao_negocio = '$descricao', pre_angariacao = '$pre_angariacao', 
                        estudo_mercado = '$estudo_mercado', data_fecho = NOW(), previsao_fecho = '$previsao_fecho', 
                        by_user = '$by_user', data_morte = NULL WHERE id = '$controlo_produto_id' 
                        AND consultor = '$controlo_produto_consultor' AND loja = '$user_id_loja' AND estado = '$controlo_produto_estado'
                    ";
                    if ($conn->query($upd) === TRUE) {
                        $_SESSION['msg_filtrado'] = "<div class='note note-success bold font-green-meadow'><p>Parabéns! Produto Atualizado Com Sucesso</p></div>";
                        ?>
                        <script type="text/javascript">location.href = "<?php setHome(); ?>/controlo_produto/consultor/<?= $controlo_produto_consultor; ?>";</script>
                        <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/controlo_produto/consultor/<?= $controlo_produto_consultor; ?>" /></noscript>
                        <?php
                    } else {
                        $_SESSION['msg_editar_produto'] = "<div class='note note-danger bold font-red-intense'><p>Impossível Atualizar, Informe o Marketing</p></div>";
                    }
                    break;
                case 3:
                    $upd = "
                        UPDATE controlo_produto SET 
                        estado = '$estado', descricao_negocio = '$descricao', pre_angariacao = '$pre_angariacao', 
                        estudo_mercado = '$estudo_mercado', data_morte = NOW(), previsao_fecho = '$previsao_fecho', 
                        by_user = '$by_user', data_fecho = NULL WHERE id = '$controlo_produto_id' 
                        AND consultor = '$controlo_produto_consultor' AND loja = '$user_id_loja' AND estado = '$controlo_produto_estado'
                    ";
                    if ($conn->query($upd) === TRUE) {
                        $_SESSION['msg_filtrado'] = "<div class='note note-success bold font-green-meadow'><p>Parabéns! Produto Atualizado Com Sucesso</p></div>";
                        ?>
                        <script type="text/javascript">location.href = "<?php setHome(); ?>/controlo_produto/consultor/<?= $controlo_produto_consultor; ?>";</script>
                        <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/controlo_produto/consultor/<?= $controlo_produto_consultor; ?>" /></noscript>
                        <?php
                    } else {
                        $_SESSION['msg_editar_produto'] = "<div class='note note-danger bold font-red-intense'><p>Impossível Atualizar, Informe o Marketing</p></div>";
                    }
                    break;
                default:
                    $_SESSION['msg_editar_produto'] = "<div class='note note-success bold font-green-meadow'><p>Ação não permitida</p></div>";
            }
        }
    }
} else {
    if (empty($url_controlo_produto_id) || empty($url_controlo_produto_consultor) || empty($url_controlo_produto_estado)) {
        $_SESSION['msg_editar_produto'] = "<div class='note note-danger bold font-red-intense'><p>Sistema de Proteção Anti-Hacker Ativado</p></div>";
    } else {
        $select = "
            SELECT controlo_produto.estado, controlo_produto.descricao_negocio, controlo_produto.pre_angariacao, 
            controlo_produto.estudo_mercado, controlo_produto.previsao_fecho
            FROM controlo_produto
            WHERE controlo_produto.id = '$url_controlo_produto_id' AND controlo_produto.consultor = '$url_controlo_produto_consultor' 
            AND controlo_produto.estado = '$url_controlo_produto_estado' AND controlo_produto.loja = '$user_id_loja'
        ";
        $result = $conn->query($select);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['estado'];
                if ($status == 1) {
                    $estado = "Em Curso";
                } elseif ($status == 2) {
                    $estado = "Fechada";
                } else {
                    $estado = "Morta";
                }
                $descricao_negocio = $row['descricao_negocio'];
                $pre_ang = $row['pre_angariacao'];
                if ($pre_ang > 0) {
                    $pre_angariacao = "Sim";
                } else {
                    $pre_angariacao = "Não";
                }
                $est_mercado = $row['estudo_mercado'];
                if ($est_mercado > 0) {
                    $estudo_mercado = "Sim";
                } else {
                    $estudo_mercado = "Não";
                }
                $prev_fecho = $row['previsao_fecho'];
                switch ($prev_fecho) {
                    case 1:
                        $previsao_fecho = "Janeiro";
                        break;
                    case 2:
                        $previsao_fecho = "Fevereiro";
                        break;
                    case 3:
                        $previsao_fecho = "Março";
                        break;
                    case 4:
                        $previsao_fecho = "Abril";
                        break;
                    case 5:
                        $previsao_fecho = "Maio";
                        break;
                    case 6:
                        $previsao_fecho = "Junho";
                        break;
                    case 7:
                        $previsao_fecho = "Julho";
                        break;
                    case 8:
                        $previsao_fecho = "Agosto";
                        break;
                    case 9:
                        $previsao_fecho = "Setembro";
                        break;
                    case 10:
                        $previsao_fecho = "Outubro";
                        break;
                    case 11:
                        $previsao_fecho = "Novembro";
                        break;
                    case 12:
                        $previsao_fecho = "Dezembro";
                        break;
                    default:
                        $previsao_fecho = "Indefinido";
                }
            }
        } else {
            $_SESSION['msg_editar_produto'] = "<div class='note note-danger bold font-red-intense'><p>Este Produto Não Existe.</p></div>";
        }
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
                                    <a href="<?php setHome(); ?>/controlo_produto">Controlo de Produto</a>
                                </li>
                                <li>
                                    <span>Editar Produto</span>
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
                                                        <i class="fa fa-pencil font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase">Editar Produto</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row margin-bottom-40">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['msg_editar_produto'])) {
                                                                echo $_SESSION['msg_editar_produto'];
                                                                unset($_SESSION['msg_editar_produto']);
                                                            }
                                                            ?>
                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/controlo_produto/editar-produto" method="post">
                                                                <div class="form-group">
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <label>Estado Atual do Produto<span class="required">*</span></label>
                                                                        <select name="estado" class="form-control select2">
                                                                            <option value="<?= $status; ?>"><?= $estado; ?></option>
                                                                            <option value="1">Em Curso</option>
                                                                            <option value="2">Fechada</option>
                                                                            <option value="3">Morta</option>
                                                                        </select>
                                                                        <input type="hidden" name="controlo_produto_id" value="<?= $url_controlo_produto_id; ?>">
                                                                        <input type="hidden" name="controlo_produto_consultor" value="<?= $url_controlo_produto_consultor; ?>">
                                                                        <input type="hidden" name="controlo_produto_estado" value="<?= $url_controlo_produto_estado; ?>">
                                                                    </div>
                                                                    <div class="col-md-8 margin-bottom-10">
                                                                        <label>Descrição do Produto <span class="required">*</span></label>
                                                                        <input class="form-control" name="descricao" type="text" placeholder="Digite uma Descrição" value="<?= $descricao_negocio; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <label>Reunião de Pré-Angariação <span class="required">*</span></label>
                                                                        <select name="pre_angariacao" class="form-control select2">
                                                                            <option value="<?= $pre_ang; ?>"><?= $pre_angariacao; ?></option>
                                                                            <option value="1">Sim</option>
                                                                            <option value="0">Não</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <label>Apresentação de Estudo de Mercado <span class="required">*</span></label>
                                                                        <select name="estudo_mercado" class="form-control select2">
                                                                            <option value="<?= $est_mercado; ?>"><?= $estudo_mercado; ?></option>
                                                                            <option value="1">Sim</option>
                                                                            <option value="0">Não</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <label>Mês de Previsão do Fecho <span class="required">*</span></label>
                                                                        <select name="previsao_fecho" class="form-control select2">
                                                                            <option value="<?= $prev_fecho; ?>"><?= $previsao_fecho; ?></option>
                                                                            <option value="1">Janeiro</option>
                                                                            <option value="2">Fevereiro</option>
                                                                            <option value="3">Março</option>
                                                                            <option value="4">Abril</option>
                                                                            <option value="5">Maio</option>
                                                                            <option value="6">Junho</option>
                                                                            <option value="7">Julho</option>
                                                                            <option value="8">Agosto</option>
                                                                            <option value="9">Setembro</option>
                                                                            <option value="10">Outubro</option>
                                                                            <option value="11">Novembro</option>
                                                                            <option value="12">Dezembro</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group margin-top-20">
                                                                    <div class="col-md-12 margin-bottom-5">
                                                                        <input type="submit" class="btn green-meadow btn-block bold uppercase" value="Atualizar Informações">
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
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $(document).on("focus", ".select2", function () {
        $(this).siblings("select").select2("open");
    });
    var inputs = $("input,select"); // You can use other elements such as textarea, button etc. 
    //depending on input field types you have used
    $("select").on("select2:close", function () {
        var pos = $(inputs).index(this) + 1;
        var next = $(inputs).eq(pos);
        setTimeout(function () {
            next.focus();
            if (next.siblings(".select2").length) { //If it's a select
                next.select2("open");
            }
        }, 100); //The delay is required to allow default events to occur
    });
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>