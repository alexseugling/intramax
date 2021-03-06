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
$page = 'questionario-130-dias';
include_once("tpl/actions/conexao.php");

$id_imovel = $url[2];

$sql_det = "
    SELECT id_angariacao.data data_insercao_ilist, id_angariacao.loja id_loja, info_loja.nome nome_loja, id_angariacao.id_imovel, id_angariacao.angariacao_id, id_angariacao.consultor id_consultor, colaboradores.nome nome_consultor, livro_registo.nome_proprietario, livro_registo.telefone_proprietario
    FROM id_angariacao
    INNER JOIN info_loja on info_loja.id = id_angariacao.loja
    INNER JOIN colaboradores on colaboradores.id = id_angariacao.consultor
    INNER JOIN livro_registo on livro_registo.angariacao_id = id_angariacao.angariacao_id
    WHERE id_angariacao.id_imovel = '{$id_imovel}'
";
$result_det = $conn->query($sql_det);

if ($result_det->num_rows > 0) {
    $_SESSION['id_imovel'] = $id_imovel;
    $erro = $baixa_preco = "hidden";
    $encontrado = "";
    while ($row_det = $result_det->fetch_assoc()) {
        $data_ilist = date_create($row_det['data_insercao_ilist']);
        $data_insercao_ilist = date_format($data_ilist, 'd/m/Y');
        $id_loja = $row_det['id_loja'];
        $_SESSION['id_loja'] = $id_loja;
        $nome_loja = $row_det['nome_loja'];
        $id_imovel = $row_det['id_imovel'];
        $_SESSION['angariacao_id'] = $row_det['angariacao_id'];
        $id_consultor = $row_det['id_consultor'];
        $nome_consultor = $row_det['nome_consultor'];
        $nome_proprietario = $row_det['nome_proprietario'];
        $_SESSION['nome_proprietario'] = $nome_proprietario;
        $telefone_proprietario = $row_det['telefone_proprietario'];
        $_SESSION['telefone_proprietario'] = $telefone_proprietario;
    }
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
} else {
    $erro = "";
    $encontrado = $baixa_preco = "hidden";
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
                                    <a href="<?php setHome(); ?>/qualidade/angariacoes-130-dias">Angariações 130 Dias</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Questionário de 130 Dias</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row" id="inquerito">
                                        <div class="col-md-12">
                                            <div class="portlet box dark">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-question-circle"></i> Questionário de 130 Dias
                                                    </div>
                                                </div>
                                                <div class="portlet-body">    
                                                    <?php
                                                    $btn_color15 = $icon15 = $font_color15 = "";
                                                    $btn_color60 = $icon60 = $font_color60 = "";
                                                    $btn_color120 = $icon120 = $font_color120 = "";
                                                    $inquerito_anterior = $btn_15 = $btn_60 = $btn_120 = "hidden";
                                                    $sql_quest15 = "
                                                        SELECT id_angariacao.angariacao_id, qlt_q15.data, qlt_q15.created_by, colaboradores.nome, qlt_q15.contrato_mediacao, qlt_q15.placa_imovel, qlt_q15.efic_consultor, qlt_q15.rec_grupo, qlt_q15.satisfacao, qlt_q15.obs_cliente
                                                        FROM id_angariacao
                                                        INNER JOIN qlt_q15 ON qlt_q15.angariacao_id = id_angariacao.angariacao_id
                                                        INNER JOIN colaboradores ON colaboradores.id = qlt_q15.created_by
                                                        WHERE id_angariacao.an_15 = 1 AND id_angariacao.id_imovel = '{$id_imovel}'
                                                    ";
                                                    $sql_quest60 = "
                                                        SELECT id_angariacao.angariacao_id, qlt_q60.data, qlt_q60.created_by, colaboradores.nome, qlt_q60.freq_contato, qlt_q60.quant_contatos, qlt_q60.last_contact, qlt_q60.freq_visitas, qlt_q60.quant_visitas, qlt_q60.classificacao_consultor, qlt_q60.recomendacao_marca, qlt_q60.satisfacao, qlt_q60.obs_cliente
                                                        FROM id_angariacao
                                                        INNER JOIN qlt_q60 ON qlt_q60.angariacao_id = id_angariacao.angariacao_id
                                                        INNER JOIN colaboradores ON colaboradores.id = qlt_q60.created_by
                                                        WHERE id_angariacao.an_60 = 1 AND id_angariacao.id_imovel = '{$id_imovel}'
                                                    ";
                                                    $sql_quest120 = "
                                                        SELECT id_angariacao.angariacao_id, qlt_q120.data, qlt_q120.created_by, colaboradores.nome, qlt_q120.freq_contato, qlt_q120.quant_contatos, qlt_q120.last_contact, qlt_q120.freq_visitas, qlt_q120.quant_visitas, qlt_q120.estudo_mercado, qlt_q120.estrategia_promocao, qlt_q120.estrategias, qlt_q120.classificacao_consultor, qlt_q120.recomendacao_marca, qlt_q120.satisfacao, qlt_q120.obs_cliente
                                                        FROM id_angariacao
                                                        INNER JOIN qlt_q120 ON qlt_q120.angariacao_id = id_angariacao.angariacao_id
                                                        INNER JOIN colaboradores ON colaboradores.id = qlt_q120.created_by
                                                        WHERE id_angariacao.an_120 = 1 AND id_angariacao.id_imovel = '{$id_imovel}'
                                                    ";
                                                    $result_quest15 = $conn->query($sql_quest15);
                                                    $result_quest60 = $conn->query($sql_quest60);
                                                    $result_quest120 = $conn->query($sql_quest120);
                                                    if ($result_quest15->num_rows > 0) {
                                                        $btn_15 = "";
                                                        $inquerito_anterior = "";
                                                        while ($row_quest15 = $result_quest15->fetch_assoc()) {
                                                            $angariacao_id15 = $row_quest15['id'];
                                                            $data15 = date_create($row_quest15['data']);
                                                            $data_quest15 = date_format($data15, 'd/m/Y');
                                                            $created_by15 = $row_quest15['created_by'];
                                                            $nome_consultor15 = $row_quest15['nome'];
                                                            $contrato_mediacao15 = $row_quest15['contrato_mediacao'];
                                                            $placa_imovel15 = $row_quest15['placa_imovel'];
                                                            $efic_consultor15 = $row_quest15['efic_consultor'];
                                                            $rec_grupo15 = $row_quest15['rec_grupo'];
                                                            $satisfacao15 = $row_quest15['satisfacao'];
                                                            $obs_cliente15 = $row_quest15['obs_cliente'];
                                                            $mediacao_imo15 = ($contrato_mediacao15 == 1) ? "Sim" : "Não";
                                                            $placa15 = ($placa_imovel15 == 1) ? "Sim" : "Não";
                                                            if ($satisfacao15 == 1) {
                                                                $btn_color15 = "btn green-meadow";
                                                                $icon15 = "fa fa-smile-o";
                                                            } else {
                                                                $btn_color15 = "btn red-intense";
                                                                $icon15 = "fa fa-frown-o";
                                                                $font_color15 = "font-red-intense";
                                                            }
                                                            ?>
                                                            <!-- INICIA MODAL QUESTIONÁRIO 15-->
                                                            <div class="modal fade bs-modal-lg" id="inquerito-15" tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                            <h4 class="modal-title">Questionário de 15 Dias - Preenchido por <?= $nome_consultor15; ?> em <?= $data_quest15; ?></h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6 margin-bottom-20">
                                                                                    <span class="bold"> O Contrato de Mediação Imobiliária foi recebido? </span><br>
                                                                                    <?= $mediacao_imo15; ?>                                                                
                                                                                </div>
                                                                                <div class="col-md-6 margin-bottom-20">
                                                                                    <span class="bold"> Tem a Placa no Imóvel? </span><br>
                                                                                    <?= $placa15; ?>                                                              
                                                                                </div>
                                                                                <div class="col-md-6 margin-bottom-20 <?= $font_color15; ?>">
                                                                                    <span class="bold"> Classificação da eficiência e profissionalismo do consultor? </span><br>
                                                                                    <?= $efic_consultor15; ?>                                                         
                                                                                </div>
                                                                                <div class="col-md-6 margin-bottom-20">
                                                                                    <span class="bold"> Recomendaria os serviços prestados pelo Grupo Remax Vantagem? </span><br>
                                                                                    <?= $rec_grupo15; ?>                                                          
                                                                                </div>
                                                                                <div class="col-md-12 margin-bottom-20">
                                                                                    <span class="bold"> Sugestões do Cliente </span><br>
                                                                                    <?= $obs_cliente15; ?>                                                             
                                                                                </div>                                                                        
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn green-meadow" data-dismiss="modal">Fechar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- FINALIZA MODAL QUESTIONÁRIO 15-->
                                                            <?php
                                                        }
                                                    }
                                                    if ($result_quest60->num_rows > 0) {
                                                        $inquerito_anterior = "";
                                                        $btn_60 = "";
                                                        while ($row_quest60 = $result_quest60->fetch_assoc()) {
                                                            $angariacao_id60 = $row_quest60['id'];
                                                            $data60 = date_create($row_quest60['data']);
                                                            $data_quest60 = date_format($data60, 'd/m/Y');
                                                            $created_by60 = $row_quest60['created_by'];
                                                            $nome_consultor60 = $row_quest60['nome'];
                                                            $freq_contato60 = $row_quest60['freq_contato'];
                                                            $quant_contatos60 = $row_quest60['quant_contatos'];
                                                            $last_c60 = date_create($row_quest60['last_contact']);
                                                            $last_contact60 = date_format($last_c60, 'd/m/Y');
                                                            $freq_visitas60 = $row_quest60['freq_visitas'];
                                                            $quant_visitas60 = $row_quest60['quant_visitas'];
                                                            $classificacao_consultor60 = $row_quest60['classificacao_consultor'];
                                                            $recomendacao_marca60 = $row_quest60['recomendacao_marca'];
                                                            $satisfacao60 = $row_quest60['satisfacao'];
                                                            $obs_cliente60 = $row_quest60['obs_cliente'];

                                                            if ($satisfacao60 == 1) {
                                                                $btn_color60 = "btn green-meadow";
                                                                $icon60 = "fa fa-smile-o";
                                                            } else {
                                                                $btn_color60 = "btn red-intense";
                                                                $icon60 = "fa fa-frown-o";
                                                                $font_color60 = "font-red-intense";
                                                            }
                                                            ?>
                                                            <!-- INICIA MODAL QUESTIONÁRIO 60-->
                                                            <div class="modal fade bs-modal-lg" id="inquerito-60" tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                            <h4 class="modal-title">Questionário de 60 Dias - Preenchido por <?= $nome_consultor60; ?> em <?= $data_quest60; ?></h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 margin-bottom-20 <?= $font_color60; ?>">
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
                                                                                <div class="col-md-6 margin-bottom-20">
                                                                                    <span class="bold"> Classificação da eficiência e profissionalismo do consultor? </span><br>
                                                                                    <?= $classificacao_consultor60; ?>                                                                
                                                                                </div>
                                                                                <div class="col-md-6 margin-bottom-20">
                                                                                    <span class="bold"> Recomendaria os serviços prestados pelo Grupo Remax Vantagem? </span><br>
                                                                                    <?= $recomendacao_marca60; ?>                                                                 
                                                                                </div>
                                                                                <div class="col-md-12 margin-bottom-20">
                                                                                    <span class="bold"> Sugestões do Cliente </span><br>
                                                                                    <?= $obs_cliente60; ?>                                                                
                                                                                </div>                                                                        
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn green-meadow" data-dismiss="modal">Fechar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- FINALIZA MODAL QUESTIONÁRIO 60-->
                                                            <?php
                                                        }
                                                    }
                                                    if ($result_quest120->num_rows > 0) {
                                                        $inquerito_anterior = "";
                                                        $btn_120 = "";
                                                        while ($row_quest120 = $result_quest120->fetch_assoc()) {
                                                            $angariacao_id120 = $row_quest120['id'];
                                                            $data120 = date_create($row_quest120['data']);
                                                            $data_quest120 = date_format($data120, 'd/m/Y');
                                                            $created_by120 = $row_quest120['created_by'];
                                                            $nome_consultor120 = $row_quest120['nome'];
                                                            $freq_contato120 = $row_quest120['freq_contato'];
                                                            $quant_contatos120 = $row_quest120['quant_contatos'];
                                                            $last_c120 = date_create($row_quest120['last_contact']);
                                                            $last_contact120 = date_format($last_c120, 'd/m/Y');
                                                            $freq_visitas120 = $row_quest120['freq_visitas'];
                                                            $quant_visitas120 = $row_quest120['quant_visitas'];
                                                            $estudo_m120 = $row_quest120['estudo_mercado'];
                                                            $estrategia_p120 = $row_quest120['estrategia_promocao'];
                                                            $estrategias120 = $row_quest120['estrategias'];
                                                            $classificacao_consultor120 = $row_quest120['classificacao_consultor'];
                                                            $recomendacao_marca120 = $row_quest120['recomendacao_marca'];
                                                            $satisfacao120 = $row_quest120['satisfacao'];
                                                            $obs_cliente120 = $row_quest120['obs_cliente'];

                                                            if ($satisfacao120 == 1) {
                                                                $btn_color120 = "btn green-meadow";
                                                                $icon120 = "fa fa-smile-o";
                                                            } else {
                                                                $btn_color120 = "btn red-intense";
                                                                $icon120 = "fa fa-frown-o";
                                                                $font_color120 = "font-red-intense";
                                                            }
                                                            $estudo_mercado120 = ($estudo_m120 == 1) ? "Sim" : "Não";
                                                            $estrategia_promocao120 = ($estrategia_p120 == 1) ? "Sim" : "Não";
                                                            ?>
                                                            <!-- INICIA MODAL QUESTIONÁRIO 120-->
                                                            <div class="modal fade bs-modal-lg" id="inquerito-120" tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                            <h4 class="modal-title">Questionário de 120 Dias - Preenchido por <?= $nome_consultor120; ?> em <?= $data_quest120; ?></h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
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
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn green-meadow" data-dismiss="modal">Fechar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- FINALIZA MODAL QUESTIONÁRIO 120-->
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <!-- INICIA QUESTIONÁRIO ANTERIOR-->
                                                    <div class="row margin-bottom-15 <?= $inquerito_anterior; ?>">
                                                        <div class="col-xs-12">
                                                            <h4 class="bold">Visualizar Questionários Anteriores</h4>
                                                            <a data-toggle="modal" href="#inquerito-15" class="<?= $btn_color15; ?> <?= $btn_15; ?>">
                                                                15 Dias
                                                                <i class="<?= $icon15; ?>"></i>
                                                            </a>
                                                            <a data-toggle="modal" href="#inquerito-60" class="<?= $btn_color60; ?> <?= $btn_60; ?>">
                                                                60 Dias
                                                                <i class="<?= $icon60; ?>"></i>
                                                            </a>
                                                            <a data-toggle="modal" href="#inquerito-120" class="<?= $btn_color120; ?> <?= $btn_120; ?>">
                                                                120 Dias
                                                                <i class="<?= $icon120; ?>"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <!-- FINALIZA QUESTIONÁRIO ANTERIOR-->
                                                    <div class="row <?= $erro; ?>">
                                                        <div class="col-xs-12">
                                                            <div class="note note-danger bold font-red-intense">
                                                                <p> ESTE ID DE IMÓVEL NÃO EXISTE! POR FAVOR VERIFIQUE. </p>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <div class="row <?= $encontrado; ?>">
                                                        <div class="col-xs-12">
                                                            <div class="mt-element-ribbon">
                                                                <div class="ribbon ribbon-clip ribbon-color-primary uppercase">
                                                                    <div class="ribbon-sub ribbon-clip"></div> Informações do Imóvel 
                                                                </div>
                                                                <div class="table-scrollable">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th> Inserido dia </th>
                                                                                <th> Agência </th>
                                                                                <th> Consultor </th>
                                                                                <th> ID do Imóvel </th>
                                                                                <th> Nome do Cliente </th>
                                                                                <th> Contacto do Cliente </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td> <?= $data_insercao_ilist; ?> </td>
                                                                                <td> <?= $nome_loja; ?> </td>
                                                                                <td> <?= $nome_consultor; ?> </td>
                                                                                <td> <?= $id_imovel; ?> </td>
                                                                                <td> <?= $nome_proprietario; ?> </td>
                                                                                <td> <?= $telefone_proprietario; ?> </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
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
                                                    <div class="row <?= $encontrado; ?>">
                                                        <div class="col-md-12">
                                                            <h4 class="bold font-red-thunderbird">Inquérito</h4>
                                                            <?php
                                                            if (isset($_SESSION['msg_err'])) {
                                                                echo $_SESSION['msg_err'];
                                                                unset($_SESSION['msg_err']);
                                                            }
                                                            ?>
                                                            <form role="form" class="margin-bottom-20" action="<?php setHome(); ?>/tpl/actions/questionario_130.php" method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                No segmento de inquérito anterior, foi contatado por alguém do Grupo Vantagem?
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <div class="mt-radio-inline">
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="1" name="foi_contatado"> Sim
                                                                                    <span></span>
                                                                                </label>
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="0" name="foi_contatado"> Não
                                                                                    <span></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p class="bold font-red-intense">
                                                                                Caso SIM, foi-lhe apresentado:
                                                                            </p>
                                                                        </div>                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Novo Estudo de Mercado?
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <div class="mt-radio-inline">
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="1" name="estudo_mercado"> Sim
                                                                                    <span></span>
                                                                                </label>
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="0" name="estudo_mercado"> Não
                                                                                    <span></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Nova Estratégia de Promoção?
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <div class="mt-radio-inline">
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="1" name="estrategia_promocao"> Sim
                                                                                    <span></span>
                                                                                </label>
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="0" name="estrategia_promocao"> Não
                                                                                    <span></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Se Sim, Quais Estratégias?
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <div class="mt-checkbox-inline">
                                                                                <label class="mt-checkbox mt-checkbox-outline">
                                                                                    <input name="estrategias[]" type="checkbox" value="Baixa de Preço"> Baixa de Preço
                                                                                    <span></span>
                                                                                </label>
                                                                                <label class="mt-checkbox mt-checkbox-outline">
                                                                                    <input name="estrategias[]" type="checkbox" value="Publicidade"> Publicidade
                                                                                    <span></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Outras Estratégias?
                                                                            </label>
                                                                            <input name="outras_estrategias" type="text" class="form-control" placeholder="Digite Outras Não Listadas?">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Sugestões do Cliente</label>
                                                                            <textarea name="sugestao" class="form-control" rows="3"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <button name="filtro" type="submit" class="btn dark btn-block bold uppercase">Finalizar Questionário</button>
                                                                        </div>
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
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/ion.rangeslider/js/ion.rangeSlider.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-ion-sliders.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.pt.min.js" type="text/javascript"></script>
<script type="text/javascript">
        $('.date-picker').datepicker({
            format: "dd/mm/yyyy",
            weekStart: 0,
            language: "pt",
            autoclose: true
        });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        // Handler for .ready() called.
        $('html, body').animate({
            scrollTop: $('#inquerito').offset().top
        }, 'slow');
    });
</script>
<script type="text/javascript" src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-mask/jquery.mask.min.js"></script>
<script>
    $('.valores').mask("###.###,00", {reverse: true});
    $('.numeros').mask("###", {reverse: true});
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>