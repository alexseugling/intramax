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
$page = 'questionario-60-dias';
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
        $telefone_proprietario = $row_det['telefone_proprietario'];
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
                                    <a href="<?php setHome(); ?>/qualidade/angariacoes-60-dias">Angariações 60 Dias</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Questionário de 60 Dias</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row" id="inquerito">
                                        <div class="col-md-12">
                                            <div class="portlet box blue">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-question-circle"></i> Questionário de 60 Dias
                                                    </div>
                                                </div>
                                                <div class="portlet-body">        
                                                    <?php
                                                    $btn_color = $icon = $font_color = "";
                                                    $inquerito_anterior = "hidden";
                                                    $sql_quest15 = "
                                                        SELECT id_angariacao.angariacao_id, qlt_q15.data, qlt_q15.created_by, colaboradores.nome, qlt_q15.contrato_mediacao, qlt_q15.placa_imovel, qlt_q15.efic_consultor, qlt_q15.rec_grupo, qlt_q15.satisfacao, qlt_q15.obs_cliente
                                                        FROM id_angariacao
                                                        INNER JOIN qlt_q15 ON qlt_q15.angariacao_id = id_angariacao.angariacao_id
                                                        INNER JOIN colaboradores ON colaboradores.id = qlt_q15.created_by
                                                        WHERE id_angariacao.an_15 = 1 AND id_angariacao.id_imovel = '{$id_imovel}'
                                                    ";
                                                    $result_quest15 = $conn->query($sql_quest15);
                                                    if ($result_quest15->num_rows > 0) {
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
                                                                $btn_color = "btn green-meadow";
                                                                $icon = "fa fa-smile-o";
                                                            } else {
                                                                $btn_color = "btn red-intense";
                                                                $icon = "fa fa-frown-o";
                                                                $font_color = "font-red-intense";
                                                            }
                                                            ?>
                                                            <!-- INICIA QUESTIONÁRIO ANTERIOR-->
                                                            <div class="row margin-bottom-15 <?= $inquerito_anterior; ?>">
                                                                <div class="col-xs-12">
                                                                    <h4 class="bold">Visualizar Questionário Anterior</h4>
                                                                    <a data-toggle="modal" href="#inquerito-15" class="<?= $btn_color; ?>">
                                                                        15 Dias
                                                                        <i class="<?= $icon; ?>"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
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
                                                                                <div class="col-md-6 margin-bottom-20 <?= $font_color; ?>">
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
                                                            <!-- FINALIZA QUESTIONÁRIO ANTERIOR-->
                                                            <?php
                                                        }
                                                    }
                                                    ?>
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
                                                            <form role="form" class="margin-bottom-20" action="<?php setHome(); ?>/tpl/actions/questionario_60.php" method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p>
                                                                                Durante o último mês, com que frequência foi contatado pelo consultor 
                                                                                para realizar o ponto de situação sobre o seu imóvel 
                                                                                (Excluindo o agendamento de visitas)?
                                                                            </p>
                                                                        </div>                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <select class="form-control" name="freq_contato">
                                                                                <option value="">Selecione uma opção</option>
                                                                                <option value="nunca">Nunca</option>
                                                                                <option value="semanal">Semanalmente</option>
                                                                                <option value="quinzenal">Quinzenalmente</option>
                                                                                <option value="mensal">Mensalmente</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <input type="text" name="quant_contatos" class="form-control numeros" placeholder="Quantas Vezes?">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Quando foi a última vez que foi contatado? 
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <input name="last_contact" class="form-control date-picker" type="text" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Ainda durante o último mês, com que frequência foi contatado para agendamento de visitas?
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <select class="form-control" name="freq_visitas">
                                                                                            <option value="">Selecione uma opção</option>
                                                                                            <option value="nunca">Nunca</option>
                                                                                            <option value="semanal">Semanalmente</option>
                                                                                            <option value="quinzenal">Quinzenalmente</option>
                                                                                            <option value="mensal">Mensalmente</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <input type="text" name="quant_visitas" class="form-control numeros" placeholder="Quantas Vezes?">
                                                                                    </div>
                                                                                </div>
                                                                            </div>                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Classificação da eficiência e profissionalismo do consultor - de 1 a 10, em como 1 é nada satisfeito e 10 muito satisfeito 
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <input class="nota" type="text" name="classificacao_consultor" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Recomendaria os serviços prestados pelo Grupo Remax Vantagem - de 1 a 10, em como 1 é nada satisfeito e 10 muito satisfeito 
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <input class="nota" type="text" name="recomendacao_marca" />
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
                                                                            <button name="filtro" type="submit" class="btn blue btn-block bold uppercase">Finalizar Questionário</button>
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
<script type="text/javascript" src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-mask/jquery.mask.min.js"></script>
<script>
    $('.numeros').mask("###", {reverse: true});
    $('.valores').mask("###.###,00", {reverse: true});
</script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.pt.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.date-picker').datepicker({
        format: "dd/mm/yyyy",
        weekStart: 0,
        language: "pt"
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
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>