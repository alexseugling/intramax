<?php
if (!isset($_SESSION['login']) || $_SESSION['user_id_cargo'] != 6 && $_SESSION['user_id_cargo'] != 9 && $_SESSION['user_id_cargo'] != 16) {
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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'atualizar-imoveis-janeiro-2018';
include_once("tpl/actions/conexao.php");
$year = date('Y');
$nc = $_SESSION['nc'];

$idi = $url[2];
$ida = $url[3];
$_SESSION['finale'] = $_SESSION['form_upd'] = $_SESSION['finito'] = "hidden";

if ($idi != "" && $ida != "") {
    $_SESSION['filtro'] = "hidden";
    $_SESSION['abas'] = "hidden";
    $_SESSION['finale'] = "";
} elseif (!isset($_SESSION['send'])) {
    $_SESSION['filtro'] = "";
    $_SESSION['abas'] = "hidden";
} else {
    $sql_ang = "SELECT livro_registo.angariacao_id, livro_registo.id_inicial_imovel id_imovel FROM livro_registo WHERE livro_registo.num_contrato = '{$nc}'";
    $result_ang = $conn->query($sql_ang);
}
if (isset($_SESSION['session_lastone'])) {
    $_SESSION['filtro'] = "hidden";
    $_SESSION['finale'] = "hidden";
    $_SESSION['form_upd'] = "";

    $user_id_loja = $_SESSION['user_id_loja'];
    $user_store_code = strtoupper($_SESSION['user_store_code']);

    $g_id_consultor = $_SESSION['g_id_consultor'];
    $g_data_insercao_ilist = $_SESSION['g_data_insercao_ilist'];
    $g_tipo_angariacao = $_SESSION['g_tipo_angariacao'];
    $g_tipo_negocio = $_SESSION['g_tipo_negocio'];

    $sql_gc = "SELECT gst.gestor id_gst, col.nome nome_gst, colaboradores.id id_cst, colaboradores.nome nome_cst, user_loja.loja loja_cst
    FROM gestor gst
    INNER JOIN colaboradores ON colaboradores.id = gst.user
    INNER JOIN colaboradores col ON col.id = gst.gestor
    INNER JOIN user_loja ON user_loja.user = colaboradores.id
    WHERE gst.user = $g_id_consultor
    ORDER BY gst.data DESC LIMIT 1";
    $result_gc = $conn->query($sql_gc);

    $sql_lf_imv = "SELECT lf_imv.id id_freg_imv, lf_imv.nome nome_freg_imv FROM lista_freguesia lf_imv";
    $result_lf_imv = $conn->query($sql_lf_imv);

    $sql_lf_prop = "SELECT lf_prop.id id_freg_prop, lf_prop.nome nome_freg_prop FROM lista_freguesia lf_prop";
    $result_lf_prop = $conn->query($sql_lf_prop);

    $sql_timv = "SELECT tipo_imovel.id id_timv, tipo_imovel.nome nome_timv FROM tipo_imovel WHERE tipo_imovel.tipo_angariacao = $g_tipo_angariacao";
    $result_timv = $conn->query($sql_timv);
}
if ($_SESSION['terminado']) {
    $_SESSION['filtro'] = $_SESSION['finale'] = $_SESSION['form_upd'] = $_SESSION['abas'] = "hidden";
    $_SESSION['finito'] = "";
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
                                    <span>Atualizar Imóveis - Janeiro de 2018</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light" id="form_wizard_1">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-refresh font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase"> Atualizar Imóveis - Janeiro de 2018</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row margin-bottom-40 <?= $_SESSION['filtro']; ?>">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['msg_upd_janeiro'])) {
                                                                echo $_SESSION['msg_upd_janeiro'];
                                                                unset($_SESSION['msg_upd_janeiro']);
                                                            }
                                                            ?>
                                                            <p>
                                                                <span class="erro">*</span> Digite o Número de Contrato do Imóvel a actualizar.
                                                            </p>
                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/tpl/actions/upd_janeiro.php" method="post">
                                                                <div class="form-group">
                                                                    <div class="col-md-6">
                                                                        <input name="num_contrato" class="form-control form-control-inline" type="text" placeholder="Digite o Número de Contrato do Imóvel de Janeiro" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <button name="send_nc" type="submit" class="btn blue btn-block bold uppercase">Confirmar e Continuar</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="row margin-bottom-40 <?= $_SESSION['abas']; ?>">
                                                        <div class="col-md-12"> 
                                                            <div class="clearfix util-btn-margin-bottom-5">
                                                                <?php
                                                                if ($result_ang->num_rows > 0) {
                                                                    while ($row_ang = $result_ang->fetch_assoc()) {
                                                                        $angariacao_id = $row_ang['angariacao_id'];
                                                                        $id_imovel = $row_ang['id_imovel'];
                                                                        ?>
                                                                        <a href="<?php setHome(); ?>/coordenacao/atualizar-imoveis-janeiro-2018/<?= $id_imovel; ?>/<?= $angariacao_id; ?>" target="_blank" class="btn btn-lg green-meadow"> <?= $id_imovel; ?> </a>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <a href="<?php setHome(); ?>/coordenacao/atualizar-imoveis-janeiro-2018" class="btn blue btn-block bold uppercase margin-top-20">Procurar Outro Número de Contrato</a>
                                                        </div>
                                                    </div>
                                                    <div class="row margin-bottom-40 <?= $_SESSION['finale']; ?>">
                                                        <div class="col-md-12"> 
                                                            <?php
                                                            $sql_det = "
                                                                SELECT angariacoes.id angariacao_id, angariacoes.data_insercao_ilist, id_angariacao.consultor id_consultor, 
                                                                cons.nome nome_consultor, angariacoes.tipo_angariacao, angariacoes.tipo_negocio, id_angariacao.id_imovel, 
                                                                id_angariacao.gestor id_gestor, gest.nome nome_gestor, angariacoes.arruamento_imovel, angariacoes.num_porta_imovel, 
                                                                angariacoes.complemento_imovel, angariacoes.cod_postal_imovel, angariacoes.freguesia_imovel id_freguesia, 
                                                                lista_freguesia.nome nome_freguesia, angariacoes.tipo_imovel id_tipo_imovel, tipo_imovel.nome nome_tipo_imovel, 
                                                                angariacoes.valor_inicial_imovel, livro_registo.estado_imovel id_estado_imovel, estado_imovel.nome nome_estado_imovel, 
                                                                livro_registo.data_cmi, livro_registo.regime, livro_registo.conservatoria, livro_registo.ficha_crp, 
                                                                livro_registo.matriz_cpu, livro_registo.tipo_comissao id_tipo_comissao, tipo_comissao.nome nome_tipo_comissao, 
                                                                livro_registo.valor_comissao, livro_registo.nome_proprietario, livro_registo.nif_proprietario, 
                                                                livro_registo.email_proprietario, livro_registo.telefone_proprietario, livro_registo.arruamento_proprietario, 
                                                                livro_registo.num_porta_proprietario, livro_registo.complemento_proprietario, livro_registo.cod_postal_proprietario, 
                                                                livro_registo.freguesia_proprietario id_freguesia_prop, freg_prop.nome nome_freg_prop
                                                                FROM angariacoes
                                                                INNER JOIN id_angariacao ON id_angariacao.angariacao_id = angariacoes.id
                                                                INNER JOIN colaboradores cons ON cons.id = id_angariacao.consultor
                                                                INNER JOIN colaboradores gest on gest.id = id_angariacao.gestor
                                                                INNER JOIN lista_freguesia ON lista_freguesia.id = angariacoes.freguesia_imovel
                                                                INNER JOIN tipo_imovel ON tipo_imovel.id = angariacoes.tipo_imovel
                                                                INNER JOIN livro_registo ON livro_registo.angariacao_id = angariacoes.id
                                                                INNER JOIN estado_imovel ON estado_imovel.id = livro_registo.estado_imovel
                                                                INNER JOIN tipo_comissao ON tipo_comissao.id = livro_registo.tipo_comissao
                                                                INNER JOIN lista_freguesia freg_prop ON freg_prop.id = livro_registo.freguesia_proprietario
                                                                WHERE livro_registo.angariacao_id = '{$ida}' AND livro_registo.id_inicial_imovel = '{$idi}'
                                                            ";

                                                            $result_det = $conn->query($sql_det);
                                                            if ($result_det->num_rows > 0) {
                                                                while ($row_det = $result_det->fetch_assoc()) {
                                                                    $angariacao_id = $row_det['angariacao_id'];
                                                                    $data_ins_ilist = date_create($row_det["data_insercao_ilist"]);
                                                                    $data_insercao_ilist = date_format($data_ins_ilist, 'd/m/Y');
                                                                    $id_consultor = $row_det['id_consultor'];
                                                                    $nome_consultor = $row_det['nome_consultor'];
                                                                    $tipo_angariacao = $row_det['tipo_angariacao'];
                                                                    $tipo_negocio = $row_det['tipo_negocio'];
                                                                    $id_imovel = $row_det['id_imovel'];
                                                                    $id_gestor = $row_det['id_gestor'];
                                                                    $nome_gestor = $row_det['nome_gestor'];
                                                                    $arruamento_imovel = $row_det['arruamento_imovel'];
                                                                    $num_porta_imovel = $row_det['num_porta_imovel'];
                                                                    $complemento_imovel = $row_det['complemento_imovel'];
                                                                    $cod_postal_imovel = $row_det['cod_postal_imovel'];
                                                                    $id_freguesia = $row_det['id_freguesia'];
                                                                    $nome_freguesia = $row_det['nome_freguesia'];
                                                                    $id_tipo_imovel = $row_det['id_tipo_imovel'];
                                                                    $nome_tipo_imovel = $row_det['nome_tipo_imovel'];
                                                                    $valor_inicial_imovel = $row_det['valor_inicial_imovel'];
                                                                    $id_estado_imovel = $row_det['id_estado_imovel'];
                                                                    $nome_estado_imovel = $row_det['nome_estado_imovel'];
                                                                    $data_cmi = $row_det['data_cmi'];
                                                                    $regime = $row_det['regime'];
                                                                    $conservatoria = $row_det['conservatoria'];
                                                                    $ficha_crp = $row_det['ficha_crp'];
                                                                    $matriz_cpu = $row_det['matriz_cpu'];
                                                                    $id_tipo_comissao = $row_det['id_tipo_comissao'];
                                                                    $nome_tipo_comissao = $row_det['nome_tipo_comissao'];
                                                                    $valor_comissao = $row_det['valor_comissao'];
                                                                    $nome_proprietario = $row_det['nome_proprietario'];
                                                                    $nif_proprietario = $row_det['nif_proprietario'];
                                                                    $email_proprietario = $row_det['email_proprietario'];
                                                                    $telefone_proprietario = $row_det['telefone_proprietario'];
                                                                    $arruamento_proprietario = $row_det['arruamento_proprietario'];
                                                                    $num_porta_proprietario = $row_det['num_porta_proprietario'];
                                                                    $complemento_proprietario = $row_det['complemento_proprietario'];
                                                                    $cod_postal_proprietario = $row_det['cod_postal_proprietario'];
                                                                    $id_freguesia_prop = $row_det['id_freguesia_prop'];
                                                                    $nome_freg_prop = $row_det['nome_freg_prop'];
                                                                }
                                                                ?>
                                                                <p>
                                                                    <span class="erro">*</span> Campos de preenchimento obrigatório.
                                                                </p>
                                                                <form class="form-horizontal" role="form" action="<?php setHome(); ?>/tpl/actions/upd_janeiro.php" method="post">
                                                                    <div class="form-group">
                                                                        <div class="col-md-3">
                                                                            <label class="control-label">Data de Inserção no Ilist <span class="erro">* </span></label>
                                                                            <input name="data_insercao_ilist" class="form-control form-control-inline date-picker" type="text" value="<?= date("d/m/Y"); ?>" readonly />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="control-label">Selecione o Consultor <span class="erro">* </span></label>
                                                                            <select name="id_consultor" class="form-control select2" required>
                                                                                <option value=""></option>
                                                                                <?php
                                                                                $user_id_loja = $_SESSION['user_id_loja'];
                                                                                $sql_consultores = "SELECT consultor.id id_consultor, consultor.nome nome_consultor
                                                                                FROM colaboradores consultor
                                                                                INNER JOIN user_loja ON user_loja.user = consultor.id and user_loja.loja = $user_id_loja
                                                                                INNER JOIN cargo ON cargo.user = consultor.id and cargo.tipo_cargo in (1,2,18,19)
                                                                                WHERE consultor.status = 1
                                                                                ORDER BY consultor.nome ASC";
                                                                                $result_consultores = $conn->query($sql_consultores);
                                                                                if ($result_consultores->num_rows > 0) {
                                                                                    while ($row_consultores = $result_consultores->fetch_assoc()) {
                                                                                        $id_cons = $row_consultores['id_consultor'];
                                                                                        $nome_cons = $row_consultores['nome_consultor'];
                                                                                        echo "\n<option value='$id_cons'>$nome_cons</option>";
                                                                                    }
                                                                                }
                                                                                ?>                                                                      
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="control-label">Tipo de Angariação <span class="erro">* </span></label>
                                                                            <select name="tipo_angariacao" class="form-control" required>
                                                                                <option value=""></option>
                                                                                <option value="1">Habitação</option>                                                                            
                                                                                <option value="2">Não Habitação</option>                                                                            
                                                                            </select>
                                                                            <input type="hidden" name="idi" value="<?= $idi; ?>">
                                                                            <input type="hidden" name="ida" value="<?= $ida; ?>">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="control-label">Tipo de Negócio <span class="erro">* </span></label>
                                                                            <select name="tipo_negocio" class="form-control" required>
                                                                                <option value=""></option>                                                                      
                                                                                <option value="1">Venda</option>                                                                            
                                                                                <option value="2">Arrendamento</option>                                                                            
                                                                                <option value="3">Trespasse</option>                                                                            
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-actions margin-top-30">
                                                                        <button name="first" type="submit" class="btn blue btn-block bold uppercase">Confirmar e Continuar</button>
                                                                    </div>
                                                                </form>
                                                                <?php
                                                            } else {
                                                                echo "<div class='alert alert-block alert-danger fade in'><button type='button' class='close' data-dismiss='alert'></button><p>Tentativa Hacker bloqueada.</p></div>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="row margin-bottom-40 <?= $_SESSION['form_upd']; ?>">
                                                        <div class="col-md-12"> 
                                                            <?php
                                                            if ($result_gc->num_rows > 0) {
                                                                while ($row_gc = $result_gc->fetch_assoc()) {
                                                                    $id_gst = $row_gc['id_gst'];
                                                                    $nome_gst = $row_gc['nome_gst'];
                                                                    $id_cst = $row_gc['id_cst'];
                                                                    $nome_cst = $row_gc['nome_cst'];
                                                                    $loja_cst = $row_gc['loja_cst'];
                                                                }
                                                            } else {
                                                                echo "<div class='alert alert-danger'><strong>Erro!</strong> Não existe consultores nesta loja.</div>";
                                                            }
                                                            ?>
                                                            <form  role="form" class="margin-bottom-40" action="<?php setHome(); ?>/tpl/actions/upd_janeiro.php" id="submit_form" method="POST">
                                                                <div class="form-wizard">
                                                                    <div class="form-body">
                                                                        <ul class="nav nav-pills nav-justified steps">
                                                                            <li>
                                                                                <a href="#tab1" data-toggle="tab" class="step">
                                                                                    <span class="number"> 1 </span>
                                                                                    <span class="desc">
                                                                                        <i class="fa fa-check"></i> Início
                                                                                    </span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#tab2" data-toggle="tab" class="step">
                                                                                    <span class="number"> 2 </span>
                                                                                    <span class="desc">
                                                                                        <i class="fa fa-check"></i> Dados do Imóvel 
                                                                                    </span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#tab3" data-toggle="tab" class="step">
                                                                                    <span class="number"> 3 </span>
                                                                                    <span class="desc">
                                                                                        <i class="fa fa-check"></i> Dados do Proprietário 
                                                                                    </span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#tab4" data-toggle="tab" class="step">
                                                                                    <span class="number"> 4 </span>
                                                                                    <span class="desc">
                                                                                        <i class="fa fa-check"></i> Confirmação 
                                                                                    </span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                        <div id="bar" class="progress progress-striped" role="progressbar">
                                                                            <div class="progress-bar progress-bar-success"> </div>
                                                                        </div>
                                                                        <div class="tab-content">
                                                                            <div class="alert alert-danger display-none">
                                                                                <button class="close" data-dismiss="alert"></button> Você não preencheu todos os campos obrigatórios. Por favor verifique.
                                                                            </div>
                                                                            <div class="tab-pane active" id="tab1">
                                                                                <h3 class="block">Início</h3>
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="alert alert-info text-center">
                                                                                            <strong>ATENÇÃO! NÃO PROSSIGA</strong> se o Gestor do Consultor estiver Incorreto ou em Branco.
                                                                                            <a href="<?php setHome(); ?>/coordenacao/alerta-correcao-gestor" class="alert-link" target="_blank"> Se For Este o Caso, Clique Aqui e Informe o Marketing.</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">ID da Angariação
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="id_angariacao" maxlength="14" class="form-control form-control-inline id_angariacao" type="text" />
                                                                                                <input type="hidden" name="insercao_ilist" value="<?= $g_data_insercao_ilist; ?>">
                                                                                                <input type="hidden" name="tipo_angariacao" value="<?= $g_tipo_angariacao; ?>">
                                                                                                <input type="hidden" name="tipo_negocio" value="<?= $g_tipo_negocio; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Consultor
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="nome_consultor" class="form-control form-control-inline" type="text" value="<?= $nome_cst; ?>" readonly />
                                                                                                <input type="hidden" name="id_cst" value="<?= $id_cst; ?>">
                                                                                                <input type="hidden" name="loja_cst" value="<?= $loja_cst; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Gestor
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="nome_gestor" class="form-control form-control-inline" type="text" value="<?= $nome_gst; ?>" readonly />
                                                                                                <input type="hidden" name="id_gst" value="<?= $id_gst; ?>">
                                                                                                <input type="hidden" name="ida" value="<?= $ida; ?>">
                                                                                                <input type="hidden" name="idi" value="<?= $idi; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane" id="tab2">
                                                                                <h3 class="block">Dados do Imóvel</h3>
                                                                                <div class="row">
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Rua/Avenida do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="arruamento_imv" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Número da Porta
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="num_porta_imv" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Andar</label>
                                                                                            <div>
                                                                                                <input name="complemento_imv" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Código Postal
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="cod_postal_imv" class="form-control form-control-inline cep" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Selecione a Freguesia do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="id_freg_imv" class="form-control select2 dropd">
                                                                                                <option></option>
                                                                                                <?php
                                                                                                if ($result_lf_imv->num_rows > 0) {
                                                                                                    while ($row_lf_imv = $result_lf_imv->fetch_assoc()) {
                                                                                                        $id_freg_imv = $row_lf_imv['id_freg_imv'];
                                                                                                        $nome_freg_imv = $row_lf_imv['nome_freg_imv'];
                                                                                                        echo "\n<option value='$id_freg_imv'>$nome_freg_imv</option>";
                                                                                                    }
                                                                                                } else {
                                                                                                    echo '<option>Erro no sistema, informe o Marketing</option>';
                                                                                                }
                                                                                                ?> 
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Tipo do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="tipo_imv" class="form-control select2 dropd">
                                                                                                <option></option>
                                                                                                <?php
                                                                                                if ($result_timv->num_rows > 0) {
                                                                                                    while ($row_timv = $result_timv->fetch_assoc()) {
                                                                                                        $id_timv = $row_timv['id_timv'];
                                                                                                        $nome_timv = $row_timv['nome_timv'];
                                                                                                        echo "\n<option value='$id_timv'>$nome_timv</option>";
                                                                                                    }
                                                                                                } else {
                                                                                                    echo '<option>Erro no sistema, informe o Marketing</option>';
                                                                                                }
                                                                                                ?> 
                                                                                            </select> 
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Valor Inicial do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="valor_inicial_imovel" class="form-control form-control-inline money" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Estado do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="estado_imovel" class="form-control select2 dropd">
                                                                                                <option value="1">Activo</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Nº do Contrato
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="num_contrato" class="form-control form-control-inline" type="text" value="<?= $nc; ?>" readonly placeholder="Digite o Número de Contrato" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Data do CMI
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="data_cmi" class="form-control form-control-inline date-picker" type="text" readonly />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Regime
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="regime" class="form-control select2 dropd">
                                                                                                <option value="1">Exclusivo</option>
                                                                                                <option value="2">Exclusivo - Múltiplos</option>
                                                                                                <option value="3">Exclusivo de Rede</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Duração do Contrato
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="dur_contrato" class="form-control select2 dropd">
                                                                                                <option value="180">180 Dias</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Conservatória
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="conservatoria" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Ficha (CRP)
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="ficha_crp" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Matriz (CPU)
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="matriz_cpu" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Tipo de Comissão
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="tipo_comissao" class="form-control select2 dropd">
                                                                                                <option value=""></option>
                                                                                                <option value="1">Percentual</option>
                                                                                                <option value="2">Valor Fixo</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Valor da Comissão
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="valor_comissao" class="form-control form-control-inline comissao" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane" id="tab3">
                                                                                <h3 class="block">Dados dos Proprietário</h3>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Nome do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="nome_prop" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">NIF do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="nif_prop" minlength="9" maxlength="9" class="form-control form-control-inline nif" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Email do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="email_prop" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Telefone do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="telefone_prop" minlength="9" maxlength="20" class="form-control form-control-inline phone" type="text" />
                                                                                                <span class="help-block font-red"> Este Número Será Verificado pelo Departamento de Qualidade </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Rua/Avenida do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="arruamento_pro" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Número da Porta
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="num_porta_prop" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Andar</label>
                                                                                            <div>
                                                                                                <input name="complemento_prop" class="form-control form-control-inline" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Código Postal
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="cod_postal_prop" class="form-control form-control-inline cep" type="text" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Selecione a Freguesia do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="id_freg_prop" class="form-control select2 dropd">
                                                                                                <option></option>
                                                                                                <?php
                                                                                                if ($result_lf_prop->num_rows > 0) {
                                                                                                    while ($row_lf_prop = $result_lf_prop->fetch_assoc()) {
                                                                                                        $id_freg_prop = $row_lf_prop['id_freg_prop'];
                                                                                                        $nome_freg_prop = $row_lf_prop['nome_freg_prop'];
                                                                                                        echo "\n<option value='$id_freg_prop'>$nome_freg_prop</option>";
                                                                                                    }
                                                                                                } else {
                                                                                                    echo '<option>Erro no sistema, informe o Marketing</option>';
                                                                                                }
                                                                                                ?> 
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane" id="tab4">
                                                                                <h3 class="block">Confirme as Informações Inseridas</h3>
                                                                                <h4 class="form-section">Início</h4>
                                                                                <hr>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Data de Inserção no Ilist</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $g_data_insercao_ilist; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">ID da Angariação</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="id_angariacao"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Consultor</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="nome_consultor"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Gestor</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="nome_gestor"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>                                                                                
                                                                                <h4 class="form-section">Dados do Imóvel</h4>
                                                                                <hr>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Rua/Avenida do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="arruamento_imv"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Número da Porta</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="num_porta_imv"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Andar</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="complemento_imv"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Código Postal</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="cod_postal_imv"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Freguesia do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="id_freg_imv"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Tipo do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="tipo_imv"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Valor Inicial do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="valor_inicial_imovel"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Estado do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="estado_imovel"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Nº do Contrato</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="num_contrato"></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Data do CMI</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="data_cmi"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Regime</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="regime"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Duração do Contrato</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="dur_contrato"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Conservatória</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="conservatoria"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Ficha (CRP)</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="ficha_crp"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Matriz (CPU)</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="matriz_cpu"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Tipo de Comissão</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="tipo_comissao"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Valor da Comissão</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="valor_comissao"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>                                                                                
                                                                                <h4 class="form-section">Dados do Proprietário</h4>
                                                                                <hr>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Nome do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="nome_prop"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Email do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="email_prop"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Telefone do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="telefone_prop"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Rua/Avenida do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="arruamento_pro"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Número da Porta</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="num_porta_prop"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Andar</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="complemento_prop"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Código Postal</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="cod_postal_prop"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Freguesia do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static" data-display="id_freg_prop"> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row margin-top-20">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <a href="<?php setHome(); ?>/coordenacao/atualizar-imoveis-janeiro-2018/<?= $idi; ?>/<?= $ida; ?>" class="btn red uppercase bold">
                                                                                    <i class="fa fa-angle-left"></i> Escolher Outro Consultor
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 text-right">
                                                                            <div class="form-group">
                                                                                <a href="javascript:;" class="btn default uppercase bold button-previous">
                                                                                    <i class="fa fa-angle-left"></i> Voltar </a>
                                                                                <a href="javascript:;" class="btn blue uppercase bold button-next"> Continuar
                                                                                    <i class="fa fa-angle-right"></i>
                                                                                </a>
                                                                                <button name="upd_info" type="submit" class="btn green-meadow uppercase bold button-submit"> Finalizar
                                                                                    <i class="fa fa-check"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>                                                                            
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $sql_det_f = "
                                                            SELECT angariacoes.id angariacao_id, livro_registo.num_contrato, angariacoes.data_insercao_ilist, id_angariacao.consultor id_consultor, 
                                                            cons.nome nome_consultor, angariacoes.tipo_angariacao, tipo_angariacao.nome nome_tipo_angariacao, 
                                                            angariacoes.tipo_negocio, tipo_negocio.nome nome_tipo_negocio, id_angariacao.id_imovel, 
                                                            id_angariacao.gestor id_gestor, gest.nome nome_gestor, angariacoes.arruamento_imovel, angariacoes.num_porta_imovel, 
                                                            angariacoes.complemento_imovel, angariacoes.cod_postal_imovel, angariacoes.freguesia_imovel id_freguesia, 
                                                            lista_freguesia.nome nome_freguesia, angariacoes.tipo_imovel id_tipo_imovel, tipo_imovel.nome nome_tipo_imovel, 
                                                            angariacoes.valor_inicial_imovel, livro_registo.estado_imovel id_estado_imovel, estado_imovel.nome nome_estado_imovel, 
                                                            livro_registo.data_cmi, livro_registo.regime, tipo_regime.nome nome_regime, livro_registo.conservatoria, 
                                                            livro_registo.ficha_crp, livro_registo.matriz_cpu, livro_registo.tipo_comissao id_tipo_comissao, 
                                                            tipo_comissao.nome nome_tipo_comissao, livro_registo.valor_comissao, livro_registo.nome_proprietario, 
                                                            livro_registo.nif_proprietario, livro_registo.email_proprietario, livro_registo.telefone_proprietario, 
                                                            livro_registo.arruamento_proprietario, livro_registo.num_porta_proprietario, livro_registo.complemento_proprietario, 
                                                            livro_registo.cod_postal_proprietario, livro_registo.freguesia_proprietario id_freguesia_prop, 
                                                            freg_prop.nome nome_freg_prop
                                                            FROM angariacoes
                                                            INNER JOIN id_angariacao ON id_angariacao.angariacao_id = angariacoes.id
                                                            INNER JOIN colaboradores cons ON cons.id = id_angariacao.consultor
                                                            INNER JOIN colaboradores gest on gest.id = id_angariacao.gestor
                                                            INNER JOIN lista_freguesia ON lista_freguesia.id = angariacoes.freguesia_imovel
                                                            INNER JOIN tipo_imovel ON tipo_imovel.id = angariacoes.tipo_imovel
                                                            INNER JOIN tipo_angariacao ON tipo_angariacao.id = angariacoes.tipo_angariacao
                                                            INNER JOIN tipo_negocio ON tipo_negocio.id = angariacoes.tipo_negocio
                                                            INNER JOIN livro_registo ON livro_registo.angariacao_id = angariacoes.id
                                                            INNER JOIN estado_imovel ON estado_imovel.id = livro_registo.estado_imovel
                                                            INNER JOIN tipo_comissao ON tipo_comissao.id = livro_registo.tipo_comissao
                                                            INNER JOIN lista_freguesia freg_prop ON freg_prop.id = livro_registo.freguesia_proprietario
                                                            INNER JOIN tipo_regime ON tipo_regime.id = livro_registo.regime
                                                            WHERE livro_registo.angariacao_id = '{$ida}' AND livro_registo.id_inicial_imovel = '{$idi}'
                                                            ";

                                                    $result_det_f = $conn->query($sql_det_f);
                                                    if ($result_det_f->num_rows > 0) {
                                                        while ($row_det_f = $result_det_f->fetch_assoc()) {
                                                            $f_angariacao_id = $row_det_f['angariacao_id'];
                                                            $f_num_contrato = $row_det_f['num_contrato'];
                                                            $f_data_ins_ilist = date_create($row_det_f["data_insercao_ilist"]);
                                                            $f_data_insercao_ilist = date_format($f_data_ins_ilist, 'd/m/Y');
                                                            $f_id_consultor = $row_det_f['id_consultor'];
                                                            $f_nome_consultor = $row_det_f['nome_consultor'];
                                                            $f_tipo_angariacao = $row_det_f['tipo_angariacao'];
                                                            $f_tipo_negocio = $row_det_f['tipo_negocio'];
                                                            $f_id_imovel = $row_det_f['id_imovel'];
                                                            $f_id_gestor = $row_det_f['id_gestor'];
                                                            $f_nome_gestor = $row_det_f['nome_gestor'];
                                                            $f_arruamento_imovel = $row_det_f['arruamento_imovel'];
                                                            $f_num_porta_imovel = $row_det_f['num_porta_imovel'];
                                                            $f_complemento_imovel = $row_det_f['complemento_imovel'];
                                                            $f_cod_postal_imovel = $row_det_f['cod_postal_imovel'];
                                                            $f_id_freguesia = $row_det_f['id_freguesia'];
                                                            $f_nome_freguesia = $row_det_f['nome_freguesia'];
                                                            $f_id_tipo_imovel = $row_det_f['id_tipo_imovel'];
                                                            $f_nome_tipo_imovel = $row_det_f['nome_tipo_imovel'];
                                                            $f_valor_inicial_imovel = $row_det_f['valor_inicial_imovel'];
                                                            $f_id_estado_imovel = $row_det_f['id_estado_imovel'];
                                                            $f_nome_estado_imovel = $row_det_f['nome_estado_imovel'];
                                                            $f_data_cm = date_create($row_det_f["data_cmi"]);
                                                            $f_data_cmi = date_format($f_data_cm, 'd/m/Y');
                                                            $f_regime = $row_det_f['regime'];
                                                            $f_nome_regime = $row_det_f['nome_regime'];
                                                            $f_conservatoria = $row_det_f['conservatoria'];
                                                            $f_ficha_crp = $row_det_f['ficha_crp'];
                                                            $f_matriz_cpu = $row_det_f['matriz_cpu'];
                                                            $f_id_tipo_comissao = $row_det_f['id_tipo_comissao'];
                                                            $f_nome_tipo_comissao = $row_det_f['nome_tipo_comissao'];
                                                            $f_valor_comissao = $row_det_f['valor_comissao'];
                                                            $f_nome_proprietario = $row_det_f['nome_proprietario'];
                                                            $f_nif_proprietario = $row_det_f['nif_proprietario'];
                                                            $f_email_proprietario = $row_det_f['email_proprietario'];
                                                            $f_telefone_proprietario = $row_det_f['telefone_proprietario'];
                                                            $f_arruamento_proprietario = $row_det_f['arruamento_proprietario'];
                                                            $f_num_porta_proprietario = $row_det_f['num_porta_proprietario'];
                                                            $f_complemento_proprietario = $row_det_f['complemento_proprietario'];
                                                            $f_cod_postal_proprietario = $row_det_f['cod_postal_proprietario'];
                                                            $f_id_freguesia_prop = $row_det_f['id_freguesia_prop'];
                                                            $f_nome_freg_prop = $row_det_f['nome_freg_prop'];
                                                        }
                                                    } else {
                                                        $_SESSION['finito'] = "hidden";
                                                        if (isset($_SESSION['terminado'])){
                                                            $_SESSION['fatal_message'] = "<div class='alert alert-block alert-danger fade in'><button type='button' class='close' data-dismiss='alert'></button><p>Erro de Boson. Informe o Marketing</p></div>";
                                                        }                                                        
                                                    }
                                                    ?>
                                                    <div class="row margin-bottom-40">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['fatal_message'])) {
                                                                echo $_SESSION['fatal_message'];
                                                                unset($_SESSION['fatal_message']);
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-md-12 <?= $_SESSION['finito']; ?>">                                                            
                                                            <div class="note note-success font-green-haze">
                                                                <h4 class='block bold'>✔ Angariação Atualizada com Sucesso!</h4>
                                                                <p>Parabéns por ter Atualizado todos os campos corretamente.</p>
                                                            </div>
                                                            <a href="<?php setHome(); ?>/coordenacao/atualizar-imoveis-janeiro-2018" class="btn red btn-block bold uppercase">Clique Aqui para Atualizar Outro Imóvel</a>
                                                            <h3 class="block text-center bold font-blue">Confirme abaixo as Informações Após a Atualização</h3>
                                                            <h4 class="form-section">Início</h4>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Data de Inserção no Ilist</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_data_insercao_ilist; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">ID da Angariação</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_id_imovel; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Consultor</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_consultor; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Gestor</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_gestor; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                                                
                                                            <h4 class="form-section">Dados do Imóvel</h4>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Rua/Avenida do Imóvel</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_arruamento_imovel; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Número da Porta</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_num_porta_imovel; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Andar</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_complemento_imovel; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Código Postal</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_cod_postal_imovel; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Freguesia do Imóvel</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_freguesia; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Tipo do Imóvel</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_tipo_imovel; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Valor Inicial do Imóvel</label>
                                                                        <div>
                                                                            <p class="form-control-static comissao"> <?= $f_valor_inicial_imovel; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Estado do Imóvel</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_estado_imovel; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Nº do Contrato</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_num_contrato; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Data do CMI</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_data_cmi; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Regime</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_regime; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Duração do Contrato</label>
                                                                        <div>
                                                                            <p class="form-control-static"> 180 dias </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Conservatória</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_conservatoria; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Ficha (CRP)</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_ficha_crp; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Matriz (CPU)</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_matriz_cpu; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Tipo de Comissão</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_tipo_comissao; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Valor da Comissão</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_valor_comissao; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                                                                                
                                                            <h4 class="form-section">Dados do Proprietário</h4>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Nome do Proprietário</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_proprietario; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Email do Proprietário</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_email_proprietario; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Telefone do Proprietário</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_telefone_proprietario; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Rua/Avenida do Proprietário</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_arruamento_proprietario; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Número da Porta</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_num_porta_proprietario; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Andar</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_complemento_proprietario; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Código Postal</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_cod_postal_proprietario; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label class="control-label bold">Freguesia do Proprietário</label>
                                                                        <div>
                                                                            <p class="form-control-static"> <?= $f_nome_freg_prop; ?> </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/form-wizard.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
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
                $(_this).val(texto.substring(0, (texto.length - 1)));
            }
        }, 100);
    });
    $('.num_contrato').on('keypress', function () {
        var regex = new RegExp("^[0-9/\b]+$");
        var _this = this;
        // Curta pausa para esperar colar para completar
        setTimeout(function () {
            var texto = $(_this).val();
            if (!regex.test(texto))
            {
                $(_this).val(texto.substring(0, (texto.length - 1)));
            }
        }, 100);
    });
    $('.desbloqueia').on('change', function () {
        $('.retira').removeAttr("readonly", this.value != '1');
        $('.retira').attr("readonly", this.value == '1');
        $('.retira').val('<?= $nc; ?>', this.value == '1');
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
<?php
if (isset($_SESSION['send'])) {
    unset($_SESSION['send']);
}
if (isset($_SESSION['session_lastone'])) {
    unset($_SESSION['session_lastone']);
}
if (isset($_SESSION['terminado'])) {
    unset($_SESSION['terminado']);
}
if (isset($_SESSION['fatal_message'])) {
    unset($_SESSION['fatal_message']);
}
?>