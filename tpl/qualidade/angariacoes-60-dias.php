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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<style>
    .blink {
        animation: blinker 0.9s linear infinite;
        color:#FFFFFF;
        background-color:#FF0000;
        font-weight: bold;
        padding: 5px;
    }
    /*    @keyframes blinker {  
            50% { opacity: 0; }
        }*/
</style>
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'angariacoes-60-dias';

include_once("tpl/actions/conexao.php");
$limit = 60;
$sql = "
    SELECT angariacoes.id id_angariacao, angariacoes.data_insercao_ilist entrada_ilist, id_angariacao.loja id_loja, 
    info_loja.nome nome_loja, id_angariacao.id_imovel id_imovel, id_angariacao.consultor id_consultor, colaboradores.nome nome_consultor, 
    livro_registo.nome_proprietario nome_cliente, livro_registo.telefone_proprietario telefone_cliente, livro_registo.estado_imovel estado_imovel
    FROM angariacoes
    INNER JOIN id_angariacao ON id_angariacao.angariacao_id = angariacoes.id
    INNER JOIN info_loja ON info_loja.id = id_angariacao.loja
    INNER JOIN colaboradores ON colaboradores.id = id_angariacao.consultor
    INNER JOIN livro_registo ON livro_registo.angariacao_id = angariacoes.id
    WHERE id_angariacao.an_{$limit} = 0 AND livro_registo.estado_imovel = 1 AND angariacoes.data_insercao_ilist < DATE_ADD(NOW(), INTERVAL -{$limit} DAY) AND angariacoes.data_insercao_ilist > DATE_ADD(NOW(), INTERVAL -91 DAY)
    ";
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
                                    <span>Angariações com 60 Dias</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        if (isset($_SESSION['msg_out'])) {
                                            echo $_SESSION['msg_out'];
                                            unset($_SESSION['msg_out']);
                                        }
                                        ?>
                                        <div class="portlet box blue">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-calendar-check-o"></i> Angariações com 60 Dias
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <table class="table table-striped table-bordered table-hover table-header-fixed tabela" id="sample_1">
                                                    <thead>
                                                        <tr>
                                                            <th> Inserida há  </th>
                                                            <th> Agência </th>
                                                            <th> ID da Angariação </th>
                                                            <th> Consultor </th>
                                                            <th> Nome do Cliente  </th>
                                                            <th> Contato do Cliente  </th>
                                                            <th> Ações </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $result = $conn->query($sql);

                                                        if ($result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                $blink = "blink";
                                                                $today = date("Y-m-d H:i:s");
                                                                $data_insercao_ilist = $row['entrada_ilist'];
                                                                $data1 = new DateTime($data_insercao_ilist);
                                                                $data2 = new DateTime($today);

                                                                $id_angariacao = $row['id_angariacao'];
                                                                $id_loja = $row['id_loja'];
                                                                $nome_loja = $row['nome_loja'];
                                                                $id_imovel = $row['id_imovel'];
                                                                $id_consultor = $row['id_consultor'];
                                                                $nome_consultor = $row['nome_consultor'];
                                                                $nome_cliente = $row['nome_cliente'];
                                                                $telefone_cliente = $row['telefone_cliente'];
                                                                $estado_imovel = $row['estado_imovel'];

                                                                $intervalo = $data1->diff($data2);
                                                                $diferenca = $intervalo->format('%a');
                                                                if ($diferenca <= $limit) {
                                                                    $blink = "";
                                                                }
                                                                ?>
                                                                <tr class="odd gradeX">
                                                                    <td class="text-center"> <div class="<?= $blink; ?>"><?= $diferenca; ?> Dias</div> </td>
                                                                    <td> <?= $nome_loja; ?> </td>
                                                                    <td> <?= $id_imovel; ?> </td>
                                                                    <td> <?= $nome_consultor; ?> </td>
                                                                    <td> <?= $nome_cliente; ?> </td>
                                                                    <td> <?= $telefone_cliente; ?> </td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button class="btn btn-xs blue dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Ações
                                                                                <i class="fa fa-angle-down"></i>
                                                                            </button>
                                                                            <ul class="dropdown-menu" role="menu">
                                                                                <li>
                                                                                    <a href="<?php setHome(); ?>/qualidade/questionario-60-dias/<?= $id_imovel; ?>">
                                                                                        <i class="fa fa-question-circle"></i> Questionário </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="<?php setHome(); ?>/qualidade/observacoes/<?= $id_imovel; ?>/60">
                                                                                        <i class="fa fa-phone-square"></i> Não Atende </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <td colspan="7" class="text-center bold font-green-meadow"> Parabéns! não tens imóveis pendentes. </td>                                                                
                                                            </tr>
                                                            <?php
                                                        }
                                                        $conn->close();
                                                        ?>
                                                    </tbody>
                                                </table>
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
<script src="<?php setHome(); ?>/tpl/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/ui-general.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>