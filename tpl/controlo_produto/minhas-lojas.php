<?php
if (!isset($_SESSION['login'], $_SESSION['menu_cotrole_produto_dz']) && !isset($_SESSION['menu_admin'])) {
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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'minhas-lojas';

$user_id = $_SESSION['user_id'];
switch ($user_id) {
    // User Jorge Santos
    case 73:
        $opt_lojas = '
            <option value=""></option>
            <option value="3">Vantagem 2 (Alverca)</option>
            <option value="4">Vantagem 3 (Arruda)</option>
            <option value="5">Vantagem 4 (Vila Franca)</option>
            <option value="10">Vantagem Oeste (Torres Vedras)</option>
            <option value="11">Vantagem 8 (Póvoa)</option>
            <option value="16">Vantagem Agraço (Sobral)</option>
            <option value="18">Vantagem Lezíria (Samora Correia)</option>
        ';
        $in_lojas = "3, 4, 5, 10, 11, 16, 18";
        break;
    // User Alex Seugling
    case 604:
        $opt_lojas = '
            <option value=""></option>
            <option value="2">Vantagem (Carregado)</option>
            <option value="3">Vantagem 2 (Alverca)</option>
            <option value="4">Vantagem 3 (Arruda)</option>
            <option value="5">Vantagem 4 (Vila Franca)</option>
            <option value="6">Vantagem Central (Lisboa)</option>
            <option value="7">Vantagem 6 (Santarém)</option>
            <option value="8">Vantagem Seven (Odivelas)</option>
            <option value="9">Vantagem Avenida (Lisboa)</option>
            <option value="10">Vantagem Oeste (Torres Vedras)</option>
            <option value="11">Vantagem 8 (Póvoa)</option>
            <option value="12">Vantagem Invicta (Porto)</option>
            <option value="13">Vantagem Gaya (Gaia)</option>
            <option value="14">Vantagem Atlântico (Cascais)</option>
            <option value="15">Vantagem Maior (Rio Maior)</option>
            <option value="16">Vantagem Agraço (Sobral)</option>
            <option value="17">Vantagem Planície (Azambuja)</option>
            <option value="18">Vantagem Lezíria (Samora Correia)</option>
            <option value="19">Vantagem Real (Caldas)</option>
        ';
        $in_lojas = "2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19";
        break;
    // User Fernando Augusto
    case 451:
        $opt_lojas = '
            <option value=""></option>
            <option value="2">Vantagem (Carregado)</option>
            <option value="3">Vantagem 2 (Alverca)</option>
            <option value="4">Vantagem 3 (Arruda)</option>
            <option value="5">Vantagem 4 (Vila Franca)</option>
            <option value="6">Vantagem Central (Lisboa)</option>
            <option value="7">Vantagem 6 (Santarém)</option>
            <option value="8">Vantagem Seven (Odivelas)</option>
            <option value="9">Vantagem Avenida (Lisboa)</option>
            <option value="10">Vantagem Oeste (Torres Vedras)</option>
            <option value="11">Vantagem 8 (Póvoa)</option>
            <option value="12">Vantagem Invicta (Porto)</option>
            <option value="13">Vantagem Gaya (Gaia)</option>
            <option value="14">Vantagem Atlântico (Cascais)</option>
            <option value="15">Vantagem Maior (Rio Maior)</option>
            <option value="16">Vantagem Agraço (Sobral)</option>
            <option value="17">Vantagem Planície (Azambuja)</option>
            <option value="18">Vantagem Lezíria (Samora Correia)</option>
            <option value="19">Vantagem Real (Caldas)</option>
        ';
        $in_lojas = "2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19";
        break;
    // User Diogo Severino
    case 22:
        $opt_lojas = '
            <option value=""></option>
            <option value="2">Vantagem (Carregado)</option>
            <option value="3">Vantagem 2 (Alverca)</option>
            <option value="4">Vantagem 3 (Arruda)</option>
            <option value="5">Vantagem 4 (Vila Franca)</option>
            <option value="6">Vantagem Central (Lisboa)</option>
            <option value="7">Vantagem 6 (Santarém)</option>
            <option value="8">Vantagem Seven (Odivelas)</option>
            <option value="9">Vantagem Avenida (Lisboa)</option>
            <option value="10">Vantagem Oeste (Torres Vedras)</option>
            <option value="11">Vantagem 8 (Póvoa)</option>
            <option value="12">Vantagem Invicta (Porto)</option>
            <option value="13">Vantagem Gaya (Gaia)</option>
            <option value="14">Vantagem Atlântico (Cascais)</option>
            <option value="15">Vantagem Maior (Rio Maior)</option>
            <option value="16">Vantagem Agraço (Sobral)</option>
            <option value="17">Vantagem Planície (Azambuja)</option>
            <option value="18">Vantagem Lezíria (Samora Correia)</option>
            <option value="19">Vantagem Real (Caldas)</option>
        ';
        $in_lojas = "2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19";
        break;
    // User Manuel Crispim
    case 36:
        $opt_lojas = '
            <option value=""></option>
            <option value="2">Vantagem (Carregado)</option>
            <option value="3">Vantagem 2 (Alverca)</option>
            <option value="4">Vantagem 3 (Arruda)</option>
            <option value="5">Vantagem 4 (Vila Franca)</option>
            <option value="6">Vantagem Central (Lisboa)</option>
            <option value="7">Vantagem 6 (Santarém)</option>
            <option value="8">Vantagem Seven (Odivelas)</option>
            <option value="9">Vantagem Avenida (Lisboa)</option>
            <option value="10">Vantagem Oeste (Torres Vedras)</option>
            <option value="11">Vantagem 8 (Póvoa)</option>
            <option value="12">Vantagem Invicta (Porto)</option>
            <option value="13">Vantagem Gaya (Gaia)</option>
            <option value="14">Vantagem Atlântico (Cascais)</option>
            <option value="15">Vantagem Maior (Rio Maior)</option>
            <option value="16">Vantagem Agraço (Sobral)</option>
            <option value="17">Vantagem Planície (Azambuja)</option>
            <option value="18">Vantagem Lezíria (Samora Correia)</option>
            <option value="19">Vantagem Real (Caldas)</option>
        ';
        $in_lojas = "2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19";
        break;
    // User José Pereira
    case 26:
        $opt_lojas = '
            <option value=""></option>
            <option value="2">Vantagem (Carregado)</option>
            <option value="3">Vantagem 2 (Alverca)</option>
            <option value="4">Vantagem 3 (Arruda)</option>
            <option value="5">Vantagem 4 (Vila Franca)</option>
            <option value="6">Vantagem Central (Lisboa)</option>
            <option value="7">Vantagem 6 (Santarém)</option>
            <option value="8">Vantagem Seven (Odivelas)</option>
            <option value="9">Vantagem Avenida (Lisboa)</option>
            <option value="10">Vantagem Oeste (Torres Vedras)</option>
            <option value="11">Vantagem 8 (Póvoa)</option>
            <option value="12">Vantagem Invicta (Porto)</option>
            <option value="13">Vantagem Gaya (Gaia)</option>
            <option value="14">Vantagem Atlântico (Cascais)</option>
            <option value="15">Vantagem Maior (Rio Maior)</option>
            <option value="16">Vantagem Agraço (Sobral)</option>
            <option value="17">Vantagem Planície (Azambuja)</option>
            <option value="18">Vantagem Lezíria (Samora Correia)</option>
            <option value="19">Vantagem Real (Caldas)</option>
        ';
        $in_lojas = "2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19";
        break;
    default:
        $opt_lojas = '<option value="">Não tens acesso a esta funcionalidade! (Informe o Marketing)</option>';
        $in_lojas = 0;
}

include_once("tpl/actions/conexao.php");
$sql_lojas = "
    SELECT controlo_produto.loja id_loja, info_loja.nome nome_loja, 
    (SELECT COUNT(1) FROM controlo_produto WHERE controlo_produto.previsao_fecho <= MONTH(NOW()) AND controlo_produto.estado = 1 AND controlo_produto.loja = CP.loja) em_curso_mes_atual,
    (SELECT COUNT(1) FROM controlo_produto WHERE controlo_produto.previsao_fecho = MONTH(NOW()) AND controlo_produto.estado = 2 AND controlo_produto.loja = CP.loja) fechada_mes_atual,
    (SELECT COUNT(1) FROM controlo_produto WHERE controlo_produto.previsao_fecho = (MONTH(NOW()) +1) AND controlo_produto.estado = 1 AND controlo_produto.loja = CP.loja) a_fechar_proximo_mes
    FROM controlo_produto 
    INNER JOIN controlo_produto CP ON CP.loja = controlo_produto.loja
    INNER JOIN info_loja ON info_loja.id = controlo_produto.loja
    WHERE controlo_produto.loja IN ($in_lojas)
    GROUP BY controlo_produto.loja
";
$result_sql_lojas = $conn->query($sql_lojas);

$idloja_url = intval($url[2]);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loja_selecionada = filter_input(INPUT_POST, 'loja', FILTER_SANITIZE_NUMBER_INT);
    //echo "a princípio existe o post e a loja selecionada foi a $loja_selecionada";

    $sql_nome_loja = "SELECT nome FROM info_loja WHERE id = $loja_selecionada";
    $result_nome_loja = mysqli_query($conn, $sql_nome_loja);

    if (mysqli_num_rows($result_nome_loja) > 0) {
        while ($row_nome_loja = mysqli_fetch_assoc($result_nome_loja)) {
            //$nome_loja_selecionada = str_replace("Remax ", "", $row_nome_loja["nome"]);
            $nome_loja_selecionada = $row_nome_loja["nome"];
        }
    } else {
        $nome_loja_selecionada = "Erro no nome da loja";
    }

    $row_two = "";

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
    WHERE CP.loja = $loja_selecionada AND
    (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho <= MONTH(NOW()) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) > 0
    GROUP BY CP.consultor, C.nome ORDER BY C.nome
    ";
    //echo $sql_this_month . "<br><br><br>";
    $result_this_month = $conn->query($sql_this_month);

    $sql_next_month = "
    SELECT CP.consultor id_consultor, C.nome nome_consultor, 
     (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho = (MONTH(NOW()) +1 ) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) em_curso_proximo_mes
    FROM controlo_produto CP
    INNER JOIN colaboradores C ON C.id = CP.consultor
    WHERE loja = $loja_selecionada AND 
    (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho = (MONTH(NOW()) +1 ) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) > 0
    GROUP BY CP.consultor, C.nome ORDER BY C.nome
    ";
    //echo $sql_next_month . "<br><br><br>";
    $result_next_month = $conn->query($sql_next_month);

    $row_one = "hidden";
    $row_two = "";
} else {
    if (empty($idloja_url)) {
        //echo 'não existe o post e não existe a loja na url';
        $row_one = "";
        $row_two = "hidden";
    } else {
        $loja_selecionada = $idloja_url;
        //echo "Não existe o post porém existe a loja na url que é $loja_selecionada";

        $sql_nome_loja = "SELECT nome FROM info_loja WHERE id = $loja_selecionada";
        $result_nome_loja = mysqli_query($conn, $sql_nome_loja);

        if (mysqli_num_rows($result_nome_loja) > 0) {
            while ($row_nome_loja = mysqli_fetch_assoc($result_nome_loja)) {
                //$nome_loja_selecionada = str_replace("Remax ", "", $row_nome_loja["nome"]);
                $nome_loja_selecionada = $row_nome_loja["nome"];
            }
        } else {
            $nome_loja_selecionada = "Erro no nome da loja";
        }

        $row_two = "";

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
        WHERE CP.loja = $loja_selecionada AND
        (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho <= MONTH(NOW()) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) > 0
        GROUP BY CP.consultor, C.nome ORDER BY C.nome
        ";
        //echo $sql_this_month . "<br><br><br>";
        $result_this_month = $conn->query($sql_this_month);

        $sql_next_month = "
        SELECT CP.consultor id_consultor, C.nome nome_consultor, 
         (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho = (MONTH(NOW()) +1 ) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) em_curso_proximo_mes
        FROM controlo_produto CP
        INNER JOIN colaboradores C ON C.id = CP.consultor
        WHERE loja = $loja_selecionada AND 
        (SELECT COUNT(1) FROM controlo_produto CP_C WHERE CP_C.previsao_fecho = (MONTH(NOW()) +1 ) AND CP_C.consultor = CP.consultor AND CP_C.estado = 1) > 0
        GROUP BY CP.consultor, C.nome ORDER BY C.nome
        ";
        //echo $sql_next_month . "<br><br><br>";
        $result_next_month = $conn->query($sql_next_month);

        $row_one = "hidden";
        $row_two = "";
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
                                    <span>Minhas Lojas</span>
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
                                                        <i class="fa fa-bank font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase">Minhas Lojas</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row margin-bottom-20 <?= $row_one; ?>">
                                                        <div class="col-md-12">
                                                            <form class="form-horizontal" method="post" action="<?php setHome(); ?>/controlo_produto/minhas-lojas">
                                                                <div class="form-group">
                                                                    <div class="col-md-12 margin-bottom-10">                                                                        
                                                                        <h4 class="bold">Selecione outra loja <span class="erro">*</span></h4>
                                                                        <select name="loja" class="form-control select2" onchange="this.form.submit()">
                                                                            <?= $opt_lojas; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="minhas_lojas" class="table table-bordered table-striped table-condensed table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center"> Loja </th>
                                                                            <th class="text-center"> Em Curso Este Mês </th>
                                                                            <th class="text-center"> Fechadas Este Mês </th>
                                                                            <th class="text-center"> Em Curso Próximo Mês </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if ($result_sql_lojas->num_rows > 0) {
                                                                            while ($row_sql_lojas = $result_sql_lojas->fetch_assoc()) {
                                                                                $id_loja = $row_sql_lojas["id_loja"];
                                                                                $nome_loja = $row_sql_lojas["nome_loja"];
                                                                                $em_curso_mes_atual = $row_sql_lojas["em_curso_mes_atual"];
                                                                                $fechada_mes_atual = $row_sql_lojas["fechada_mes_atual"];
                                                                                $em_curso_proximo_mes = $row_sql_lojas["a_fechar_proximo_mes"];
                                                                                echo "<tr class='text-center'>
                                                                                    <td><a href='" . BASE . "/controlo_produto/minhas-lojas/$id_loja'>$nome_loja</a></td>
                                                                                    <td>$em_curso_mes_atual</td>
                                                                                    <td>$fechada_mes_atual</td>
                                                                                    <td>$em_curso_proximo_mes</td>
                                                                                    </tr>
                                                                                ";
                                                                            }
                                                                        } else {
                                                                            echo "<tr class='text-center'><td colspan='4'>Neste mês não existem consultores com angariações em curso ou fechadas</td></tr> ";
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr class="text-center">
                                                                            <td> TOTAL </td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row margin-bottom-40 <?= $row_two; ?>">
                                                        <div class="col-md-12">
                                                            <a href="<?php setHome(); ?>/controlo_produto/minhas-lojas" class="btn red btn-block uppercase bold">Voltar para Minhas Lojas</a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h3 class="bold font-blue"><?= $nome_loja_selecionada; ?></h3>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <h3 class="bold font-blue"><?= $mes_one; ?> de <?= $ano_one; ?></h3>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="loja_ma" class="table table-bordered table-striped table-condensed table-hover">
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
                                                                                echo "<tr class='text-center'>
                                                                                    <td><a href='".BASE."/controlo_produto/detalhes_consultor/$id_consultor'>$nome_consultor</a></td>
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
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h3 class="bold font-green-meadow"><?= $nome_loja_selecionada; ?></h3>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <h3 class="bold font-green-meadow"><?= $mes_two; ?> de <?= $ano_two; ?></h3>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="loja_pm" class="table table-bordered table-striped table-condensed table-hover">
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
                                                                                echo "<tr class='text-center'>
                                                                                    <td><a href='".BASE."/controlo_produto/detalhes_consultor/$id_consultor'>$nome_consultor</a></td>
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
                                                                            <td></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 margin-top-20">
                                                            <a href="<?php setHome(); ?>/controlo_produto/minhas-lojas" class="btn red btn-block uppercase bold">Voltar para Minhas Lojas</a>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#minhas_lojas').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                curso_ma = api
                        .column(1)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                fechada_ma = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                curso_pm = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                $(api.column(1).footer()).html(curso_ma);
                $(api.column(2).footer()).html(fechada_ma);
                $(api.column(3).footer()).html(curso_pm);
            }
        });
        $('#loja_ma').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                curso_ma = api
                        .column(1)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                fechada_ma = api
                        .column(2)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                $(api.column(1).footer()).html(curso_ma);
                $(api.column(2).footer()).html(fechada_ma);
            }
        });
        $('#loja_pm').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json"
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                curso_pm = api
                        .column(1)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                // Update footer
                $(api.column(1).footer()).html(curso_pm);
            }
        });
    });
</script>
<script src="<?php setHome(); ?>/tpl/assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/table-datatables-responsive.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>