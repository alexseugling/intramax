<?php
if (!isset($_SESSION['login'], $_SESSION['menu_qualidade']) && !isset($_SESSION['menu_admin'])) {
    ?>
    <script type="text/javascript">location.href = "<?php setHome(); ?>/tpl/actions/logout.php";</script>
    <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/tpl/actions/logout.php" /></noscript>
    <?php
    exit;
}
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'pesquisa-timelime';
$user_id_cargo = $_SESSION['user_id_cargo'];
if ($user_id_cargo == 9 || $user_id_cargo == 10 || $user_id_cargo == 8 || $user_id_cargo == 4) {
    $auth = TRUE;
} else {
    $auth = FALSE;
}

include_once("tpl/actions/conexao.php");

$resultado = $detalhe = "hidden";
$run = $info = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $busca = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_NUMBER_INT);
    $search = trim($busca);

    if ($search != "") {
        $sql_id = "
            SELECT id_angariacao.id_imovel, qlt_obs.comentario 
            FROM id_angariacao 
            INNER JOIN livro_registo ON livro_registo.angariacao_id = id_angariacao.angariacao_id 
            LEFT JOIN qlt_obs ON qlt_obs.angariacao_id = id_angariacao.angariacao_id 
            WHERE livro_registo.nif_proprietario = '{$search}' OR livro_registo.telefone_proprietario = '{$search}' OR id_angariacao.id_imovel = '{$search}' 
            GROUP BY id_angariacao.id_imovel
        ";
        $result = $conn->query($sql_id);
        if ($result->num_rows > 0) {
            $run = TRUE;
            $_SESSION['search_msg'] = "<div class='note note-success bold font-green-meadow'><p>IMÓVEL LOCALIZADO COM SUCESSO. </p></div>";
            $resultado = "";
        } else {
            $_SESSION['search_msg'] = "<div class='note note-danger bold font-red-intense'><p> NENHUM IMÓVEL LOCALIZADO. </p></div>";
        }
    } else {
        $_SESSION['search_msg'] = "<div class='note note-danger bold font-red-intense'><p> POR FAVOR DIGITE O NIF OU TELEFONE ASSOCIADO AO IMÓVEL DO CLIENTE. </p></div>";
    }
} else {
    $id_imovel = $url[2];
    if (isset($id_imovel) && $id_imovel != "") {
        $sql_info = "
            SELECT id_angariacao.angariacao_id, estado_imovel.nome estado_imovel, angariacoes.data_insercao_ilist, livro_registo.nome_proprietario, livro_registo.telefone_proprietario, livro_registo.nif_proprietario, info_loja.nome loja, CONCAT(angariacoes.arruamento_imovel, ', ', angariacoes.num_porta_imovel, ' - ', angariacoes.complemento_imovel, ', ', angariacoes.cod_postal_imovel, ' ', lista_freguesia.nome, ' - ', lista_concelho.nome) morada, colaboradores.nome consultor
            FROM livro_registo
            INNER JOIN angariacoes ON angariacoes.id = livro_registo.angariacao_id
            INNER JOIN id_angariacao ON id_angariacao.angariacao_id = angariacoes.id
            INNER JOIN lista_freguesia ON lista_freguesia.id = angariacoes.freguesia_imovel
            INNER JOIN lista_concelho ON lista_concelho.id = lista_freguesia.concelho
            INNER JOIN info_loja ON info_loja.id = id_angariacao.loja
            INNER JOIN colaboradores ON colaboradores.id = id_angariacao.consultor
            INNER JOIN estado_imovel ON estado_imovel.id = livro_registo.estado_imovel
            WHERE id_angariacao.id_imovel = '{$id_imovel}'
        ";
        $result_info = $conn->query($sql_info);
        if ($result_info->num_rows > 0) {
            $info = TRUE;
            $detalhe = "";
        } else {
            $_SESSION['search_msg'] = "<div class='note note-danger bold font-red-intense'><p> ESTE ID DE IMÓVEL NÃO EXISTE. </p></div>";
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
                                    <a href="<?php setHome(); ?>/qualidade">Qualidade</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Pesquisa (Timeline)</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- BEGIN Portlet PORTLET-->
                                        <div class="portlet box red-sunglo">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-search"></i> <i class="fa fa-phone"></i> Pesquisa (Timeline)
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row margin-bottom-25 ">
                                                    <div class="col-md-12">
                                                        <?php
                                                        if (isset($_SESSION['search_msg'])) {
                                                            echo $_SESSION['search_msg'];
                                                            unset($_SESSION['search_msg']);
                                                        }
                                                        ?>
                                                        <p>
                                                            <span class="erro">*</span> Digite o NIF / Telefone do Cliente ou o ID do Imóvel no Campo Abaixo e Clique no Botão "Ver Situação do Imóvel".
                                                        </p>
                                                        <form class="form-horizontal" role="form" action="<?php setHome(); ?>/qualidade/pesquisa-timeline" method="post">
                                                            <div class="form-group">
                                                                <div class="col-md-6 margin-bottom-10">
                                                                    <div>
                                                                        <input name="search" class="form-control form-control-inline" type="text" placeholder="Digite o NIF / Telefone do Cliente ou o ID do Imóvel" />
                                                                        <span class="help-block bold font-red-intense"> - Recomendamos o NIF para trazer todos os imóveis </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button name="filtro_baixa" type="submit" class="btn red btn-block bold uppercase">Ver Situação do Imóvel</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>                                                    
                                                </div>
                                                <div class="row margin-bottom-25 <?= $resultado; ?>">
                                                    <div class="col-md-12">
                                                        <div class="clearfix util-btn-margin-bottom-5">
                                                            <h4 class="block bold"><span class="font-blue">Resultado da Pesquisa</span> <span class="font-red-intense"> * Imóvel marcado a vermelho significa que não conseguiram contatar o cliente.</span></h4>
                                                            <?php
                                                            if ($run === TRUE) {
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $id_imovel = $row["id_imovel"];
                                                                    $comentario = $row["comentario"];
                                                                    if ($comentario != NULL) {
                                                                        $color = "red-intense";
                                                                    } else {
                                                                        $color = "blue";
                                                                    }
                                                                    ?>
                                                                    <a href="<?php setHome(); ?>/qualidade/pesquisa-timeline/<?= $id_imovel; ?>" target="_blank" class="btn btn-lg <?= $color; ?>"> <?= $id_imovel; ?> </a>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $tl15 = $tl60 = $tl120 = $tl130 = "hidden";
                                                $sql15 = "
                                                SELECT id_angariacao.angariacao_id, qlt_q15.data, qlt_q15.created_by, colaboradores.nome, qlt_q15.contrato_mediacao, qlt_q15.placa_imovel, qlt_q15.efic_consultor, qlt_q15.rec_grupo, qlt_q15.satisfacao, qlt_q15.obs_cliente
                                                FROM id_angariacao
                                                INNER JOIN qlt_q15 ON qlt_q15.angariacao_id = id_angariacao.angariacao_id
                                                INNER JOIN colaboradores ON colaboradores.id = qlt_q15.created_by
                                                WHERE id_angariacao.an_15 = 1 AND id_angariacao.id_imovel = '{$id_imovel}'
                                                ";
                                                $sql60 = "
                                                SELECT id_angariacao.angariacao_id, qlt_q60.data, qlt_q60.created_by, colaboradores.nome, qlt_q60.freq_contato, qlt_q60.quant_contatos, qlt_q60.last_contact, qlt_q60.freq_visitas, qlt_q60.quant_visitas, qlt_q60.classificacao_consultor, qlt_q60.recomendacao_marca, qlt_q60.satisfacao, qlt_q60.obs_cliente
                                                FROM id_angariacao
                                                INNER JOIN qlt_q60 ON qlt_q60.angariacao_id = id_angariacao.angariacao_id
                                                INNER JOIN colaboradores ON colaboradores.id = qlt_q60.created_by
                                                WHERE id_angariacao.an_60 = 1 AND id_angariacao.id_imovel = '{$id_imovel}'
                                                ";
                                                $sql120 = "
                                                SELECT id_angariacao.angariacao_id, qlt_q120.data, qlt_q120.created_by, colaboradores.nome, qlt_q120.freq_contato, qlt_q120.quant_contatos, qlt_q120.last_contact, qlt_q120.freq_visitas, qlt_q120.quant_visitas, qlt_q120.estudo_mercado, qlt_q120.estrategia_promocao, qlt_q120.estrategias, qlt_q120.classificacao_consultor, qlt_q120.recomendacao_marca, qlt_q120.satisfacao, qlt_q120.obs_cliente
                                                FROM id_angariacao
                                                INNER JOIN qlt_q120 ON qlt_q120.angariacao_id = id_angariacao.angariacao_id
                                                INNER JOIN colaboradores ON colaboradores.id = qlt_q120.created_by
                                                WHERE id_angariacao.an_120 = 1 AND id_angariacao.id_imovel = '{$id_imovel}'
                                                ";
                                                $sql130 = "
                                                SELECT id_angariacao.angariacao_id, qlt_q130.data, qlt_q130.id_imovel, qlt_q130.created_by, qlt_q130.loja, qlt_q130.foi_contatado, qlt_q130.estudo_mercado, qlt_q130.estrategia_promocao, qlt_q130.estrategias, qlt_q130.satisfacao, qlt_q130.obs_cliente
                                                FROM id_angariacao
                                                INNER JOIN qlt_q130 ON qlt_q130.angariacao_id = id_angariacao.angariacao_id
                                                WHERE id_angariacao.an_130 = 1 AND id_angariacao.id_imovel = '{$id_imovel}'
                                                ";

                                                $result15 = $conn->query($sql15);
                                                $result60 = $conn->query($sql60);
                                                $result120 = $conn->query($sql120);
                                                $result130 = $conn->query($sql130);

                                                if ($result15->num_rows > 0) {
                                                    $tl15 = "";
                                                    while ($row15 = $result15->fetch_assoc()) {
//                                                        $angariacao_id15 = $row15["angariacao_id"];
//                                                        $d15 = date_create($row15["data"]);
//                                                        $data15 = date_format($d15, 'd/m/Y H:i');
//                                                        $created_by15 = $row15["created_by"];
//                                                        $nome15 = $row15["nome"];
                                                        $contrato_mediacao15 = $row15["contrato_mediacao"];
                                                        $placa_imovel15 = $row15["placa_imovel"];
                                                        $efic_consultor15 = $row15["efic_consultor"];
                                                        $rec_grupo15 = $row15["rec_grupo"];
                                                        $satisfacao15 = $row15["satisfacao"];
                                                        $obs_cliente15 = $row15["obs_cliente"];
                                                        $mediacao_imo15 = ($contrato_mediacao15 == 1) ? "Sim" : "Não";
                                                        $placa15 = ($placa_imovel15 == 1) ? "Sim" : "Não";
                                                        if ($satisfacao15 == 1) {
                                                            $bg_color15 = "bg-green-meadow";
                                                            $over_color15 = "#1BBC9B";
                                                            $icon15 = "fa fa-smile-o";
                                                        } else {
                                                            $bg_color15 = "bg-red-intense";
                                                            $over_color15 = "#E35B5A";
                                                            $icon15 = "fa fa-frown-o";
                                                            $font_color15 = "font-red-intense";
                                                        }
                                                    }
                                                }
                                                if ($result60->num_rows > 0) {
                                                    $tl60 = "";
                                                    while ($row60 = $result60->fetch_assoc()) {
                                                        $freq_contato60 = $row60["freq_contato"];
                                                        $quant_contatos60 = $row60["quant_contatos"];
                                                        $last60 = date_create($row60["last_contact"]);
                                                        $last_contact60 = date_format($last60, 'd/m/Y');
                                                        $freq_visitas60 = $row60["freq_visitas"];
                                                        $quant_visitas60 = $row60["quant_visitas"];
                                                        $classificacao_consultor60 = $row60["classificacao_consultor"];
                                                        $recomendacao_marca60 = $row60["recomendacao_marca"];
                                                        $satisfacao60 = $row60["satisfacao"];
                                                        $obs_cliente60 = $row60["obs_cliente"];
                                                        if ($satisfacao60 == 1) {
                                                            $bg_color60 = "bg-green-meadow";
                                                            $over_color60 = "#1BBC9B";
                                                            $icon60 = "fa fa-smile-o";
                                                        } else {
                                                            $bg_color60 = "bg-red-intense";
                                                            $over_color60 = "#E35B5A";
                                                            $icon60 = "fa fa-frown-o";
                                                            $font_color60 = "font-red-intense";
                                                        }
                                                    }
                                                }
                                                if ($result120->num_rows > 0) {
                                                    $tl120 = "";
                                                    while ($row120 = $result120->fetch_assoc()) {
                                                        $freq_contato120 = $row120["freq_contato"];
                                                        $quant_contatos120 = $row120["quant_contatos"];
                                                        $last120 = date_create($row120["last_contact"]);
                                                        $last_contact120 = date_format($last120, 'd/m/Y');
                                                        $freq_visitas120 = $row120["freq_visitas"];
                                                        $quant_visitas120 = $row120["quant_visitas"];
                                                        $estudo_m120 = $row120["estudo_mercado"];
                                                        $estrategia_p120 = $row120["estrategia_promocao"];
                                                        $estrategias120 = $row120["estrategias"];
                                                        $classificacao_consultor120 = $row120["classificacao_consultor"];
                                                        $recomendacao_marca120 = $row120["recomendacao_marca"];
                                                        $satisfacao120 = $row120["satisfacao"];
                                                        $obs_cliente120 = $row120["obs_cliente"];
                                                        if ($satisfacao120 == 1) {
                                                            $bg_color120 = "bg-green-meadow";
                                                            $over_color120 = "#1BBC9B";
                                                            $icon120 = "fa fa-smile-o";
                                                        } else {
                                                            $bg_color120 = "bg-red-intense";
                                                            $over_color120 = "#E35B5A";
                                                            $icon120 = "fa fa-frown-o";
                                                            $font_color120 = "font-red-intense";
                                                        }
                                                        $estudo_mercado120 = ($estudo_m120 == 1) ? "Sim" : "Não";
                                                        $estrategia_promocao120 = ($estrategia_p120 == 1) ? "Sim" : "Não";
                                                    }
                                                }
                                                if ($result130->num_rows > 0) {
                                                    $tl130 = "";
                                                    while ($row130 = $result130->fetch_assoc()) {
                                                        $foi_C130 = $row130["foi_contatado"];
                                                        $estudo_m130 = $row130["estudo_mercado"];
                                                        $estrategia_p130 = $row130["estrategia_promocao"];
                                                        $estrategias130 = $row130["estrategias"];
                                                        $satisfacao130 = $row130["satisfacao"];
                                                        $obs_cliente130 = $row130["obs_cliente"];
                                                        $foi_contatado130 = ($foi_C130 == 1) ? "Sim" : "Não";
                                                        $estudo_mercado130 = ($estudo_m130 == 1) ? "Sim" : "Não";
                                                        $estrategia_promocao130 = ($estrategia_p130 == 1) ? "Sim" : "Não";
                                                        if ($satisfacao130 == 1) {
                                                            $bg_color130 = "bg-green-meadow";
                                                            $over_color130 = "#1BBC9B";
                                                            $icon130 = "fa fa-smile-o";
                                                        } else {
                                                            $bg_color130 = "bg-red-intense";
                                                            $over_color130 = "#E35B5A";
                                                            $icon130 = "fa fa-frown-o";
                                                            $font_color130 = "font-red-intense";
                                                        }
                                                    }
                                                }
                                                ?>
                                                <div class="row margin-bottom-25 <?= $detalhe; ?>">
                                                    <div class="col-md-12">
                                                        <?php
                                                        if (isset($_SESSION['msg_obs'])) {
                                                            echo $_SESSION['msg_obs'];
                                                            unset($_SESSION['msg_obs']);
                                                        }
                                                        ?>
                                                        <h4 class="block bold">Tarefas Realizadas, Tarefas em Aberto e Situação do Imóvel</h4>
                                                        <ul class="nav nav-pills">
                                                            <li class="bg-blue">
                                                                <a class="font-white" href="#info" data-toggle="tab" onMouseOver="this.style.backgroundColor = '#3598DC'"> Informações do Imóvel </a>
                                                            </li>
                                                            <li class="<?= $bg_color15; ?> <?= $tl15; ?>">
                                                                <a class="font-white" href="#q-15" data-toggle="tab" onMouseOver="this.style.backgroundColor = '<?= $over_color15; ?>'"> Questionário 15 Dias <i class="<?= $icon15; ?>"></i></a>
                                                            </li>
                                                            <li class="<?= $bg_color60; ?> <?= $tl60; ?>">
                                                                <a class="font-white" href="#q-60" data-toggle="tab" onMouseOver="this.style.backgroundColor = '<?= $over_color60; ?>'"> Questionário 60 Dias <i class="<?= $icon60; ?>"></i> </a>
                                                            </li>
                                                            <li class="<?= $bg_color120; ?> <?= $tl120; ?>">
                                                                <a class="font-white" href="#q-120" data-toggle="tab" onMouseOver="this.style.backgroundColor = '<?= $over_color120; ?>'"> Questionário 120 Dias <i class="<?= $icon120; ?>"></i></a>
                                                            </li>
                                                            <li class="<?= $bg_color130; ?> <?= $tl130; ?>">
                                                                <a class="font-white" href="#q-130" data-toggle="tab" onMouseOver="this.style.backgroundColor = '<?= $over_color130; ?>'"> Questionário 130 Dias <i class="<?= $icon130; ?>"></i></a>
                                                            </li>
                                                            <?php
                                                            if ($auth === TRUE) {
                                                                ?>
                                                                <li class="bg-purple-seance">
                                                                    <a class="font-white" href="#g-obs" data-toggle="tab" onMouseOver="this.style.backgroundColor = '#9A12B3'"> Observações <i class="fa fa-comment"></i></a>
                                                                </li>
                                                                <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <?php
                                                            if ($info === TRUE) {
                                                                $baixa_preco = "hidden";
                                                                $sql_baixa = "
                                                                    SELECT baixa_preco.data data_baixa, baixa_preco.novo_valor_imovel, baixa_preco.ultimo_valor_imovel, baixa_preco.by_user, colaboradores.nome baixa_por
                                                                    FROM baixa_preco
                                                                    INNER JOIN colaboradores ON colaboradores.id = baixa_preco.by_user
                                                                    INNER JOIN id_angariacao ON id_angariacao.angariacao_id = baixa_preco.angariacao_id
                                                                    WHERE id_angariacao.id_imovel = '{$id_imovel}' AND baixa_preco.novo_valor_imovel <> baixa_preco.ultimo_valor_imovel
                                                                    ORDER BY baixa_preco.id DESC
                                                                ";
                                                                $result_baixa = $conn->query($sql_baixa);
                                                                if ($result_baixa->num_rows > 0) {
                                                                    $baixa_preco = "";
                                                                }
                                                                while ($row_info = $result_info->fetch_assoc()) {
                                                                    $id_ang_info = $row_info["angariacao_id"];
                                                                    $estado_imovel = $row_info["estado_imovel"];
                                                                    $insercao_ilist = date_create($row_info["data_insercao_ilist"]);
                                                                    $data_insercao_ilist = date_format($insercao_ilist, 'd/m/Y H:i');
                                                                    $nome_proprietario = $row_info["nome_proprietario"];
                                                                    $telefone_proprietario = $row_info["telefone_proprietario"];
                                                                    $nif_proprietario = $row_info["nif_proprietario"];
                                                                    $loja = $row_info["loja"];
                                                                    $morada = $row_info["morada"];
                                                                    $consultor = $row_info["consultor"];

                                                                    $_SESSION['angariacao_id'] = $id_ang_info;
                                                                    $_SESSION['id_imovel'] = $id_imovel;
                                                                    ?>
                                                                    <div class="tab-pane fade active in" id="info">
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold font-red-intense"> Situação do Imóvel </span><br><?= $estado_imovel; ?>                                                                 
                                                                        </div>
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold"> Data de Inserção </span><br><?= $data_insercao_ilist; ?>                                                                    
                                                                        </div>
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold"> Nome do Cliente </span><br><?= $nome_proprietario; ?>                                                                   
                                                                        </div>
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold"> Telefone do Cliente </span><br><?= $telefone_proprietario; ?>                                                                     
                                                                        </div>
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold font-red-intense"> NIF do Cliente </span><br><?= $nif_proprietario; ?>                                                                   
                                                                        </div>
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold"> Morada do Imóvel </span><br><?= $morada; ?>                                                                     
                                                                        </div>
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold"> Agência Remax </span><br><?= $loja; ?>                                                                   
                                                                        </div>
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold"> Nome do Consultor </span><br><?= $consultor; ?>                                                                     
                                                                        </div>
                                                                        <div class="col-md-4 margin-bottom-20">
                                                                            <span class="bold"> ID do Imóvel </span><br><?= $id_imovel; ?>                                                                       
                                                                        </div>
                                                                        <div class="row <?= $baixa_preco; ?>">
                                                                            <div class="col-xs-12">
                                                                                <div class="mt-element-ribbon">
                                                                                    <div class="ribbon ribbon-clip ribbon-color-primary uppercase">
                                                                                        <div class="ribbon-sub ribbon-clip"></div> Histórico de Baixa de Preço 
                                                                                    </div>
                                                                                    <div class="table-scrollable">
                                                                                        <table class="table table-striped table-hover">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th> Data da Baixa </th>
                                                                                                    <th> Valor Atual </th>
                                                                                                    <th> Valor Antigo </th>
                                                                                                    <th> Efetuado por </th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php
                                                                                                while ($row_baixa = $result_baixa->fetch_assoc()) {
                                                                                                    $data_b = date_create($row_baixa['data_baixa']);
                                                                                                    $data_baixa = date_format($data_b, 'd/m/Y H:i');
                                                                                                    $novo_valor_imovel = $row_baixa['novo_valor_imovel'];
                                                                                                    $ultimo_valor_imovel = $row_baixa['ultimo_valor_imovel'];
                                                                                                    $by_user = $row_baixa['by_user'];
                                                                                                    $baixa_por = $row_baixa['baixa_por'];
                                                                                                    ?>
                                                                                                    <tr>
                                                                                                        <td> <?= $data_baixa ?> </td>
                                                                                                        <td class="valores"> <?= $novo_valor_imovel ?>  </td>
                                                                                                        <td class="valores"> <?= $ultimo_valor_imovel ?>  </td>
                                                                                                        <td> <?= $baixa_por ?> </td>
                                                                                                    </tr>
                                                                                                    <?php
                                                                                                }
                                                                                                ?> 
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <div class="tab-pane fade" id="q-15">
                                                                <div class="col-md-6 margin-bottom-20">
                                                                    <span class="bold"> O Contrato de Mediação Imobiliária foi recebido? </span><br>
                                                                    <?= $mediacao_imo15 ?>                                                                 
                                                                </div>
                                                                <div class="col-md-6 margin-bottom-20">
                                                                    <span class="bold"> Tem a Placa no Imóvel? </span><br>
                                                                    <?= $placa15 ?>                                                                 
                                                                </div>
                                                                <div class="col-md-6 margin-bottom-20 <?= $font_color15; ?>">
                                                                    <span class="bold"> Classificação da eficiência e profissionalismo do consultor? </span><br>
                                                                    <?= $efic_consultor15 ?>                                                                  
                                                                </div>
                                                                <div class="col-md-6 margin-bottom-20 <?= $font_color15; ?>">
                                                                    <span class="bold"> Recomendaria os serviços prestados pelo Grupo Remax Vantagem? </span><br>
                                                                    <?= $rec_grupo15 ?>                                                                  
                                                                </div>
                                                                <div class="col-md-12 margin-bottom-20">
                                                                    <span class="bold"> Sugestões do Cliente </span><br>
                                                                    <?= $obs_cliente15 ?>                                                                  
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="q-60">
                                                                <div class="col-md-12 margin-bottom-20">
                                                                    <span class="bold">
                                                                        Durante o último mês, com que frequência foi contatado pelo consultor para realizar o ponto de situação sobre o seu imóvel (Excluindo o agendamento de visitas)? 
                                                                    </span>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span class="bold">Intervalo de Tempo:</span> <?= $freq_contato60; ?>                                                               
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <span class="bold">Quantidade de Vezes:</span> <?= $quant_contatos60; ?>                                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 margin-bottom-20">
                                                                    <span class="bold"> Quando foi a última vez que foi contatado? </span><br>
                                                                    <?= $last_contact60; ?>                                                                
                                                                </div>
                                                                <div class="col-md-8 margin-bottom-20">
                                                                    <span class="bold"> Ainda durante o último mês, com que frequência foi contatado para agendamento de visitas? </span>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span class="bold">Intervalo de Tempo:</span> <?= $freq_visitas60; ?>                                                               
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <span class="bold">Quantidade de Vezes:</span> <?= $quant_visitas60; ?>                                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 margin-bottom-20 <?= $font_color60; ?>">
                                                                    <span class="bold"> Classificação da eficiência e profissionalismo do consultor? </span><br>
                                                                    <?= $classificacao_consultor60; ?>                                                                 
                                                                </div>
                                                                <div class="col-md-6 margin-bottom-20 <?= $font_color60; ?>">
                                                                    <span class="bold"> Recomendaria os serviços prestados pelo Grupo Remax Vantagem? </span><br>
                                                                    <?= $recomendacao_marca60; ?>                                                                 
                                                                </div>
                                                                <div class="col-md-12 margin-bottom-20">
                                                                    <span class="bold"> Sugestões do Cliente </span><br>
                                                                    <?= $obs_cliente60; ?>                                                                
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="q-120">
                                                                <div class="col-md-12 margin-bottom-20">
                                                                    <span class="bold">
                                                                        Durante o último mês, com que frequência foi contatado pelo consultor para realizar o ponto de situação sobre o seu imóvel (Excluindo o agendamento de visitas)? 
                                                                    </span>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span class="bold">Intervalo de Tempo:</span> <?= $freq_contato120; ?>                                                                
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <span class="bold">Quantidade de Vezes:</span> <?= $quant_contatos120; ?>                                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 margin-bottom-20">
                                                                    <span class="bold"> Quando foi a última vez que foi contatado? </span><br>
                                                                    <?= $last_contact120; ?>                                                                 
                                                                </div>
                                                                <div class="col-md-8 margin-bottom-20">
                                                                    <span class="bold"> Ainda durante o último mês, com que frequência foi contatado para agendamento de visitas? </span>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span class="bold">Intervalo de Tempo:</span> <?= $freq_visitas120; ?>                                                               
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <span class="bold">Quantidade de Vezes:</span> <?= $quant_visitas120; ?>                                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 margin-bottom-20">
                                                                    <span class="bold">
                                                                        Uma vez que o seu imóvel está a ser promovido há mais de 120 dias sem os resultados desejados, foi-lhe apresentado pelo consultor: 
                                                                    </span>
                                                                    <div class="row">
                                                                        <div class="col-md-4 <?= $font_color120; ?>">
                                                                            <span class="bold">Novo Estudo de Mercado?</span><br> <?= $estudo_mercado120; ?>                                                               
                                                                        </div>
                                                                        <div class="col-md-4 <?= $font_color120; ?>">
                                                                            <span class="bold">Nova Estratégia de Promoção?</span><br> <?= $estrategia_promocao120; ?>                                                                 
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <span class="bold">Se Sim, Quais Estratégias?</span><br> <?= $estrategias120; ?>                                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 margin-bottom-20">
                                                                    <span class="bold"> Classificação da eficiência e profissionalismo do consultor? </span><br>
                                                                    <?= $classificacao_consultor120; ?>                                                                 
                                                                </div>
                                                                <div class="col-md-6 margin-bottom-20">
                                                                    <span class="bold"> Recomendaria os serviços prestados pelo Grupo Remax Vantagem? </span><br>
                                                                    <?= $recomendacao_marca120; ?>                                                                 
                                                                </div>
                                                                <div class="col-md-12 margin-bottom-20">
                                                                    <span class="bold"> Sugestões do Cliente </span><br>
                                                                    <?= $obs_cliente120; ?>                                                              
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="q-130">
                                                                <div class="col-md-12 margin-bottom-20 <?= $font_color130; ?>">
                                                                    <span class="bold">
                                                                        No segmento de inquérito anterior, foi contatado pelo consultor? 
                                                                    </span>
                                                                    <br> <?= $foi_contatado130; ?>
                                                                </div>
                                                                <div class="col-md-12 margin-bottom-10">
                                                                    <span class="bold">
                                                                        Foi apresentado pelo consultor: 
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-12 margin-bottom-20">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <span class="bold">Novo Estudo de Mercado?</span><br> <?= $estudo_mercado130; ?>                                                              
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <span class="bold">Nova Estratégia de Promoção?</span><br> <?= $estrategia_promocao130; ?>                                                                 
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <span class="bold">Se Sim, Quais Estratégias?</span><br> <?= $estrategias130; ?>                                                                 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            if ($auth === TRUE) {
                                                                $sql_obs = "
                                                                SELECT qlt_obs.created_by, colaboradores.nome, qlt_obs.data, qlt_obs.comentario, qlt_obs.field 
                                                                FROM qlt_obs 
                                                                INNER JOIN colaboradores ON qlt_obs.created_by = colaboradores.id
                                                                WHERE qlt_obs.angariacao_id = {$id_ang_info}
                                                                ORDER BY qlt_obs.id DESC
                                                                ";
                                                                ?>
                                                                <div class="tab-pane fade" id="g-obs">
                                                                    <div class="col-md-12 margin-bottom-20">
                                                                        <h4 class="bold"><span class="font-blue">Adicionar comentários a este imóvel. </span><span class="font-red-intense">* Visível apenas à pessoas autorizadas</span></h4>
                                                                        <form class="form-horizontal margin-bottom-40" role="form" action="<?php setHome(); ?>/tpl/actions/ptl_observacoes.php" method="post">
                                                                            <div class="form-group">
                                                                                <div class="col-md-12">
                                                                                    <textarea name="obs" class="form-control" rows="3"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-actions">
                                                                                <button name="btn_obs" type="submit" class="btn blue-hoki btn-block bold uppercase">Adicionar Comentário</button>
                                                                            </div>
                                                                        </form>

                                                                        <?php
                                                                        $result_obs = mysqli_query($conn, $sql_obs);
                                                                        if (mysqli_num_rows($result_obs) > 0) {
                                                                            while ($row_obs = mysqli_fetch_assoc($result_obs)) {
                                                                                $criado_por = $row_obs["created_by"];
                                                                                $nome = $row_obs["nome"];
                                                                                $timestamp = date_create($row_obs["data"]);
                                                                                $data = date_format($timestamp, 'd/m/Y H:i');
                                                                                $comentario = $row_obs["comentario"];
                                                                                $field = $row_obs["field"];
                                                                                switch ($field) {
                                                                                    case 15:
                                                                                        $etiqueta = "Questionário de 15 Dias";
                                                                                        break;
                                                                                    case 60:
                                                                                        $etiqueta = "Questionário de 60 Dias";
                                                                                        break;
                                                                                    case 120:
                                                                                        $etiqueta = "Questionário de 120 Dias";
                                                                                        break;
                                                                                    case 130:
                                                                                        $etiqueta = "Questionário de 130 Dias";
                                                                                        break;
                                                                                    case 'prv':
                                                                                        $etiqueta = "Privado à Direcção";
                                                                                        break;
                                                                                }
                                                                                ?>
                                                                                <div class="timeline">
                                                                                    <div class="timeline-item">
                                                                                        <div class="timeline-badge">
                                                                                            <div class="timeline-icon">
                                                                                                <i class="fa fa-comment font-blue-hoki"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="timeline-body">
                                                                                            <div class="timeline-body-arrow"> </div>
                                                                                            <div class="timeline-body-head">
                                                                                                <div class="timeline-body-head-caption">
                                                                                                    Criado por: <span class="timeline-body-alerttitle font-blue bold"><?= $nome; ?></span>
                                                                                                    <span class="timeline-body-time font-grey-cascade"><?= $data; ?></span> 
                                                                                                    <span class="font-blue">Etiqueta: </span><?= $etiqueta; ?>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="timeline-body-content">
                                                                                                <span class="font-red-intense">
                                                                                                    <?= $comentario; ?>
                                                                                                </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <div class='alert alert-info text-center'>
                                                                                <span class="bold"> AINDA NÃO EXISTE NENHUM COMENTÁRIO OU OBSERVAÇÃO RELACIONADO A ESTE IMÓVEL. </span>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        ?>
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
                                        <!-- END Portlet PORTLET-->
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
    $('.valores').mask("###.###,00", {reverse: true});
    $('.numeros').mask("###", {reverse: true});
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>