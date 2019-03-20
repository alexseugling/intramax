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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'consultor';
$url_cst_id = $url['2'];
$user_id_loja = $_SESSION['user_id_loja'];
include_once("tpl/actions/conexao.php");
$row_one = "";
$row_two = "hidden";

if (!empty($url_cst_id)) {
    $id_consultor = intval($url_cst_id);
    if (empty($id_consultor)) {
        $_SESSION['msg_filtro_consultor'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>Hack Detetado!</h4><p> A INTRAMAX possui um sistema anti-hack.</p></div>";
    } else {
        $sql_cst = "
            SELECT consultor.id id_consultor, consultor.nome nome_consultor, gestor.gestor id_gestor
            FROM colaboradores consultor
            INNER JOIN user_loja ON user_loja.user = consultor.id and user_loja.loja = $user_id_loja
            INNER JOIN cargo ON cargo.user = consultor.id and cargo.tipo_cargo in (1,2,18,19)
            INNER JOIN gestor ON gestor.user = consultor.id
            WHERE consultor.status = 1 AND consultor.id = $id_consultor
        ";
        $result_cst = $conn->query($sql_cst);
        if ($result_cst->num_rows > 0) {
            while ($row_cst = $result_cst->fetch_assoc()) {
                $id_gst = $row_cst['id_gestor'];
                $id_cst = $row_cst['id_consultor'];
                $nome_cst = $row_cst['nome_consultor'];
            }
            $row_one = "hidden";
            $row_two = "";
        } else {
            $_SESSION['msg_filtro_consultor'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>Nada Encontrado!</h4><p> Este consultor não existe no sistema.</p></div>";
        }
    }
} else {
    //Options Consultor
    $sql_cons = "
    SELECT consultor.id id_consultor, consultor.nome nome_consultor
    FROM colaboradores consultor
    INNER JOIN user_loja ON user_loja.user = consultor.id and user_loja.loja = $user_id_loja
    INNER JOIN cargo ON cargo.user = consultor.id and cargo.tipo_cargo in (1,2,18,19)
    WHERE consultor.status = 1
    ORDER BY consultor.nome ASC
    ";
    $result_cons = $conn->query($sql_cons);
    if ($result_cons->num_rows > 0) {
        while ($row_cons = $result_cons->fetch_assoc()) {
            $id_cons = $row_cons['id_consultor'];
            $nome_cons = $row_cons['nome_consultor'];
            $opt_consultores_loja .= "\n<option value='$id_cons'>$nome_cons</option>";
        }
    } else {
        $opt_consultores_loja = '<option>Erro no sistema, informe o Marketing</option>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_consultor = filter_input(INPUT_POST, 'id_consultor', FILTER_SANITIZE_NUMBER_INT);
        if (empty($id_consultor)) {
            $_SESSION['msg_filtro_consultor'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>Atenção!</h4><p> É necessário selecionar o consultor pois este campo não pode ser ser vazio.</p></div>";
        } else {
            $sql_cst = "
                SELECT consultor.id id_consultor, consultor.nome nome_consultor, gestor.gestor id_gestor
                FROM colaboradores consultor
                INNER JOIN user_loja ON user_loja.user = consultor.id and user_loja.loja = $user_id_loja
                INNER JOIN cargo ON cargo.user = consultor.id and cargo.tipo_cargo in (1,2,18,19) 
                INNER JOIN gestor ON gestor.user = consultor.id
                WHERE consultor.status = 1 AND consultor.id = $id_consultor
            ";
            $result_cst = $conn->query($sql_cst);
            if ($result_cst->num_rows > 0) {
                while ($row_cst = $result_cst->fetch_assoc()) {
                    $id_gst = $row_cst['id_gestor'];
                    $id_cst = $row_cst['id_consultor'];
                    $nome_cst = $row_cst['nome_consultor'];
                }
                $row_one = "hidden";
                $row_two = "";
            }
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
                                    <a href="<?php setHome(); ?>/controlo_produto">Controlo de Produto</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Filtro Consultor</span>
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
                                                        <i class="fa fa-filter font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase">Filtro Consultor</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row margin-bottom-40 <?= $row_one; ?>">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['msg_filtro_consultor'])) {
                                                                echo $_SESSION['msg_filtro_consultor'];
                                                                unset($_SESSION['msg_filtro_consultor']);
                                                            }
                                                            ?>
                                                            <h4>Selecione o Consultor <span class="erro">*</span></h4>
                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/controlo_produto/consultor" method="post">
                                                                <div class="form-group">
                                                                    <div class="col-md-6 margin-bottom-10">
                                                                        <select name="id_consultor" class="form-control select2">
                                                                            <option value=""></option>
                                                                            <?= $opt_consultores_loja; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-3 margin-bottom-10">
                                                                        <button type="submit" class="btn green-meadow btn-block bold uppercase">
                                                                            <i class="fa fa-filter"></i> Filtrar Consultor
                                                                        </button>
                                                                    </div>
                                                                    <div class="col-md-3 margin-bottom-10">
                                                                        <a href="<?php setHome(); ?>/controlo_produto" class="btn blue btn-block bold uppercase"><i class="fa fa-eye"></i> Ver Todos</a>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>                                                        
                                                    </div>
                                                    <div class="row margin-bottom-40 <?= $row_two; ?>">
                                                        <div class="col-md-4 margin-bottom-15">
                                                            <a href="<?php setHome(); ?>/controlo_produto/consultor" class="btn btn-lg red-intense btn-block bold uppercase"><i class="fa fa-reply"></i> Ver Outro Consultor</a>
                                                        </div>
                                                        <div class="col-md-4 margin-bottom-15">
                                                            <a href="<?php setHome(); ?>/controlo_produto" class="btn btn-lg blue btn-block bold uppercase"><i class="fa fa-eye"></i> Ver Todos</a>
                                                        </div>
                                                        <div class="col-md-4 margin-bottom-15">
                                                            <a data-toggle="modal" href="#inserir" class="btn btn-lg green-meadow btn-block bold uppercase"><i class="fa fa-plus"></i> Inserir Nova</a>
                                                        </div>
                                                        <div class="col-md-12 margin-bottom-15">
                                                            <?php
                                                            if (isset($_SESSION['msg_filtrado'])) {
                                                                echo $_SESSION['msg_filtrado'];
                                                                unset($_SESSION['msg_filtrado']);
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <!-- /.modal -->
                                                            <div class="modal fade bs-modal-lg" id="inserir" tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                            <h4 class="modal-title bold font-green-meadow uppercase">Novo Negócio - <?= $nome_cst; ?></h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/tpl/actions/inserir_controlo_produto.php" method="post">
                                                                                <div class="form-group">
                                                                                    <div class="col-md-12">
                                                                                        <label>Descrição do Produto <span class="required">*</span></label>
                                                                                        <input class="form-control" name="descricao" type="text" placeholder="Digite uma Descrição">
                                                                                        <input type="hidden" name="id_cst" value="<?= $id_cst; ?>">
                                                                                        <input type="hidden" name="id_gst" value="<?= $id_gst; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <div class="col-md-4 margin-bottom-10">
                                                                                        <label>Reunião de Pré-Angariação <span class="required">*</span></label>
                                                                                        <select name="pre_angariacao" class="form-control select2">
                                                                                            <option value=""></option>
                                                                                            <option value="1">Sim</option>
                                                                                            <option value="0">Não</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 margin-bottom-10">
                                                                                        <label>Apresentação de Estudo de Mercado <span class="required">*</span></label>
                                                                                        <select name="estudo_mercado" class="form-control select2">
                                                                                            <option value=""></option>
                                                                                            <option value="1">Sim</option>
                                                                                            <option value="0">Não</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 margin-bottom-10">
                                                                                        <label>Mês de Previsão do Fecho <span class="required">*</span></label>
                                                                                        <select name="previsao_fecho" class="form-control select2">
                                                                                            <option value=""></option>
                                                                                            <option value="1">Janeiro</option>
                                                                                            <option value="2">Fevereiro</option>
                                                                                            <option value="3">Março</option>
                                                                                            <option value="4">Abril</option>
                                                                                            <option value="5">Maio</option>
                                                                                            <option value="6">Junho</option>
                                                                                            <option value="7">Julho</option>
                                                                                            <option value="8">Agosto</option>
                                                                                            <option value="9">Setembro</option>
                                                                                            <option value="10">Outubro</option>
                                                                                            <option value="11">Novembro</option>
                                                                                            <option value="12">Dezembro</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group margin-top-20">
                                                                                    <div class="col-md-6 margin-bottom-5">
                                                                                        <button type="button" class="btn red-intense btn-block bold uppercase" data-dismiss="modal">Cancelar</button>
                                                                                    </div>
                                                                                    <div class="col-md-6 margin-bottom-5">
                                                                                        <input type="submit" class="btn green-meadow btn-block bold uppercase" value="Adicionar">
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                </div>
                                                                <!-- /.modal-dialog -->
                                                            </div>
                                                            <!-- /.modal -->
                                                        </div>
                                                        <div class="col-md-12">
                                                            <h4 class="bold uppercase font-blue">Angariações Em Curso - <?= $nome_cst; ?></h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center"> Descrição do Negócio </th>
                                                                            <th class="text-center"> Pré-Angariação </th>
                                                                            <th class="text-center"> Estudo de Mercado </th>
                                                                            <th class="text-center"> Data de Inserção </th>
                                                                            <th class="text-center"> Prev. de Fecho </th>
                                                                            <th class="text-center"> Estado </th>
                                                                            <th class="text-center"> Editar </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $sel_curso = "
                                                                            SELECT controlo_produto.id id_curso, controlo_produto.data_insercao, controlo_produto.descricao_negocio, 
                                                                            controlo_produto.pre_angariacao, controlo_produto.estudo_mercado, controlo_produto.previsao_fecho, 
                                                                            controlo_produto.data_fecho, controlo_produto.estado
                                                                            FROM controlo_produto
                                                                            WHERE controlo_produto.consultor = $id_cst AND controlo_produto.estado = 1
                                                                            ORDER BY controlo_produto.data_insercao DESC
                                                                        ";
                                                                        $result_curso = $conn->query($sel_curso);
                                                                        if ($result_curso->num_rows > 0) {
                                                                            while ($row_curso = $result_curso->fetch_assoc()) {
                                                                                $id_curso = $row_curso["id_curso"];
                                                                                $data_insercao = date_format(date_create($row_curso["data_insercao"]), "d/m/Y");
                                                                                $descricao_negocio = $row_curso["descricao_negocio"];
                                                                                $pre_ang = $row_curso["pre_angariacao"];
                                                                                if ($pre_ang > 0) {
                                                                                    $pre_angariacao = "Sim";
                                                                                } else {
                                                                                    $pre_angariacao = "Não";
                                                                                }
                                                                                $est_mercado = $row_curso["estudo_mercado"];
                                                                                if ($est_mercado > 0) {
                                                                                    $estudo_mercado = "Sim";
                                                                                } else {
                                                                                    $estudo_mercado = "Não";
                                                                                }
                                                                                $prev_fecho = $row_curso["previsao_fecho"];
                                                                                switch ($prev_fecho) {
                                                                                    case 1:
                                                                                        $previsao_fecho = "Janeiro";
                                                                                        break;
                                                                                    case 2:
                                                                                        $previsao_fecho = "Fevereiro";
                                                                                        break;
                                                                                    case 3:
                                                                                        $previsao_fecho = "Março";
                                                                                        break;
                                                                                    case 4:
                                                                                        $previsao_fecho = "Abril";
                                                                                        break;
                                                                                    case 5:
                                                                                        $previsao_fecho = "Maio";
                                                                                        break;
                                                                                    case 6:
                                                                                        $previsao_fecho = "Junho";
                                                                                        break;
                                                                                    case 7:
                                                                                        $previsao_fecho = "Julho";
                                                                                        break;
                                                                                    case 8:
                                                                                        $previsao_fecho = "Agosto";
                                                                                        break;
                                                                                    case 9:
                                                                                        $previsao_fecho = "Setembro";
                                                                                        break;
                                                                                    case 10:
                                                                                        $previsao_fecho = "Outubro";
                                                                                        break;
                                                                                    case 11:
                                                                                        $previsao_fecho = "Novembro";
                                                                                        break;
                                                                                    case 12:
                                                                                        $previsao_fecho = "Dezembro";
                                                                                        break;
                                                                                    default:
                                                                                        $previsao_fecho = "Indefinido";
                                                                                }
                                                                                $status = $row_curso["estado"];
                                                                                if ($status == 1) {
                                                                                    $estado = "Em Curso";
                                                                                } elseif ($status == 2) {
                                                                                    $estado = "Fechada";
                                                                                } else {
                                                                                    $estado = "Morta";
                                                                                }

                                                                                echo "<tr class='text-center'>
                                                                                    <td> $descricao_negocio </td>
                                                                                    <td> $pre_angariacao </td>
                                                                                    <td> $estudo_mercado </td>
                                                                                    <td> $data_insercao </td>
                                                                                    <td> $previsao_fecho </td>
                                                                                    <td> $estado </td>
                                                                                    <td> <a href='" . BASE . "/controlo_produto/editar-produto/$id_curso/$id_cst/$status'><i class='fa fa-pencil'></i></a> </td>
                                                                                </tr>";
                                                                            }
                                                                        } else {
                                                                            echo "<tr class='text-center'><td colspan='7'>Não existe nenhum produto em curso</td></tr>";
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <h4 class="bold uppercase font-green-meadow">Angariações Fechadas - <?= $nome_cst; ?></h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center"> Descrição do Negócio </th>
                                                                            <th class="text-center"> Pré-Angariação </th>
                                                                            <th class="text-center"> Estudo de Mercado </th>
                                                                            <th class="text-center"> Data da Inserção </th>
                                                                            <th class="text-center"> Prev. de Fecho </th>
                                                                            <th class="text-center"> Data do Fecho </th>
                                                                            <th class="text-center"> Estado </th>
                                                                            <th class="text-center"> Editar </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $sel_fechada = "
                                                                            SELECT controlo_produto.id id_fechada, controlo_produto.data_insercao, controlo_produto.data_fecho, 
                                                                            controlo_produto.descricao_negocio, controlo_produto.pre_angariacao, controlo_produto.estudo_mercado, 
                                                                            controlo_produto.previsao_fecho, controlo_produto.estado
                                                                            FROM controlo_produto
                                                                            WHERE controlo_produto.consultor = $id_cst AND controlo_produto.estado = 2
                                                                            ORDER BY controlo_produto.data_insercao DESC
                                                                        ";
                                                                        $result_fechada = $conn->query($sel_fechada);
                                                                        if ($result_fechada->num_rows > 0) {
                                                                            while ($row_fechada = $result_fechada->fetch_assoc()) {
                                                                                $id_fechada = $row_fechada["id_fechada"];
                                                                                $data_insercao = date_format(date_create($row_fechada["data_insercao"]), "d/m/Y");
                                                                                $data_fecho = date_format(date_create($row_fechada["data_fecho"]), "d/m/Y");
                                                                                $descricao_negocio = $row_fechada["descricao_negocio"];
                                                                                $pre_ang = $row_fechada["pre_angariacao"];
                                                                                if ($pre_ang > 0) {
                                                                                    $pre_angariacao = "Sim";
                                                                                } else {
                                                                                    $pre_angariacao = "Não";
                                                                                }
                                                                                $est_mercado = $row_fechada["estudo_mercado"];
                                                                                if ($est_mercado > 0) {
                                                                                    $estudo_mercado = "Sim";
                                                                                } else {
                                                                                    $estudo_mercado = "Não";
                                                                                }
                                                                                $prev_fecho = $row_fechada["previsao_fecho"];
                                                                                switch ($prev_fecho) {
                                                                                    case 1:
                                                                                        $previsao_fecho = "Janeiro";
                                                                                        break;
                                                                                    case 2:
                                                                                        $previsao_fecho = "Fevereiro";
                                                                                        break;
                                                                                    case 3:
                                                                                        $previsao_fecho = "Março";
                                                                                        break;
                                                                                    case 4:
                                                                                        $previsao_fecho = "Abril";
                                                                                        break;
                                                                                    case 5:
                                                                                        $previsao_fecho = "Maio";
                                                                                        break;
                                                                                    case 6:
                                                                                        $previsao_fecho = "Junho";
                                                                                        break;
                                                                                    case 7:
                                                                                        $previsao_fecho = "Julho";
                                                                                        break;
                                                                                    case 8:
                                                                                        $previsao_fecho = "Agosto";
                                                                                        break;
                                                                                    case 9:
                                                                                        $previsao_fecho = "Setembro";
                                                                                        break;
                                                                                    case 10:
                                                                                        $previsao_fecho = "Outubro";
                                                                                        break;
                                                                                    case 11:
                                                                                        $previsao_fecho = "Novembro";
                                                                                        break;
                                                                                    case 12:
                                                                                        $previsao_fecho = "Dezembro";
                                                                                        break;
                                                                                    default:
                                                                                        $previsao_fecho = "Indefinido";
                                                                                }
                                                                                $status = $row_fechada["estado"];
                                                                                if ($status == 1) {
                                                                                    $estado = "Em Curso";
                                                                                } elseif ($status == 2) {
                                                                                    $estado = "Fechada";
                                                                                } else {
                                                                                    $estado = "Morta";
                                                                                }

                                                                                echo "<tr class='text-center'>
                                                                                    <td> $descricao_negocio </td>
                                                                                    <td> $pre_angariacao </td>
                                                                                    <td> $estudo_mercado </td>
                                                                                    <td> $data_insercao </td>
                                                                                    <td> $previsao_fecho </td>
                                                                                    <td> $data_fecho </td>
                                                                                    <td> $estado </td>
                                                                                    <td> <a href='" . BASE . "/controlo_produto/editar-produto/$id_fechada/$id_cst/$status'><i class='fa fa-pencil'></i></a> </td>
                                                                                </tr>";
                                                                            }
                                                                        } else {
                                                                            echo "<tr class='text-center'><td colspan='8'>Não existe nenhum produto fechado</td></tr>";
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <h4 class="bold uppercase font-purple-seance">Angariações Mortas - <?= $nome_cst; ?></h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-condensed table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center"> Descrição do Negócio </th>
                                                                            <th class="text-center"> Pré-Angariação </th>
                                                                            <th class="text-center"> Estudo de Mercado </th>
                                                                            <th class="text-center"> Data da Inserção </th>
                                                                            <th class="text-center"> Prev. de Fecho </th>
                                                                            <th class="text-center"> Data da Morte </th>
                                                                            <th class="text-center"> Estado </th>
                                                                            <th class="text-center"> Editar </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $sel_morta = "
                                                                            SELECT controlo_produto.id id_morta, controlo_produto.data_insercao, controlo_produto.data_morte, 
                                                                            controlo_produto.descricao_negocio, controlo_produto.pre_angariacao, controlo_produto.estudo_mercado, 
                                                                            controlo_produto.previsao_fecho, controlo_produto.estado
                                                                            FROM controlo_produto
                                                                            WHERE controlo_produto.consultor = $id_cst AND controlo_produto.estado = 3
                                                                            ORDER BY controlo_produto.data_insercao DESC
                                                                        ";
                                                                        $result_morta = $conn->query($sel_morta);
                                                                        if ($result_morta->num_rows > 0) {
                                                                            while ($row_morta = $result_morta->fetch_assoc()) {
                                                                                $id_morta = $row_morta["id_morta"];
                                                                                $data_insercao = date_format(date_create($row_morta["data_insercao"]), "d/m/Y");
                                                                                $data_morte = date_format(date_create($row_morta["data_morte"]), "d/m/Y");
                                                                                $descricao_negocio = $row_morta["descricao_negocio"];
                                                                                $pre_ang = $row_morta["pre_angariacao"];
                                                                                if ($pre_ang > 0) {
                                                                                    $pre_angariacao = "Sim";
                                                                                } else {
                                                                                    $pre_angariacao = "Não";
                                                                                }
                                                                                $est_mercado = $row_morta["estudo_mercado"];
                                                                                if ($est_mercado > 0) {
                                                                                    $estudo_mercado = "Sim";
                                                                                } else {
                                                                                    $estudo_mercado = "Não";
                                                                                }
                                                                                $prev_fecho = $row_morta["previsao_fecho"];
                                                                                switch ($prev_fecho) {
                                                                                    case 1:
                                                                                        $previsao_fecho = "Janeiro";
                                                                                        break;
                                                                                    case 2:
                                                                                        $previsao_fecho = "Fevereiro";
                                                                                        break;
                                                                                    case 3:
                                                                                        $previsao_fecho = "Março";
                                                                                        break;
                                                                                    case 4:
                                                                                        $previsao_fecho = "Abril";
                                                                                        break;
                                                                                    case 5:
                                                                                        $previsao_fecho = "Maio";
                                                                                        break;
                                                                                    case 6:
                                                                                        $previsao_fecho = "Junho";
                                                                                        break;
                                                                                    case 7:
                                                                                        $previsao_fecho = "Julho";
                                                                                        break;
                                                                                    case 8:
                                                                                        $previsao_fecho = "Agosto";
                                                                                        break;
                                                                                    case 9:
                                                                                        $previsao_fecho = "Setembro";
                                                                                        break;
                                                                                    case 10:
                                                                                        $previsao_fecho = "Outubro";
                                                                                        break;
                                                                                    case 11:
                                                                                        $previsao_fecho = "Novembro";
                                                                                        break;
                                                                                    case 12:
                                                                                        $previsao_fecho = "Dezembro";
                                                                                        break;
                                                                                    default:
                                                                                        $previsao_fecho = "Indefinido";
                                                                                }
                                                                                $status = $row_morta["estado"];
                                                                                if ($status == 1) {
                                                                                    $estado = "Em Curso";
                                                                                } elseif ($status == 2) {
                                                                                    $estado = "Fechada";
                                                                                } else {
                                                                                    $estado = "Morta";
                                                                                }

                                                                                echo "<tr class='text-center'>
                                                                                    <td> $descricao_negocio </td>
                                                                                    <td> $pre_angariacao </td>
                                                                                    <td> $estudo_mercado </td>
                                                                                    <td> $data_insercao </td>
                                                                                    <td> $previsao_fecho </td>
                                                                                    <td> $data_morte </td>
                                                                                    <td> $estado </td>
                                                                                    <td> <a href='" . BASE . "/controlo_produto/editar-produto/$id_morta/$id_cst/$status'><i class='fa fa-pencil'></i></a> </td>
                                                                                </tr>";
                                                                            }
                                                                        } else {
                                                                            echo "<tr class='text-center'><td colspan='8'>Não existe nenhum produto morto</td></tr>";
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
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>