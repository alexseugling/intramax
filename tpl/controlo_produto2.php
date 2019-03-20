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

<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'nova-angariacao';
$user_id_loja = $_SESSION['user_id_loja'];
$user_nome_loja = $_SESSION['user_nome_loja'];
$mes_one = $mes_two = intval(date("m"));
$ano_one = $ano_two = date("Y");
switch ($mes_one) {
    case 1:
        $mes_one = "Janeiro";
        $mes_two = "Fevereiro";
        break;
    case 2:
        $mes_one = "Fevereiro";
        $mes_two = "Março";
        break;
    case 3:
        $mes_one = "Março";
        $mes_two = "Abril";
        break;
    case 04:
        $mes_one = "Abril";
        $mes_two = "Maio";
        break;
    case 5:
        $mes_one = "Maio";
        $mes_two = "Junho";
        break;
    case 6:
        $mes_one = "Junho";
        $mes_two = "Julho";
        break;
    case 7:
        $mes_one = "Julho";
        $mes_two = "Agosto";
        break;
    case 8:
        $mes_one = "Agosto";
        $mes_two = "Setembro";
        break;
    case 9:
        $mes_one = "Setembro";
        $mes_two = "Outubro";
        break;
    case 10:
        $mes_one = "Outubro";
        $mes_two = "Novembro";
        break;
    case 11:
        $mes_one = "Novembro";
        $mes_two = "Dezembro";
        break;
    case 12:
        $mes_one = "Dezembro";
        $mes_two = "Janeiro";
        $ano_two = date("Y") + 1;
        break;
    default:
        $mes_one = $mes_two = $ano_one = $ano_two = "Indefinido";
}
include_once("tpl/actions/conexao.php");
$sql_this_month = "
    SELECT CP.consultor id_consultor, C.nome nome_consultor, 
     (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho <= MONTH(NOW()) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) em_curso_mes_atual,
     (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho = MONTH(NOW()) AND CP_C.consultor = CP.consultor AND CP_C.estado = 2) fechada_mes_atual 
    FROM controlo_produto CP
    INNER JOIN colaboradores C ON C.id = CP.consultor
    WHERE CP.loja = $user_id_loja AND
    (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho <= MONTH(NOW()) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) > 0
    GROUP BY CP.consultor, C.nome ORDER BY C.nome
";
$result_this_month = $conn->query($sql_this_month);

$sql_next_month = "
SELECT CP.consultor id_consultor, C.nome nome_consultor, 
 (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho = (MONTH(NOW()) +1 ) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) em_curso_proximo_mes
FROM controlo_produto CP
INNER JOIN colaboradores C ON C.id = CP.consultor
WHERE loja = $user_id_loja AND 
(SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho = (MONTH(NOW()) +1 ) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) > 0
GROUP BY CP.consultor, C.nome ORDER BY C.nome
";
$result_next_month = $conn->query($sql_next_month);
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
                                                        <i class="fa fa-check font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase">Controlo de Produto</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row margin-bottom-40">
                                                        <div class="col-md-12 margin-bottom-15">
                                                            <a href="<?php setHome(); ?>/controlo_produto/consultor" class="btn btn-lg green-meadow btn-block bold uppercase"><i class="fa fa-filter"></i> Filtrar Consultor</a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h3 class="bold font-blue"><?= $user_nome_loja; ?></h3>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <h3 class="bold font-blue"><?= $mes_one; ?> de <?= $ano_one; ?></h3>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center"> Consultor </th>
                                                                            <th class="text-center"> Em Curso </th>
                                                                            <th class="text-center"> Fechadas </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($result_this_month->num_rows > 0) {
                                                                            while ($row_this_month = $result_this_month->fetch_assoc()) {
                                                                                $id_consultor = $row_this_month["id_consultor"];
                                                                                $nome_consultor = $row_this_month["nome_consultor"];
                                                                                $em_curso_mes_atual = $row_this_month["em_curso_mes_atual"];
                                                                                $fechada_mes_atual = $row_this_month["fechada_mes_atual"];
                                                                                $total_em_curso_mes_atual += $em_curso_mes_atual;
                                                                                $total_fechada_mes_atual += $fechada_mes_atual;
                                                                                echo "<tr class='text-center'>
                                                                                    <td><a href='./controlo_produto/consultor/$id_consultor'>$nome_consultor</a></td>
                                                                                    <td>$em_curso_mes_atual</td>
                                                                                    <td>$fechada_mes_atual</td>
                                                                                    </tr>
                                                                                ";
                                                                            }
                                                                        } else {
                                                                            echo "<tr class='text-center'><td colspan='3'>Neste mês não existem consultores com angariações em curso ou fechadas</td></tr> ";
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr class="text-center">
                                                                            <td> TOTAL </td>
                                                                            <td> <?= $total_em_curso_mes_atual; ?> </td>
                                                                            <td> <?= $total_fechada_mes_atual; ?> </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h3 class="bold font-green-meadow"><?= $user_nome_loja; ?></h3>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <h3 class="bold font-green-meadow"><?= $mes_two; ?> de <?= $ano_two; ?></h3>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center"> Consultor </th>
                                                                            <th class="text-center"> Em Curso </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($result_next_month->num_rows > 0) {
                                                                            while ($row_next_month = $result_next_month->fetch_assoc()) {
                                                                                $id_consultor = $row_next_month["id_consultor"];
                                                                                $nome_consultor = $row_next_month["nome_consultor"];
                                                                                $em_curso_proximo_mes = $row_next_month["em_curso_proximo_mes"];
                                                                                $total_em_curso_proximo_mes += $em_curso_proximo_mes;
                                                                                echo "<tr class='text-center'>
                                                                                    <td><a href='./controlo_produto/consultor/$id_consultor'>$nome_consultor</a></td>
                                                                                    <td>$em_curso_proximo_mes</td>
                                                                                    </tr>
                                                                                ";
                                                                            }
                                                                        } else {
                                                                            echo "<tr class='text-center'><td colspan='2'>No próximo mês não existem consultores com angariações em curso</td></tr> ";
                                                                        }
                                                                        ?>                                                                       
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr class="text-center">
                                                                            <td> TOTAL </td>
                                                                            <td> <?= $total_em_curso_proximo_mes; ?> </td>
                                                                        </tr>
                                                                    </tfoot>
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

<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>