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
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'questionario-15-dias';
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
                                    <a href="<?php setHome(); ?>/qualidade/angariacoes-15-dias">Angariações 15 Dias</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Questionário de 15 Dias</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row" id="inquerito">
                                        <div class="col-md-12">
                                            <div class="portlet box green-meadow">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-question-circle"></i> Questionário de 15 Dias
                                                    </div>
                                                </div>
                                                <div class="portlet-body"> 
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
                                                            <form role="form" class="margin-bottom-20" action="<?php setHome(); ?>/tpl/actions/questionario_15.php" method="post">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p class="bold uppercase">Verificar se o Compromisso Remax foi cumprido</p>
                                                                        </div>                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                O Contrato de Mediação Imobiliária foi recebido?
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <div class="mt-radio-inline">
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="1" name="mediacao_imobiliaria"> Sim
                                                                                    <span></span>
                                                                                </label>
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="0" name="mediacao_imobiliaria"> Não
                                                                                    <span></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">
                                                                                Tem a Placa no Imóvel?
                                                                                <span class="erro">* </span>
                                                                            </label>
                                                                            <div class="mt-radio-inline">
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="1" name="placa_imovel"> Sim
                                                                                    <span></span>
                                                                                </label>
                                                                                <label class="mt-radio mt-radio-outline">
                                                                                    <input type="radio" value="0" name="placa_imovel"> Não
                                                                                    <span></span>
                                                                                </label>
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
                                                                            <button name="filtro" type="submit" class="btn green-meadow btn-block bold uppercase">Finalizar Questionário</button>
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
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>