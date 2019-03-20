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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'baixa-de-preco';

$filtro_baixa = filter_input(INPUT_POST, 'filtro_baixa', FILTER_SANITIZE_STRING);

if (isset($filtro_baixa)) {
    include_once("tpl/actions/conexao.php");
    $id_imovel = filter_input(INPUT_POST, 'id_imovel', FILTER_SANITIZE_STRING);

    // det = detalhes
    $det_imv = "SELECT id_angariacao.angariacao_id, id_angariacao.consultor id_cst, cst.nome nome_cst, id_angariacao.gestor id_gst, gst.nome nome_gst, baixa_preco.novo_valor_imovel ultimo_valor_imv, angariacoes.freguesia_imovel freg_id, lista_freguesia.nome freg_nome, lista_freguesia.concelho conc_id, lista_freguesia.distrito dist_id, lista_concelho.nome conc_nome, lista_distrito.nome dist_nome, angariacoes.tipo_angariacao tipo_ang, angariacoes.tipo_negocio tipo_neg
    FROM id_angariacao
    INNER JOIN colaboradores cst ON cst.id = id_angariacao.consultor
    INNER JOIN colaboradores gst ON gst.id = id_angariacao.gestor
    INNER JOIN baixa_preco ON baixa_preco.angariacao_id = id_angariacao.angariacao_id
    INNER JOIN angariacoes ON angariacoes.id = id_angariacao.angariacao_id
    INNER JOIN lista_freguesia ON lista_freguesia.id = angariacoes.freguesia_imovel
    INNER JOIN lista_concelho ON lista_concelho.id = lista_freguesia.concelho
    INNER JOIN lista_distrito ON lista_distrito.id = lista_freguesia.distrito
    INNER JOIN livro_registo ON livro_registo.angariacao_id = id_angariacao.angariacao_id
    WHERE id_angariacao.id_imovel = '{$id_imovel}' AND livro_registo.estado_imovel = 1
    ORDER BY baixa_preco.id DESC LIMIT 1";
    $result_det_imv = $conn->query($det_imv);

    $_SESSION['filtro_baixa'] = "hidden";
    $_SESSION['baixa_next'] = "";
} else {
    $_SESSION['filtro_baixa'] = "";
    $_SESSION['baixa_next'] = "hidden";
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
                                    <span>Baixa de Preço</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row">
                                        <div class="col-md-12 <?= $_SESSION['filtro_baixa'] ?>">
                                            <div class="portlet light">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-arrow-down font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase"> Nova Baixa de Preço</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body margin-bottom-10">                                                    
                                                    <?php
                                                    if (isset($_SESSION['msg_baixa_preco'])) {
                                                        echo $_SESSION['msg_baixa_preco'];
                                                        unset($_SESSION['msg_baixa_preco']);
                                                    }
                                                    ?>
                                                    <p>
                                                        <span class="erro">*</span> Digite o ID do Imóvel no Campo Abaixo e Clique no Botão "Confirmar e Continuar".
                                                    </p>
                                                    <form class="form-horizontal" role="form" action="<?php setHome(); ?>/coordenacao/baixa-de-preco" method="post">
                                                        <div class="form-group">
                                                            <div class="col-md-6 margin-bottom-10">
                                                                <div>
                                                                    <input name="id_imovel" minlength="11" maxlength="14" class="form-control form-control-inline id_angariacao" type="text" placeholder="Digite o ID do Imóvel" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <button name="filtro_baixa" type="submit" class="btn green-meadow btn-block bold uppercase">Confirmar e Continuar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 <?= $_SESSION['baixa_next'] ?>">
                                            <?php
                                            if ($result_det_imv->num_rows > 0) {
                                                while ($row_det_imv = $result_det_imv->fetch_assoc()) {
                                                    $angariacao_id = $row_det_imv['angariacao_id'];
                                                    $id_cst = $row_det_imv['id_cst'];
                                                    $nome_cst = $row_det_imv['nome_cst'];
                                                    $id_gst = $row_det_imv['id_gst'];
                                                    $nome_gst = $row_det_imv['nome_gst'];
                                                    $ultimo_valor_imv = $row_det_imv['ultimo_valor_imv'];
                                                    $freg_id = $row_det_imv['freg_id'];
                                                    $freg_nome = $row_det_imv['freg_nome'];
                                                    $conc_id = $row_det_imv['conc_id'];
                                                    $conc_nome = $row_det_imv['conc_nome'];
                                                    $dist_id = $row_det_imv['dist_id'];
                                                    $dist_nome = $row_det_imv['dist_nome'];
                                                    $tipo_ang = $row_det_imv['tipo_ang'];
                                                    $tipo_neg = $row_det_imv['tipo_neg'];
                                                }
                                                $hist = "SELECT baixa_preco.data, baixa_preco.by_user, baixa_preco.ultimo_valor_imovel, baixa_preco.novo_valor_imovel, baixa_preco.produto_espelho, colaboradores.nome
                                                FROM baixa_preco
                                                INNER JOIN colaboradores ON colaboradores.id = baixa_preco.by_user
                                                WHERE baixa_preco.angariacao_id = $angariacao_id AND baixa_preco.ultimo_valor_imovel <> baixa_preco.novo_valor_imovel
                                                ORDER BY baixa_preco.data DESC";
                                                $result_hist = $conn->query($hist);
                                            } else {
                                                if (isset($filtro_baixa)) {
                                                    $_SESSION['msg_baixa_preco'] = "<div class='alert alert-danger'><strong>Atenção!</strong> ID do imóvel não existe ou foi substituído.</div>";
                                                    ?>
                                                    <script type="text/javascript">location.href = "<?php setHome(); ?>/coordenacao/baixa-de-preco";</script>
                                                    <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/coordenacao/baixa-de-preco" /></noscript>
                                                    <?php
                                                }
                                            }
                                            ?> 
                                            <div class="portlet light">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-arrow-down font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase"> Nova Baixa de Preço</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">  
                                                    <form class="form-horizontal" role="form" action="<?php setHome(); ?>/tpl/actions/baixa_preco.php" method="post">
                                                        <div class="form-group margin-top-20">
                                                            <div class="col-md-6">
                                                                <label class="control-label">Data da Baixa de Preço <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="data_baixa_preco" class="form-control form-control-inline date-picker data" type="text" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label">ID da Angariação <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="id_imovel" minlength="12" maxlength="13" class="form-control form-control-inline id_angariacao" type="text" value="<?= $id_imovel; ?>" readonly required />
                                                                    <input type="hidden" name="angariacao_id" value="<?= $angariacao_id; ?>">
                                                                    <input type="hidden" name="tipo_ang" value="<?= $tipo_ang; ?>">
                                                                    <input type="hidden" name="tipo_neg" value="<?= $tipo_neg; ?>">
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-6">
                                                                <label class="control-label">Gestor <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="nome_gst" class="form-control form-control-inline" type="text" value="<?= $nome_gst; ?>" readonly required />
                                                                    <input type="hidden" name="id_gst" value="<?= $id_gst; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label">Consultor <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="nome_cst" class="form-control form-control-inline" type="text" value="<?= $nome_cst; ?>" readonly required />
                                                                    <input type="hidden" name="id_cst" value="<?= $id_cst; ?>">
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-6">
                                                                <label class="control-label">Distrito <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="dist_nome" class="form-control form-control-inline" type="text" value="<?= $dist_nome; ?>" readonly required />
                                                                    <input type="hidden" name="dist_id" value="<?= $dist_id; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="control-label">Concelho <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="conc_nome" class="form-control form-control-inline" type="text" value="<?= $conc_nome; ?>" readonly required />
                                                                    <input type="hidden" name="conc_id" value="<?= $conc_id; ?>">
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label class="control-label">Freguesia <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="freg_nome" class="form-control form-control-inline" type="text" value="<?= $freg_nome; ?>" readonly />
                                                                    <input type="hidden" name="freg_id" value="<?= $freg_id; ?>">
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-6">
                                                                <label class="control-label">Último Valor do Imóvel <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="valor_antigo_imv" class="form-control form-control-inline valores" type="text" value="<?= $ultimo_valor_imv; ?>" readonly required />
                                                                </div>
                                                            </div> 
                                                            <div class="col-md-6">
                                                                <label class="control-label">Novo Valor do Imóvel <span class="erro">*</span></label>
                                                                <div>
                                                                    <input name="novo_valor_imv" class="form-control form-control-inline valores" type="text" placeholder="Digite o Novo Valor" required />
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="form-group margin-top-20">
                                                            <div class="col-md-6 margin-bottom-10">
                                                                <a class="btn red bold btn-block uppercase" href="<?php setHome(); ?>/coordenacao/baixa-de-preco"> Procurar Outro </a>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <button type="submit" class="btn green-meadow btn-block bold uppercase">Atualizar Valor</button>
                                                            </div> 
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 <?= $_SESSION['baixa_next'] ?>">
                                            <div class="portlet box blue">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-calendar"></i>Histórico de Baixas
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="scroller" style="height:490px">
                                                        <?php
                                                        if ($result_hist->num_rows > 0) {
                                                            while ($row_hist = $result_hist->fetch_assoc()) {
                                                                $data = substr($row_hist['data'], 0, strpos($row_hist['data'], ' '));
                                                                $data_baixa_preco = implode("/", array_reverse(explode("-", $data)));
                                                                $by_user = $row_hist['by_user'];
                                                                $ultimo_valor_imovel = $row_hist['ultimo_valor_imovel'];
                                                                $novo_valor_imovel = $row_hist['novo_valor_imovel'];
                                                                $nome_col_hist = $row_hist['nome'];
                                                                $produto_espelho = $row_hist['produto_espelho'];

                                                                switch ($produto_espelho) {
                                                                    case 0:
                                                                        $produto = '<i class="fa fa-times-circle font-red-intense"></i>';
                                                                        break;
                                                                    case 1:
                                                                        $produto = '<i class="fa fa-check-circle font-green-meadow"></i>';
                                                                        break;
                                                                    case NULL:
                                                                        $produto = "Não se Aplica.";
                                                                        break;
                                                                }
                                                                ?>
                                                                <div class="timeline">
                                                                    <div class="timeline-item">
                                                                        <div class="timeline-badge">
                                                                            <div class="timeline-icon">
                                                                                <i class="fa fa-calendar font-blue"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="timeline-body">
                                                                            <div class="timeline-body-arrow"> </div>
                                                                            <div class="timeline-body-head">
                                                                                <div class="timeline-body-head-caption">
                                                                                    <span class="timeline-body-alerttitle font-red-intense"><?= $data_baixa_preco; ?></span>
                                                                                    <span class="timeline-body-time bold font-blue"><?= $nome_col_hist; ?></span>
                                                                                </div>                                                                    
                                                                            </div>
                                                                            <div class="timeline-body-content">
                                                                                <h4 class="font-red-intense bold">
                                                                                    Valor Atual: <span class="valores"><?= $novo_valor_imovel; ?></span>€
                                                                                </h4>
                                                                                <h4 class="font-grey-cascade bold">
                                                                                    Valor Antigo: <span class="valores"><?= $ultimo_valor_imovel; ?></span>€
                                                                                </h4>
                                                                                <h4 class="bold">
                                                                                    Produto: <?= $produto; ?>
                                                                                </h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <div class="timeline">
                                                                <div class="timeline-item">
                                                                    <div class="timeline-badge">
                                                                        <div class="timeline-icon">
                                                                            <i class="fa fa-calendar font-blue"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-body">
                                                                        <div class="timeline-body-arrow"> </div>
                                                                        <div class="timeline-body-head">
                                                                            <div class="timeline-body-head-caption">
                                                                                <span class="timeline-body-alerttitle font-blue">Até a data de</span>
                                                                                <span class="timeline-body-time bold font-red-intense"><?= date("d/m/Y"); ?></span>
                                                                            </div>                                                                    
                                                                        </div>
                                                                        <div class="timeline-body-content">
                                                                            <h4 class="font-red-intense bold">
                                                                                Não existe baixa de preço.
                                                                            </h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
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
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.pt.min.js" type="text/javascript"></script>
<script type="text/javascript">
                                                $('.date-picker').datepicker({
                                                    format: "dd/mm/yyyy",
                                                    weekStart: 0,
                                                    language: "pt",
                                                    autoclose: true
                                                });
</script>
<script>
    $('.id_angariacao').on('keypress', function () {
        var regex = new RegExp("^[0-9-\b]+$");
        var _this = this;
        // Curta pausa para esperar colar para completar
        setTimeout(function () {
            var texto = $(_this).val();
            if (!regex.test(texto))
            {
                $(_this).val(texto.substring(0, (texto.length - 1)))
            }
        }, 100);
    });
</script>
<script type="text/javascript" src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-mask/jquery.mask.min.js"></script>
<script>
//    $('.money').mask("#,##0.00", {reverse: true});
    $('.money').mask("###0.00", {reverse: true});
    $('.valores').mask("#.##0,00", {reverse: true});
    $('.phone').mask("000000000", {reverse: true});
    $('.cep').mask("0000-000", {reverse: true});
    $('.data').mask("00/00/0000", {reverse: true});
    $('.numeros').mask("###", {reverse: true});
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>