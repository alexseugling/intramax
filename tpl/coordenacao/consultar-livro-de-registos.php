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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />

<link href="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'consultar-livro-de-registos';
$mtd = $url[2];
$filtros = $data_ok = $nc_ok = $id_tabela_data = $id_tabela_nc = '';
$erro = $data = $nc = $resultado_data = $resultado_nc = 'hidden';
$store_code = strtoupper($_SESSION['user_store_code']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("tpl/actions/conexao.php");
    if (!empty($mtd)) {
        if ($mtd == 'data') {
            $id_tabela_data = "sample_1";
            $filtros = 'hidden';
            $data = '';
            $from = filter_input(INPUT_POST, 'from', FILTER_SANITIZE_STRING);
            $data_inicial = implode("-", array_reverse(explode("/", $from)));
            $to = filter_input(INPUT_POST, 'to', FILTER_SANITIZE_STRING);
            $data_final = implode("-", array_reverse(explode("/", $to)));
            if (!empty($data_inicial) && !empty($data_final) && !empty($store_code)) {
                $sql_data = "
                    SELECT angariacoes.id angariacoes_id, angariacoes.data_insercao_ilist, id_angariacao.consultor id_consultor, CONSUL.nome nome_consultor, 
                    angariacoes.tipo_angariacao id_tipo_angariacao, tipo_angariacao.nome nome_tipo_angariacao, 
                    angariacoes.tipo_negocio id_tipo_negocio, tipo_negocio.nome nome_tipo_negocio, livro_registo.id_inicial_imovel, 
                    id_angariacao.id_imovel id_atual_imovel, id_angariacao.gestor id_gestor, GEST.nome nome_gestor, 
                    angariacoes.arruamento_imovel, angariacoes.num_porta_imovel, angariacoes.complemento_imovel, 
                    angariacoes.cod_postal_imovel, 
                    angariacoes.freguesia_imovel id_freguesia_imovel, LF_IMV.nome nome_freguesia_imovel, LF_IMV.concelho id_concelho_imovel, 
                    LC_IMV.nome nome_concelho_imovel, LF_IMV.distrito id_distrito_imovel, LD_IMV.nome nome_distrito_imovel, 
                    angariacoes.tipo_imovel id_tipo_imovel, tipo_imovel.nome nome_tipo_imovel, angariacoes.valor_inicial_imovel, 
                    livro_registo.estado_imovel id_estado_imovel, estado_imovel.nome nome_estado_imovel, livro_registo.num_contrato, 
                    livro_registo.data_cmi, livro_registo.regime id_regime, tipo_regime.nome nome_regime, livro_registo.duracao_contrato, 
                    livro_registo.conservatoria, livro_registo.ficha_crp, livro_registo.matriz_cpu, livro_registo.fracao, 
                    livro_registo.tipo_comissao id_tipo_comissao, tipo_comissao.nome nome_tipo_comissao, livro_registo.valor_comissao, 
                    livro_registo.nome_proprietario, livro_registo.nif_proprietario, livro_registo.email_proprietario, 
                    livro_registo.telefone_proprietario, livro_registo.arruamento_proprietario, livro_registo.num_porta_proprietario, 
                    livro_registo.complemento_proprietario, livro_registo.cod_postal_proprietario, 
                    livro_registo.freguesia_proprietario id_freguesia_proprietario, LF_PROP.nome nome_freguesia_proprietario, 
                    LF_PROP.concelho id_concelho_proprietario, LC_PROP.nome nome_concelho_proprietario, 
                    LF_PROP.distrito id_distrito_proprietario, LD_PROP.nome nome_distrito_proprietario, livro_registo.data_negocio, 
                    livro_registo.data_recisao, livro_registo.valor_negocio, livro_registo.comissao_recebida
                    FROM angariacoes
                    INNER JOIN livro_registo ON livro_registo.angariacao_id = angariacoes.id
                    INNER JOIN id_angariacao ON id_angariacao.angariacao_id = angariacoes.id
                    INNER JOIN colaboradores CONSUL ON CONSUL.id = id_angariacao.consultor
                    INNER JOIN tipo_angariacao ON tipo_angariacao.id = angariacoes.tipo_angariacao
                    INNER JOIN tipo_negocio ON tipo_negocio.id = angariacoes.tipo_negocio
                    INNER JOIN colaboradores GEST ON GEST.id = id_angariacao.gestor
                    INNER JOIN lista_freguesia LF_IMV ON LF_IMV.id = angariacoes.freguesia_imovel
                    INNER JOIN lista_concelho LC_IMV ON LC_IMV.id = LF_IMV.concelho
                    INNER JOIN lista_distrito LD_IMV ON LD_IMV.id = LF_IMV.distrito
                    INNER JOIN tipo_imovel ON tipo_imovel.id = angariacoes.tipo_imovel
                    INNER JOIN estado_imovel ON estado_imovel.id = livro_registo.estado_imovel
                    INNER JOIN tipo_regime ON tipo_regime.id = livro_registo.regime
                    INNER JOIN tipo_comissao ON tipo_comissao.id = livro_registo.tipo_comissao
                    INNER JOIN lista_freguesia LF_PROP ON LF_PROP.id = livro_registo.freguesia_proprietario
                    INNER JOIN lista_concelho LC_PROP ON LC_PROP.id = LF_PROP.concelho
                    INNER JOIN lista_distrito LD_PROP ON LD_PROP.id = LF_PROP.distrito
                    WHERE SUBSTRING_INDEX(num_contrato, '/', 1) = '$store_code' AND DATE(livro_registo.data_cmi) BETWEEN '$data_inicial' AND '$data_final'
                ";
                $result_data = $conn->query($sql_data);
                if ($result_data->num_rows > 0) {
                    $resultado_data = '';
                    $data_ok = 1;
                } else {
                    $_SESSION['msg_data'] = "<div class='alert alert-danger'><strong>Sem Resultados!</strong> Não existem angariações no período selecionado.</div>";
                }
            } else {
                $_SESSION['msg_data'] = "<div class='alert alert-danger'><strong>Atenção!</strong> Preencha a Data Inicial e a Data Final.</div>";
            }
        }
        if ($mtd == 'nc') {
            $id_tabela_nc = "sample_1";
            $filtros = 'hidden';
            $nc = '';
            $from = filter_input(INPUT_POST, 'from', FILTER_SANITIZE_STRING);
            $to = filter_input(INPUT_POST, 'to', FILTER_SANITIZE_STRING);

            if (empty($from) && empty($to)) {
                $_SESSION['msg_nc'] = "<div class='alert alert-danger'><strong>Atenção ao Preenchimento dos Campos!</strong> Pelo menos 1 dos campos deve ser preenchido no formato especificado.</div>";
            } elseif (empty($to) && !empty($from)) {
                $to = $from;
            } elseif (empty($from) && !empty($to)) {
                $from = $to;
            } else {
                $from = filter_input(INPUT_POST, 'from', FILTER_SANITIZE_STRING);
                $to = filter_input(INPUT_POST, 'to', FILTER_SANITIZE_STRING);
            }

            $nc_from_exp = explode('/', $from);
            $nc_from_cod = strtoupper(reset($nc_from_exp));
            $nc_from_int = intval(next($nc_from_exp));
            $nc_from_year = intval(end($nc_from_exp));

            $nc_to_exp = explode('/', $to);
            $nc_to_cod = strtoupper(reset($nc_to_exp));
            $nc_to_int = intval(next($nc_to_exp));
            $nc_to_year = intval(end($nc_to_exp));

            if ($nc_from_cod != $store_code || $nc_to_cod != $store_code) {
                $_SESSION['msg_nc'] = "<div class='alert alert-danger'><strong>Bloqueio Acionado!</strong> Não é permitido visualizar registos de outras lojas.</div>";
            } else {
                $sql_nc = "
                    SELECT angariacoes.id angariacoes_id, angariacoes.data_insercao_ilist, id_angariacao.consultor id_consultor, CONSUL.nome nome_consultor, 
                    angariacoes.tipo_angariacao id_tipo_angariacao, tipo_angariacao.nome nome_tipo_angariacao, 
                    angariacoes.tipo_negocio id_tipo_negocio, tipo_negocio.nome nome_tipo_negocio, livro_registo.id_inicial_imovel, 
                    id_angariacao.id_imovel id_atual_imovel, id_angariacao.gestor id_gestor, GEST.nome nome_gestor, 
                    angariacoes.arruamento_imovel, angariacoes.num_porta_imovel, angariacoes.complemento_imovel, 
                    angariacoes.cod_postal_imovel, 
                    angariacoes.freguesia_imovel id_freguesia_imovel, LF_IMV.nome nome_freguesia_imovel, LF_IMV.concelho id_concelho_imovel, 
                    LC_IMV.nome nome_concelho_imovel, LF_IMV.distrito id_distrito_imovel, LD_IMV.nome nome_distrito_imovel, 
                    angariacoes.tipo_imovel id_tipo_imovel, tipo_imovel.nome nome_tipo_imovel, angariacoes.valor_inicial_imovel, 
                    livro_registo.estado_imovel id_estado_imovel, estado_imovel.nome nome_estado_imovel, livro_registo.num_contrato, 
                    livro_registo.data_cmi, livro_registo.regime id_regime, tipo_regime.nome nome_regime, livro_registo.duracao_contrato, 
                    livro_registo.conservatoria, livro_registo.ficha_crp, livro_registo.matriz_cpu, livro_registo.fracao, 
                    livro_registo.tipo_comissao id_tipo_comissao, tipo_comissao.nome nome_tipo_comissao, livro_registo.valor_comissao, 
                    livro_registo.nome_proprietario, livro_registo.nif_proprietario, livro_registo.email_proprietario, 
                    livro_registo.telefone_proprietario, livro_registo.arruamento_proprietario, livro_registo.num_porta_proprietario, 
                    livro_registo.complemento_proprietario, livro_registo.cod_postal_proprietario, 
                    livro_registo.freguesia_proprietario id_freguesia_proprietario, LF_PROP.nome nome_freguesia_proprietario, 
                    LF_PROP.concelho id_concelho_proprietario, LC_PROP.nome nome_concelho_proprietario, 
                    LF_PROP.distrito id_distrito_proprietario, LD_PROP.nome nome_distrito_proprietario, livro_registo.data_negocio, 
                    livro_registo.data_recisao, livro_registo.valor_negocio, livro_registo.comissao_recebida
                    FROM angariacoes
                    INNER JOIN livro_registo ON livro_registo.angariacao_id = angariacoes.id
                    INNER JOIN id_angariacao ON id_angariacao.angariacao_id = angariacoes.id
                    INNER JOIN colaboradores CONSUL ON CONSUL.id = id_angariacao.consultor
                    INNER JOIN tipo_angariacao ON tipo_angariacao.id = angariacoes.tipo_angariacao
                    INNER JOIN tipo_negocio ON tipo_negocio.id = angariacoes.tipo_negocio
                    INNER JOIN colaboradores GEST ON GEST.id = id_angariacao.gestor
                    INNER JOIN lista_freguesia LF_IMV ON LF_IMV.id = angariacoes.freguesia_imovel
                    INNER JOIN lista_concelho LC_IMV ON LC_IMV.id = LF_IMV.concelho
                    INNER JOIN lista_distrito LD_IMV ON LD_IMV.id = LF_IMV.distrito
                    INNER JOIN tipo_imovel ON tipo_imovel.id = angariacoes.tipo_imovel
                    INNER JOIN estado_imovel ON estado_imovel.id = livro_registo.estado_imovel
                    INNER JOIN tipo_regime ON tipo_regime.id = livro_registo.regime
                    INNER JOIN tipo_comissao ON tipo_comissao.id = livro_registo.tipo_comissao
                    INNER JOIN lista_freguesia LF_PROP ON LF_PROP.id = livro_registo.freguesia_proprietario
                    INNER JOIN lista_concelho LC_PROP ON LC_PROP.id = LF_PROP.concelho
                    INNER JOIN lista_distrito LD_PROP ON LD_PROP.id = LF_PROP.distrito
                    WHERE 
                     SUBSTRING_INDEX(num_contrato, '/', 1) = '$store_code' AND 
                     SUBSTRING_INDEX(SUBSTRING_INDEX(num_contrato, '/', -2), '/', 1) BETWEEN $nc_from_int AND $nc_to_int AND 
                     SUBSTRING_INDEX(num_contrato, '/', -1) BETWEEN $nc_from_year AND $nc_to_year
                    ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(num_contrato, '/', -2), '/', 1) AS UNSIGNED), SUBSTRING_INDEX(num_contrato, '/', -1)
                ";
                $result_nc = $conn->query($sql_nc);
                if ($result_nc->num_rows > 0) {
                    $resultado_nc = '';
                    $nc_ok = 1;
                } else {
                    $_SESSION['msg_nc'] = "<div class='alert alert-danger'><strong>Sem Resultados!</strong> Talvez tenha digitado um número de contrato que não existe, por favor verifique.</div>";
                }
            }
        }
    }
} else {
    if (!empty($mtd)) {
        if ($mtd == 'data') {
            $filtros = 'hidden';
            $data = '';
        } elseif ($mtd == 'nc') {
            $filtros = 'hidden';
            $nc = '';
        } else {
            $filtros = 'hidden';
            $erro = '';
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
                                    <a href="<?php setHome(); ?>/coordenacao">Coordenação</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <a href="<?php setHome(); ?>/coordenacao/livro-de-registos">Livro de Registos</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Consultar Livro de Registos</span>
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
                                                        <i class="fa fa-book font-blue"></i> <i class="fa fa-search font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase"> Consultar Livro de Registos</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row margin-bottom-40 margin-top-30 <?= $erro; ?>">
                                                        <div class="col-md-12">
                                                            <div class="note note-danger font-red-intense">
                                                                <h4 class="block bold">Erro Fatal!</h4>
                                                                <p class="bold"> Método de busca desconhecido. </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <a href="<?php setHome(); ?>/coordenacao/consultar-livro-de-registos" class="bold btn btn-block btn-lg blue"> Procurar Novamente
                                                                <i class="fa fa-undo"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row margin-bottom-40 margin-top-30 <?= $filtros; ?>">
                                                        <div class="col-md-6 margin-bottom-10">
                                                            <a href="<?php setHome(); ?>/coordenacao/consultar-livro-de-registos/data" class="bold btn btn-block btn-lg blue"> Procurar por Data
                                                                <i class="fa fa-calendar"></i>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <a href="<?php setHome(); ?>/coordenacao/consultar-livro-de-registos/nc" class="bold btn btn-block btn-lg purple"> Número de Contrato
                                                                <i class="fa fa-file-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row margin-bottom-40 margin-top-30 <?= $data; ?>">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['msg_data'])) {
                                                                echo $_SESSION['msg_data'];
                                                                unset($_SESSION['msg_data']);
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/coordenacao/consultar-livro-de-registos/data" method="post">
                                                                <div class="form-group">
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <div class="input-group input-large date-picker input-daterange" data-date-format="dd/mm/yyyy">
                                                                            <input type="text" class="form-control" name="from" readonly>
                                                                            <span class="input-group-addon"> Até </span>
                                                                            <input type="text" class="form-control" name="to" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <button name="filtro_baixa" type="submit" class="btn green-meadow btn-block bold uppercase">Confirmar e Buscar <i class="fa fa-search"></i></button>
                                                                    </div>
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <a href="<?php setHome(); ?>/coordenacao/consultar-livro-de-registos" class="uppercase bold btn btn-block red-thunderbird"> Limpar e Voltar
                                                                            <i class="fa fa-undo"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-md-12 <?= $resultado_data; ?>">
                                                            <div class="portlet light ">
                                                                <div class="portlet-title">
                                                                    <div class="caption font-dark">
                                                                        <i class="icon-magnifier font-dark"></i>
                                                                        <span class="caption-subject bold uppercase">Resultado da Busca</span>
                                                                    </div>
                                                                    <div class="tools"> </div>
                                                                </div>
                                                                <div class="portlet-body">
                                                                    <table class="table table-striped table-bordered table-hover" id="<?=$id_tabela_data;?>">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Data de Inserção</th>
                                                                                <th>Número do Contrato</th>
                                                                                <th>Nome do Consultor</th>
                                                                                <th>Tipo de Angariação</th>
                                                                                <th>Tipo de Negócio</th>
                                                                                <th>ID Inicial</th>
                                                                                <th>Arruamento do Imóvel</th>
                                                                                <th>Nº Porta Imóvel</th>
                                                                                <th>Complemento do Imóvel</th>
                                                                                <th>Código Postal do Imóvel</th>
                                                                                <th>Freguesia do Imóvel</th>
                                                                                <th>Concelho do Imóvel</th>
                                                                                <th>Distrito do Imóvel</th>
                                                                                <th>Tipo de Imóvel</th>
                                                                                <th>Valor Inicial do Imóvel</th>
                                                                                <th>Estado do Imóvel</th>
                                                                                <th>Data do CMI</th>
                                                                                <th>Tipo de Regime</th>
                                                                                <th>Duração do Contrato</th>
                                                                                <th>Conservatória</th>
                                                                                <th>Ficha (CRP)</th>
                                                                                <th>Matriz (CPU)</th>
                                                                                <th>Fração</th>
                                                                                <th>Tipo de Honorário</th>
                                                                                <th>Valor do Honorário</th>
                                                                                <th>Nome do Proprietário</th>
                                                                                <th>NIF do Proprietário</th>
                                                                                <th>Email do Proprietário</th>
                                                                                <th>Telefone do Proprietário</th>
                                                                                <th>Arruamento do Proprietário</th>
                                                                                <th>Nº Porta Proprietário</th>
                                                                                <th>Complemento Proprietário</th>
                                                                                <th>Código Postal do Proprietário</th>
                                                                                <th>Freguesia do Proprietário</th>
                                                                                <th>Concelho do Proprietário</th>
                                                                                <th>Distrito do Proprietário</th>
                                                                                <th>Data do Negócio</th>
                                                                                <th>Data da Rescisão</th>
                                                                                <th>Valor do Negócio</th>
                                                                                <th>Honorário Recebido</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            if ($data_ok == 1) {
                                                                                while ($row_data = $result_data->fetch_assoc()) {
                                                                                    $angariacoes_id = $row_data["angariacoes_id"];
                                                                                    $dt_insercao_ilist = $row_data["data_insercao_ilist"];
                                                                                    $data_insercao_ilist = (!empty($dt_insercao_ilist)) ? date_format(date_create($dt_insercao_ilist), "d/m/Y") : '';
                                                                                    $id_consultor = $row_data["id_consultor"];
                                                                                    $nome_consultor = $row_data["nome_consultor"];
                                                                                    $id_tipo_angariacao = $row_data["id_tipo_angariacao"];
                                                                                    $nome_tipo_angariacao = $row_data["nome_tipo_angariacao"];
                                                                                    $id_tipo_negocio = $row_data["id_tipo_negocio"];
                                                                                    $nome_tipo_negocio = $row_data["nome_tipo_negocio"];
                                                                                    $id_inicial_imovel = $row_data["id_inicial_imovel"];
                                                                                    $id_atual_imovel = $row_data["id_atual_imovel"];
                                                                                    $id_gestor = $row_data["id_gestor"];
                                                                                    $nome_gestor = $row_data["nome_gestor"];
                                                                                    $arruamento_imovel = $row_data["arruamento_imovel"];
                                                                                    $num_porta_imovel = $row_data["num_porta_imovel"];
                                                                                    $complemento_imovel = $row_data["complemento_imovel"];
                                                                                    $cod_postal_imovel = $row_data["cod_postal_imovel"];
                                                                                    $id_freguesia_imovel = $row_data["id_freguesia_imovel"];
                                                                                    $nome_freguesia_imovel = $row_data["nome_freguesia_imovel"];
                                                                                    $id_concelho_imovel = $row_data["id_concelho_imovel"];
                                                                                    $nome_concelho_imovel = $row_data["nome_concelho_imovel"];
                                                                                    $id_distrito_imovel = $row_data["id_distrito_imovel"];
                                                                                    $nome_distrito_imovel = $row_data["nome_distrito_imovel"];
                                                                                    $id_tipo_imovel = $row_data["id_tipo_imovel"];
                                                                                    $nome_tipo_imovel = $row_data["nome_tipo_imovel"];
                                                                                    $valor_inicial_imovel = number_format($row_data["valor_inicial_imovel"], 2, ',', '.');
                                                                                    $id_estado_imovel = $row_data["id_estado_imovel"];
                                                                                    $nome_estado_imovel = $row_data["nome_estado_imovel"];
                                                                                    $num_contrato = $row_data["num_contrato"];
                                                                                    $dt_cmi = $row_data["data_cmi"];
                                                                                    $data_cmi = (!empty($dt_cmi)) ? date_format(date_create($dt_cmi), "d/m/Y") : '';
                                                                                    $id_regime = $row_data["id_regime"];
                                                                                    $nome_regime = $row_data["nome_regime"];
                                                                                    $duracao_contrato = $row_data["duracao_contrato"] . " dias";
                                                                                    $conservatoria = $row_data["conservatoria"];
                                                                                    $ficha_crp = $row_data["ficha_crp"];
                                                                                    $matriz_cpu = $row_data["matriz_cpu"];
                                                                                    $fracao = $row_data["fracao"];
                                                                                    $id_tipo_comissao = $row_data["id_tipo_comissao"];
                                                                                    $nome_tipo_comissao = $row_data["nome_tipo_comissao"];
                                                                                    if ($id_tipo_comissao == 1) {
                                                                                        $valor_comissao = str_replace(".00", "", $row_data["valor_comissao"]) . "%";
                                                                                    } elseif ($id_tipo_comissao == 2) {
                                                                                        $valor_comissao = number_format($row_data["valor_comissao"], 2, ',', '.') . "€";
                                                                                    } else {
                                                                                        $valor_comissao = number_format($row_data["valor_comissao"], 2, ',', '.') . "%";
                                                                                    }
                                                                                    $nome_proprietario = $row_data["nome_proprietario"];
                                                                                    $nif_proprietario = $row_data["nif_proprietario"];
                                                                                    $email_proprietario = $row_data["email_proprietario"];
                                                                                    $telefone_proprietario = $row_data["telefone_proprietario"];
                                                                                    $arruamento_proprietario = $row_data["arruamento_proprietario"];
                                                                                    $num_porta_proprietario = $row_data["num_porta_proprietario"];
                                                                                    $complemento_proprietario = $row_data["complemento_proprietario"];
                                                                                    $cod_postal_proprietario = $row_data["cod_postal_proprietario"];
                                                                                    $id_freguesia_proprietario = $row_data["id_freguesia_proprietario"];
                                                                                    $nome_freguesia_proprietario = $row_data["nome_freguesia_proprietario"];
                                                                                    $id_concelho_proprietario = $row_data["id_concelho_proprietario"];
                                                                                    $nome_concelho_proprietario = $row_data["nome_concelho_proprietario"];
                                                                                    $id_distrito_proprietario = $row_data["id_distrito_proprietario"];
                                                                                    $nome_distrito_proprietario = $row_data["nome_distrito_proprietario"];
                                                                                    $dt_negocio = $row_data["data_negocio"];
                                                                                    $data_negocio = (!empty($dt_negocio)) ? date_format(date_create($dt_negocio), "d/m/Y") : '';
                                                                                    $dt_recisao = $row_data["data_recisao"];
                                                                                    $data_recisao = (!empty($dt_recisao)) ? date_format(date_create($dt_recisao), "d/m/Y") : '';
                                                                                    $valor_negocio = number_format($row_data["valor_negocio"], 2, ',', '.');
                                                                                    $comissao_recebida = number_format($row_data["comissao_recebida"], 2, ',', '.');
                                                                                    echo "
                                                                                        <tr>
                                                                                            <td>$data_insercao_ilist</td>
                                                                                            <td>$num_contrato</td>
                                                                                            <td>$nome_consultor</td>
                                                                                            <td>$nome_tipo_angariacao</td>
                                                                                            <td>$nome_tipo_negocio</td>
                                                                                            <td>$id_inicial_imovel</td>
                                                                                            <td>$arruamento_imovel</td>
                                                                                            <td>$num_porta_imovel</td>
                                                                                            <td>$complemento_imovel</td>
                                                                                            <td>$cod_postal_imovel</td>
                                                                                            <td>$nome_freguesia_imovel</td>
                                                                                            <td>$nome_concelho_imovel</td>
                                                                                            <td>$nome_distrito_imovel</td>
                                                                                            <td>$nome_tipo_imovel</td>
                                                                                            <td>$valor_inicial_imovel</td>
                                                                                            <td>$nome_estado_imovel</td>
                                                                                            <td>$data_cmi</td>
                                                                                            <td>$nome_regime</td>
                                                                                            <td>$duracao_contrato</td>
                                                                                            <td>$conservatoria</td>
                                                                                            <td>$ficha_crp</td>
                                                                                            <td>$matriz_cpu</td>
                                                                                            <td>$fracao</td>
                                                                                            <td>$nome_tipo_comissao</td>
                                                                                            <td>$valor_comissao</td>
                                                                                            <td>$nome_proprietario</td>
                                                                                            <td>$nif_proprietario</td>
                                                                                            <td>$email_proprietario</td>
                                                                                            <td>$telefone_proprietario</td>
                                                                                            <td>$arruamento_proprietario</td>
                                                                                            <td>$num_porta_proprietario</td>
                                                                                            <td>$complemento_proprietario</td>
                                                                                            <td>$cod_postal_proprietario</td>
                                                                                            <td>$nome_freguesia_proprietario</td>
                                                                                            <td>$nome_concelho_proprietario</td>
                                                                                            <td>$nome_distrito_proprietario</td>
                                                                                            <td>$data_negocio</td>
                                                                                            <td>$data_recisao</td>
                                                                                            <td>$valor_negocio</td>
                                                                                            <td>$comissao_recebida</td>
                                                                                        </tr>
                                                                                    ";
                                                                                }
                                                                            }
                                                                            ?>                                                                            
                                                                        </tbody>
                                                                    </table>                                                                    
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row margin-bottom-40 <?= $nc; ?>">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['msg_nc'])) {
                                                                echo $_SESSION['msg_nc'];
                                                                unset($_SESSION['msg_nc']);
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <h5 class="bold"><span class="font-red-intense">Atenção!</span> Digite os números de contratos iniciais e finais. Exemplo: <span class="font-red-intense"><?= $store_code; ?>/1/2018</span> Até <span class="font-red-intense"><?= $store_code; ?>/50/2018</span>. <span class="font-blue">Se for Apenas 1 Contrato, Pode Preencher Só 1 dos Campos</span>.</h5>
                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/coordenacao/consultar-livro-de-registos/nc" method="post">
                                                                <div class="form-group">
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <div class="input-group input-large">
                                                                            <input type="text" class="form-control" name="from" pattern="^[A-Za-z]{3}[/]{1}[0-9]*[/]{1}[0-9]{4}$">
                                                                            <span class="input-group-addon"> Até </span>
                                                                            <input type="text" class="form-control" name="to" pattern="^[A-Za-z]{3}[/]{1}[0-9]*[/]{1}[0-9]{4}$">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <button name="filtro_baixa" type="submit" class="btn green-meadow btn-block bold uppercase">Confirmar e Buscar <i class="fa fa-search"></i></button>
                                                                    </div>
                                                                    <div class="col-md-4 margin-bottom-10">
                                                                        <a href="<?php setHome(); ?>/coordenacao/consultar-livro-de-registos" class="uppercase bold btn btn-block red-thunderbird"> Limpar e Voltar
                                                                            <i class="fa fa-undo"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-md-12 <?= $resultado_nc; ?>">
                                                            <div class="portlet light ">
                                                                <div class="portlet-title">
                                                                    <div class="caption font-dark">
                                                                        <i class="icon-magnifier font-dark"></i>
                                                                        <span class="caption-subject bold uppercase">Resultado da Busca</span>
                                                                    </div>
                                                                    <div class="tools"> </div>
                                                                </div>
                                                                <div class="portlet-body">
                                                                    <table class="table table-striped table-bordered table-hover" id="<?=$id_tabela_nc;?>">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Número do Contrato</th>
                                                                                <th>Data de Inserção</th>
                                                                                <th>Nome do Consultor</th>
                                                                                <th>Tipo de Angariação</th>
                                                                                <th>Tipo de Negócio</th>
                                                                                <th>ID Inicial</th>
                                                                                <th>Arruamento do Imóvel</th>
                                                                                <th>Nº Porta Imóvel</th>
                                                                                <th>Complemento do Imóvel</th>
                                                                                <th>Código Postal do Imóvel</th>
                                                                                <th>Freguesia do Imóvel</th>
                                                                                <th>Concelho do Imóvel</th>
                                                                                <th>Distrito do Imóvel</th>
                                                                                <th>Tipo de Imóvel</th>
                                                                                <th>Valor Inicial do Imóvel</th>
                                                                                <th>Estado do Imóvel</th>
                                                                                <th>Data do CMI</th>
                                                                                <th>Tipo de Regime</th>
                                                                                <th>Duração do Contrato</th>
                                                                                <th>Conservatória</th>
                                                                                <th>Ficha (CRP)</th>
                                                                                <th>Matriz (CPU)</th>
                                                                                <th>Fração</th>
                                                                                <th>Tipo de Honorário</th>
                                                                                <th>Valor do Honorário</th>
                                                                                <th>Nome do Proprietário</th>
                                                                                <th>NIF do Proprietário</th>
                                                                                <th>Email do Proprietário</th>
                                                                                <th>Telefone do Proprietário</th>
                                                                                <th>Arruamento do Proprietário</th>
                                                                                <th>Nº Porta Proprietário</th>
                                                                                <th>Complemento Proprietário</th>
                                                                                <th>Código Postal do Proprietário</th>
                                                                                <th>Freguesia do Proprietário</th>
                                                                                <th>Concelho do Proprietário</th>
                                                                                <th>Distrito do Proprietário</th>
                                                                                <th>Data do Negócio</th>
                                                                                <th>Data da Rescisão</th>
                                                                                <th>Valor do Negócio</th>
                                                                                <th>Honorário Recebido</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            if ($nc_ok == 1) {
                                                                                while ($row_nc = $result_nc->fetch_assoc()) {
                                                                                    $angariacoes_id = $row_nc["angariacoes_id"];
                                                                                    $dt_insercao_ilist = $row_nc["data_insercao_ilist"];
                                                                                    $data_insercao_ilist = (!empty($dt_insercao_ilist)) ? date_format(date_create($dt_insercao_ilist), "d/m/Y") : '';
                                                                                    $id_consultor = $row_nc["id_consultor"];
                                                                                    $nome_consultor = $row_nc["nome_consultor"];
                                                                                    $id_tipo_angariacao = $row_nc["id_tipo_angariacao"];
                                                                                    $nome_tipo_angariacao = $row_nc["nome_tipo_angariacao"];
                                                                                    $id_tipo_negocio = $row_nc["id_tipo_negocio"];
                                                                                    $nome_tipo_negocio = $row_nc["nome_tipo_negocio"];
                                                                                    $id_inicial_imovel = $row_nc["id_inicial_imovel"];
                                                                                    $id_atual_imovel = $row_nc["id_atual_imovel"];
                                                                                    $id_gestor = $row_nc["id_gestor"];
                                                                                    $nome_gestor = $row_nc["nome_gestor"];
                                                                                    $arruamento_imovel = $row_nc["arruamento_imovel"];
                                                                                    $num_porta_imovel = $row_nc["num_porta_imovel"];
                                                                                    $complemento_imovel = $row_nc["complemento_imovel"];
                                                                                    $cod_postal_imovel = $row_nc["cod_postal_imovel"];
                                                                                    $id_freguesia_imovel = $row_nc["id_freguesia_imovel"];
                                                                                    $nome_freguesia_imovel = $row_nc["nome_freguesia_imovel"];
                                                                                    $id_concelho_imovel = $row_nc["id_concelho_imovel"];
                                                                                    $nome_concelho_imovel = $row_nc["nome_concelho_imovel"];
                                                                                    $id_distrito_imovel = $row_nc["id_distrito_imovel"];
                                                                                    $nome_distrito_imovel = $row_nc["nome_distrito_imovel"];
                                                                                    $id_tipo_imovel = $row_nc["id_tipo_imovel"];
                                                                                    $nome_tipo_imovel = $row_nc["nome_tipo_imovel"];
                                                                                    $valor_inicial_imovel = number_format($row_nc["valor_inicial_imovel"], 2, ',', '.');
                                                                                    $id_estado_imovel = $row_nc["id_estado_imovel"];
                                                                                    $nome_estado_imovel = $row_nc["nome_estado_imovel"];
                                                                                    $num_contrato = $row_nc["num_contrato"];
                                                                                    $dt_cmi = $row_nc["data_cmi"];
                                                                                    $data_cmi = (!empty($dt_cmi)) ? date_format(date_create($dt_cmi), "d/m/Y") : '';
                                                                                    $id_regime = $row_nc["id_regime"];
                                                                                    $nome_regime = $row_nc["nome_regime"];
                                                                                    $duracao_contrato = $row_nc["duracao_contrato"] . " dias";
                                                                                    $conservatoria = $row_nc["conservatoria"];
                                                                                    $ficha_crp = $row_nc["ficha_crp"];
                                                                                    $matriz_cpu = $row_nc["matriz_cpu"];
                                                                                    $fracao = $row_nc["fracao"];
                                                                                    $id_tipo_comissao = $row_nc["id_tipo_comissao"];
                                                                                    $nome_tipo_comissao = $row_nc["nome_tipo_comissao"];
                                                                                    if ($id_tipo_comissao == 1) {
                                                                                        $valor_comissao = str_replace(".00", "", $row_nc["valor_comissao"]) . "%";
                                                                                    } elseif ($id_tipo_comissao == 2) {
                                                                                        $valor_comissao = number_format($row_nc["valor_comissao"], 2, ',', '.') . "€";
                                                                                    } else {
                                                                                        $valor_comissao = number_format($row_nc["valor_comissao"], 2, ',', '.') . "%";
                                                                                    }
                                                                                    $nome_proprietario = $row_nc["nome_proprietario"];
                                                                                    $nif_proprietario = $row_nc["nif_proprietario"];
                                                                                    $email_proprietario = $row_nc["email_proprietario"];
                                                                                    $telefone_proprietario = $row_nc["telefone_proprietario"];
                                                                                    $arruamento_proprietario = $row_nc["arruamento_proprietario"];
                                                                                    $num_porta_proprietario = $row_nc["num_porta_proprietario"];
                                                                                    $complemento_proprietario = $row_nc["complemento_proprietario"];
                                                                                    $cod_postal_proprietario = $row_nc["cod_postal_proprietario"];
                                                                                    $id_freguesia_proprietario = $row_nc["id_freguesia_proprietario"];
                                                                                    $nome_freguesia_proprietario = $row_nc["nome_freguesia_proprietario"];
                                                                                    $id_concelho_proprietario = $row_nc["id_concelho_proprietario"];
                                                                                    $nome_concelho_proprietario = $row_nc["nome_concelho_proprietario"];
                                                                                    $id_distrito_proprietario = $row_nc["id_distrito_proprietario"];
                                                                                    $nome_distrito_proprietario = $row_nc["nome_distrito_proprietario"];
                                                                                    $dt_negocio = $row_nc["data_negocio"];
                                                                                    $data_negocio = (!empty($dt_negocio)) ? date_format(date_create($dt_negocio), "d/m/Y") : '';
                                                                                    $dt_recisao = $row_nc["data_recisao"];
                                                                                    $data_recisao = (!empty($dt_recisao)) ? date_format(date_create($dt_recisao), "d/m/Y") : '';
                                                                                    $valor_negocio = number_format($row_nc["valor_negocio"], 2, ',', '.');
                                                                                    $comissao_recebida = number_format($row_nc["comissao_recebida"], 2, ',', '.');
                                                                                    echo "
                                                                                        <tr>
                                                                                            <td>$num_contrato</td>
                                                                                            <td>$data_insercao_ilist</td>
                                                                                            <td>$nome_consultor</td>
                                                                                            <td>$nome_tipo_angariacao</td>
                                                                                            <td>$nome_tipo_negocio</td>
                                                                                            <td>$id_inicial_imovel</td>
                                                                                            <td>$arruamento_imovel</td>
                                                                                            <td>$num_porta_imovel</td>
                                                                                            <td>$complemento_imovel</td>
                                                                                            <td>$cod_postal_imovel</td>
                                                                                            <td>$nome_freguesia_imovel</td>
                                                                                            <td>$nome_concelho_imovel</td>
                                                                                            <td>$nome_distrito_imovel</td>
                                                                                            <td>$nome_tipo_imovel</td>
                                                                                            <td>$valor_inicial_imovel</td>
                                                                                            <td>$nome_estado_imovel</td>
                                                                                            <td>$data_cmi</td>
                                                                                            <td>$nome_regime</td>
                                                                                            <td>$duracao_contrato</td>
                                                                                            <td>$conservatoria</td>
                                                                                            <td>$ficha_crp</td>
                                                                                            <td>$matriz_cpu</td>
                                                                                            <td>$fracao</td>
                                                                                            <td>$nome_tipo_comissao</td>
                                                                                            <td>$valor_comissao</td>
                                                                                            <td>$nome_proprietario</td>
                                                                                            <td>$nif_proprietario</td>
                                                                                            <td>$email_proprietario</td>
                                                                                            <td>$telefone_proprietario</td>
                                                                                            <td>$arruamento_proprietario</td>
                                                                                            <td>$num_porta_proprietario</td>
                                                                                            <td>$complemento_proprietario</td>
                                                                                            <td>$cod_postal_proprietario</td>
                                                                                            <td>$nome_freguesia_proprietario</td>
                                                                                            <td>$nome_concelho_proprietario</td>
                                                                                            <td>$nome_distrito_proprietario</td>
                                                                                            <td>$data_negocio</td>
                                                                                            <td>$data_recisao</td>
                                                                                            <td>$valor_negocio</td>
                                                                                            <td>$comissao_recebida</td>
                                                                                        </tr>
                                                                                    ";
                                                                                }
                                                                            }
                                                                            ?>                                                                            
                                                                        </tbody>
                                                                    </table>                                                                    
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
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
        $.fn.datepicker.dates['en'] = {
            days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
            daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
            daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sá"],
            months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            today: "Hoje",
            clear: "Limpar",
            format: "dd/mm/yyyy",
            titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
            weekStart: 0
        };
</script>    

<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>