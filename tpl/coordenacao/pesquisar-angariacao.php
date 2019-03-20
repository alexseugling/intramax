<?php
if (!isset($_SESSION['login'], $_SESSION['menu_coordenacao']) && !isset($_SESSION['menu_admin']) && !isset($_SESSION['menu_qualidade'])) {
    ?>
    <script type="text/javascript">location.href = "<?php setHome(); ?>/tpl/actions/logout.php";</script>
    <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/tpl/actions/logout.php" /></noscript>
    <?php
    exit;
}
include 'tpl/inc/head_body.inc.php';
$page = 'pesquisar-angariacao';
$action = $url['2'];
include_once("tpl/actions/conexao.php");
$user_id_loja = $_SESSION['user_id_loja'];
$user_id = $_SESSION['user_id'];
$user_nome = $_SESSION['user_nome'];
$user_nome_loja = $_SESSION['user_nome_loja'];
$row_inicio = $row_id_imovel = $row_consultor = $row_erro = $row_form = "hidden";
if ($action == "") {
    $row_inicio = "";
} elseif ($action == "id-imovel") {
    $row_id_imovel = "";
} elseif ($action == "consultor") {
    $busca_consultor = "";
    $resultado_busca_consultor = "hidden";
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
    //Angariações ativas do consultor pesquisado
    $consultor_pesquisado = $_SESSION['consultor_pesquisado'];
    if ($consultor_pesquisado != "") {
        $busca_consultor = "hidden";
        $resultado_busca_consultor = "";
    }
    $row_consultor = "";
} else {
    $row_erro = "";
}
if ($_SESSION['show_form'] != "") {
    if ($action == "id-imovel") {
        $id_imovel_procurado = $url['3'];
        //TODAS AS INFORMAÇÕES DO IMÓVEL PELO ID DO IMÓVEL
        $sel_id_imovel = "
            SELECT angariacoes.id angariacao_id, id_angariacao.id_imovel id_atual_imovel, livro_registo.id_inicial_imovel id_inicial_imovel, 
            angariacoes.data_insercao_ilist, id_angariacao.consultor id_consultor, cst.nome nome_consultor, 
            id_angariacao.gestor id_gestor, gst.nome nome_gestor, id_angariacao.loja, 
            angariacoes.tipo_angariacao id_tipo_angariacao, tipo_angariacao.nome tipo_angariacao, 
            angariacoes.tipo_negocio id_tipo_negocio, tipo_negocio.nome tipo_negocio, 
            angariacoes.arruamento_imovel, angariacoes.num_porta_imovel, angariacoes.complemento_imovel, 
            angariacoes.cod_postal_imovel, angariacoes.freguesia_imovel id_freguesia_imovel, 
            freg_imv.nome freguesia_imovel, freg_imv.concelho id_concelho_imovel, conc_imv.nome concelho_imovel, 
            freg_imv.distrito id_distrito_imovel, dist_imv.nome distrito_imovel, angariacoes.tipo_imovel id_tipo_imovel, 
            tipo_imovel.nome tipo_imovel, angariacoes.valor_inicial_imovel, 
            (SELECT novo_valor_imovel FROM `baixa_preco` WHERE angariacao_id = angariacoes.id ORDER BY id DESC LIMIT 1) valor_atual_imovel, 
            livro_registo.estado_imovel id_estado_imovel, estado_imovel.nome estado_imovel, 
            livro_registo.num_contrato, livro_registo.data_cmi, livro_registo.regime id_regime, 
            tipo_regime.nome regime, livro_registo.duracao_contrato, livro_registo.conservatoria, 
            livro_registo.ficha_crp, livro_registo.matriz_cpu, livro_registo.fracao, 
            livro_registo.tipo_comissao id_tipo_comissao, tipo_comissao.nome tipo_comissao, 
            livro_registo.valor_comissao, livro_registo.data_negocio, livro_registo.data_recisao, 
            livro_registo.valor_negocio, livro_registo.comissao_recebida, 
            livro_registo.nome_proprietario, livro_registo.nif_proprietario, livro_registo.email_proprietario, 
            livro_registo.telefone_proprietario, livro_registo.arruamento_proprietario, 
            livro_registo.num_porta_proprietario, livro_registo.complemento_proprietario, 
            livro_registo.cod_postal_proprietario, livro_registo.freguesia_proprietario id_freguesia_proprietario, 
            freg_prop.nome freguesia_proprietario, freg_prop.concelho id_concelho_proprietario, 
            conc_prop.nome concelho_proprietario, freg_prop.distrito id_distrito_proprietario, 
            dist_prop.nome distrito_proprietario, angariacoes.espelho 
            FROM angariacoes 
            INNER JOIN id_angariacao ON id_angariacao.angariacao_id = angariacoes.id 
            INNER JOIN tipo_angariacao ON tipo_angariacao.id = angariacoes.tipo_angariacao 
            INNER JOIN colaboradores cst ON cst.id = id_angariacao.consultor 
            INNER JOIN colaboradores gst ON gst.id = id_angariacao.gestor 
            INNER JOIN tipo_negocio ON tipo_negocio.id = angariacoes.tipo_negocio 
            INNER JOIN livro_registo ON livro_registo.angariacao_id = angariacoes.id 
            INNER JOIN lista_freguesia freg_imv ON freg_imv.id = angariacoes.freguesia_imovel 
            INNER JOIN lista_concelho conc_imv ON conc_imv.id = freg_imv.concelho
            INNER JOIN lista_distrito dist_imv ON dist_imv.id = freg_imv.distrito
            INNER JOIN tipo_imovel ON tipo_imovel.id = angariacoes.tipo_imovel 
            INNER JOIN estado_imovel ON estado_imovel.id = livro_registo.estado_imovel 
            INNER JOIN tipo_regime ON tipo_regime.id = livro_registo.regime 
            INNER JOIN tipo_comissao ON tipo_comissao.id = livro_registo.tipo_comissao 
            INNER JOIN lista_freguesia freg_prop ON freg_prop.id = livro_registo.freguesia_proprietario 
            INNER JOIN lista_concelho conc_prop ON conc_prop.id = freg_prop.concelho
            INNER JOIN lista_distrito dist_prop ON dist_prop.id = freg_prop.distrito
            WHERE id_angariacao.id_imovel = '$id_imovel_procurado' OR livro_registo.id_inicial_imovel = '$id_imovel_procurado'
        ";
        $result_id_imovel = mysqli_query($conn, $sel_id_imovel);
        if (mysqli_num_rows($result_id_imovel) > 0) {
            while ($rw_id_imovel = mysqli_fetch_assoc($result_id_imovel)) {
                $angariacao_id = $rw_id_imovel["angariacao_id"];
                $id_inicial_imovel = $rw_id_imovel["id_inicial_imovel"];
                $id_atual_imovel = $rw_id_imovel["id_atual_imovel"];
                $data_insercao_ilist = date_format(date_create($rw_id_imovel["data_insercao_ilist"]), "d/m/Y");
                $id_consultor = $rw_id_imovel["id_consultor"];
                $nome_consultor = $rw_id_imovel["nome_consultor"];
                $id_gestor = $rw_id_imovel["id_gestor"];
                $nome_gestor = $rw_id_imovel["nome_gestor"];
                $loja = $rw_id_imovel["loja"];
                $id_tipo_angariacao = $rw_id_imovel["id_tipo_angariacao"];
                $tipo_angariacao = $rw_id_imovel["tipo_angariacao"];
                $id_tipo_negocio = $rw_id_imovel["id_tipo_negocio"];
                $tipo_negocio = $rw_id_imovel["tipo_negocio"];
                $arruamento_imovel = $rw_id_imovel["arruamento_imovel"];
                $num_porta_imovel = $rw_id_imovel["num_porta_imovel"];
                $complemento_imovel = $rw_id_imovel["complemento_imovel"];
                $cod_postal_imovel = $rw_id_imovel["cod_postal_imovel"];
                $id_freguesia_imovel = $rw_id_imovel["id_freguesia_imovel"];
                $freguesia_imovel = $rw_id_imovel["freguesia_imovel"];
                $id_concelho_imovel = $rw_id_imovel["id_concelho_imovel"];
                $concelho_imovel = $rw_id_imovel["concelho_imovel"];
                $id_distrito_imovel = $rw_id_imovel["id_distrito_imovel"];
                $distrito_imovel = $rw_id_imovel["distrito_imovel"];
                $id_tipo_imovel = $rw_id_imovel["id_tipo_imovel"];
                $tipo_imovel = $rw_id_imovel["tipo_imovel"];
                $valor_inicial_imovel_clear = str_replace(".00", "", $rw_id_imovel["valor_inicial_imovel"]);
                $valor_inicial_imovel = number_format($rw_id_imovel["valor_inicial_imovel"], 2, ',', '.');
                $valor_atual_imovel_clear = str_replace(".00", "", $rw_id_imovel["valor_atual_imovel"]);
                $valor_atual_imovel = number_format($rw_id_imovel["valor_atual_imovel"], 2, ',', '.');
                $id_estado_imovel = $rw_id_imovel["id_estado_imovel"];
                $estado_imovel = $rw_id_imovel["estado_imovel"];
                $num_contrato = $rw_id_imovel["num_contrato"];
                $data_cmi = date_format(date_create($rw_id_imovel["data_cmi"]), "d/m/Y");
                $id_regime = $rw_id_imovel["id_regime"];
                $regime = $rw_id_imovel["regime"];
                $duracao_contrato = $rw_id_imovel["duracao_contrato"];
                $conservatoria = $rw_id_imovel["conservatoria"];
                $ficha_crp = $rw_id_imovel["ficha_crp"];
                $matriz_cpu = $rw_id_imovel["matriz_cpu"];
                $fracao = $rw_id_imovel["fracao"];
                $id_tipo_comissao = $rw_id_imovel["id_tipo_comissao"];
                $tipo_comissao = $rw_id_imovel["tipo_comissao"];
                $valor_comissao_clear = str_replace(".00", "", $rw_id_imovel["valor_comissao"]);
                $valor_comissao = number_format($rw_id_imovel["valor_comissao"], 2, ',', '.');
                if ($id_tipo_comissao == 1) {
                    $valor_comissao = str_replace(".00", "", $rw_id_imovel["valor_comissao"]) . "%";
                    $fmt = "numeros";
                } elseif ($id_tipo_comissao == 2) {
                    $valor_comissao = number_format($rw_id_imovel["valor_comissao"], 2, ',', '.') . "€";
                    $fmt = "comissao";
                } else {
                    $valor_comissao = number_format($rw_id_imovel["valor_comissao"], 2, ',', '.') . "%";
                    $fmt = "comissao";
                }
                $data_negocio = $rw_id_imovel["data_negocio"];
                if ($data_negocio != NULL) {
                    $data_negocio = date_format(date_create($rw_id_imovel["data_negocio"]), "d/m/Y");
                }
                $data_recisao = $rw_id_imovel["data_recisao"];
                if ($data_recisao != NULL) {
                    $data_recisao = date_format(date_create($rw_id_imovel["data_recisao"]), "d/m/Y");
                }
                $valor_negocio_clear = str_replace(".00", "", $rw_id_imovel["valor_negocio"]);
                $valor_negocio = number_format($rw_id_imovel["valor_negocio"], 2, ',', '.');
                $comissao_recebida_clear = str_replace(".00", "", $rw_id_imovel["comissao_recebida"]);
                $comissao_recebida = number_format($rw_id_imovel["comissao_recebida"], 2, ',', '.');
                $nome_proprietario = $rw_id_imovel["nome_proprietario"];
                $nif_proprietario = $rw_id_imovel["nif_proprietario"];
                $email_proprietario = $rw_id_imovel["email_proprietario"];
                $telefone_proprietario = $rw_id_imovel["telefone_proprietario"];
                $arruamento_proprietario = $rw_id_imovel["arruamento_proprietario"];
                $num_porta_proprietario = $rw_id_imovel["num_porta_proprietario"];
                $complemento_proprietario = $rw_id_imovel["complemento_proprietario"];
                $cod_postal_proprietario = $rw_id_imovel["cod_postal_proprietario"];
                $id_freguesia_proprietario = $rw_id_imovel["id_freguesia_proprietario"];
                $freguesia_proprietario = $rw_id_imovel["freguesia_proprietario"];
                $id_concelho_proprietario = $rw_id_imovel["id_concelho_proprietario"];
                $concelho_proprietario = $rw_id_imovel["concelho_proprietario"];
                $id_distrito_proprietario = $rw_id_imovel["id_distrito_proprietario"];
                $distrito_proprietario = $rw_id_imovel["distrito_proprietario"];
                $vai_espelho = $rw_id_imovel["espelho"];
                if ($vai_espelho == 1) {
                    $espelho = "Sim";
                } else {
                    $espelho = "Não";
                }
                //INICIA O HISTÓRICO ANTERIOR
                $hist_before = $rw_id_imovel;
            }
            if ($user_id_loja == $loja) {
                $row_inicio = $row_id_imovel = $row_consultor = $row_erro = "hidden";
                $row_form = "";
            }
        }
        //HISTORICO ANTERIOR INICIO
        foreach ($hist_before as $key => $value) {
            $antes .= "$key: $value<br>";
        }
        $_SESSION['historico_antes'] = "Informações do dia " . date('d/m/Y - H:i:s') . " ANTES de ser alterado por $user_nome - Colaborador(a) com o id $user_id na loja $user_nome_loja<br>" . $antes;
        //HISTORICO ANTERIOR FIM
        // SELECT OPTIONS CONSULTORES
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

        // SELECT OPTIONS LISTA DE FREGUESIAS
        $sql_freg = "
            SELECT freglist.id freglist_id, freglist.nome freglist_nome FROM lista_freguesia freglist
        ";
        $result_freg = $conn->query($sql_freg);
        if ($result_freg->num_rows > 0) {
            while ($row_freg = $result_freg->fetch_assoc()) {
                $freglist_id = $row_freg['freglist_id'];
                $freglist_nome = $row_freg['freglist_nome'];
                $opt_freguesias .= "\n<option value='$freglist_id'>$freglist_nome</option>";
            }
        } else {
            $opt_freguesias = '<option>Erro no sistema, informe o Marketing</option>';
        }

        // SELECT OPTIONS TIPO DE ANGARIAÇÃO "HABITAÇÃO OU NÃO HABITAÇÃO"
        $sql_tipo_ang = "
            SELECT tipo_angariacao.id id_tipo_ang, tipo_angariacao.nome tipo_ang FROM tipo_angariacao 
        ";
        $result_tipo_ang = $conn->query($sql_tipo_ang);
        if ($result_tipo_ang->num_rows > 0) {
            while ($row_tipo_ang = $result_tipo_ang->fetch_assoc()) {
                $id_tipo_ang = $row_tipo_ang['id_tipo_ang'];
                $tipo_ang = $row_tipo_ang['tipo_ang'];
                $opt_tipo_angariacoes .= "\n<option value='$id_tipo_ang'>$tipo_ang</option>";
            }
        } else {
            $opt_tipo_angariacoes = '<option>Erro no sistema, informe o Marketing</option>';
        }

        // SELECT OPTIONS TIPO DE NEGÓCIO "VENDA, ARRENDAMENTO OU TRESPASSE"
        $sql_tipo_ngc = "
            SELECT tipo_negocio.id id_tipo_ngc, tipo_negocio.nome tipo_ngc FROM tipo_negocio 
        ";
        $result_tipo_ngc = $conn->query($sql_tipo_ngc);
        if ($result_tipo_ngc->num_rows > 0) {
            while ($row_tipo_ngc = $result_tipo_ngc->fetch_assoc()) {
                $id_tipo_ngc = $row_tipo_ngc['id_tipo_ngc'];
                $tipo_ngc = $row_tipo_ngc['tipo_ngc'];
                $opt_tipo_negocio .= "\n<option value='$id_tipo_ngc'>$tipo_ngc</option>";
            }
        } else {
            $opt_tipo_negocio = '<option>Erro no sistema, informe o Marketing</option>';
        }

        // SELECT OPTIONS TIPO DE IMÓVEIS (DEPENDE SE FOR HABITAÇÃO OU NÃO HABITAÇÃO)
        $sql_tipo_imv = "
            SELECT tipo_imovel.id id_tipo_imv, tipo_imovel.nome tipo_imv FROM tipo_imovel 
            WHERE tipo_imovel.tipo_angariacao = $id_tipo_angariacao ORDER BY tipo_imovel.nome ASC
        ";
        $result_tipo_imv = $conn->query($sql_tipo_imv);
        if ($result_tipo_imv->num_rows > 0) {
            while ($row_tipo_imv = $result_tipo_imv->fetch_assoc()) {
                $id_tipo_imv = $row_tipo_imv['id_tipo_imv'];
                $tipo_imv = $row_tipo_imv['tipo_imv'];
                $opt_tipo_imoveis .= "\n<option value='$id_tipo_imv'>$tipo_imv</option>";
            }
        } else {
            $opt_tipo_imoveis = '<option>Erro no sistema, informe o Marketing</option>';
        }

        // SELECT OPTIONS ESTADO DO IMÓVEL (ACTIVO, RESERVADO, VENDIDO, ARRENDADO E CANCELADO)
        $sql_estado_imv = "
            SELECT estado_imovel.id id_estado_imv, estado_imovel.nome estado_imv FROM estado_imovel
        ";
        $result_estado_imv = $conn->query($sql_estado_imv);
        if ($result_estado_imv->num_rows > 0) {
            while ($row_estado_imv = $result_estado_imv->fetch_assoc()) {
                $id_estado_imv = $row_estado_imv['id_estado_imv'];
                $estado_imv = $row_estado_imv['estado_imv'];
                $opt_estado_imovel .= "\n<option value='$id_estado_imv'>$estado_imv</option>";
            }
        } else {
            $opt_estado_imovel = '<option>Erro no sistema, informe o Marketing</option>';
        }

        // SELECT OPTIONS TIPO DE REGIME (EXCLUSIVO, EXCLUSIVO MÚLTIPLOS E EXCLUSIVO DE REDE)
        $sql_tipo_reg = "
            SELECT tipo_regime.id id_tipo_reg, tipo_regime.nome tipo_reg FROM tipo_regime
        ";
        $result_tipo_reg = $conn->query($sql_tipo_reg);
        if ($result_tipo_reg->num_rows > 0) {
            while ($row_tipo_reg = $result_tipo_reg->fetch_assoc()) {
                $id_tipo_reg = $row_tipo_reg['id_tipo_reg'];
                $tipo_reg = $row_tipo_reg['tipo_reg'];
                $opt_tipo_regime .= "\n<option value='$id_tipo_reg'>$tipo_reg</option>";
            }
        } else {
            $opt_tipo_regime = '<option>Erro no sistema, informe o Marketing</option>';
        }

        // SELECT OPTIONS TIPO DE COMISSÃO (PERCENTUAL INTEIRO, VALOR FIXO E PERCENTUAL FRACIONADO 162,60%)
        $sql_tipo_com = "
            SELECT tipo_comissao.id id_tipo_com, tipo_comissao.nome tipo_com FROM tipo_comissao
        ";
        $result_tipo_com = $conn->query($sql_tipo_com);
        if ($result_tipo_com->num_rows > 0) {
            while ($row_tipo_com = $result_tipo_com->fetch_assoc()) {
                $id_tipo_com = $row_tipo_com['id_tipo_com'];
                $tipo_com = $row_tipo_com['tipo_com'];
                $opt_tipo_comissao .= "\n<option value='$id_tipo_com'>$tipo_com</option>";
            }
        } else {
            $opt_tipo_comissao = '<option>Erro no sistema, informe o Marketing</option>';
        }
    } elseif ($action == "consultor") {
        
    } else {
        
    }
}
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

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
                                    <span>Pesquisar Angariação</span>
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
                                                        <i class="fa fa-search font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase"> Pesquisar Angariação</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row <?= $row_inicio; ?>">
                                                        <div class="col-md-6 margin-bottom-15">
                                                            <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao/id-imovel" class="btn blue btn-outline btn-block sbold uppercase">
                                                                PESQUISAR POR ID DO IMÓVEL
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6 margin-bottom-15">
                                                            <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao/consultor" class="btn purple-seance btn-outline btn-block sbold uppercase">
                                                                PESQUISAR POR CONSULTOR
                                                            </a>                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row <?= $row_id_imovel; ?>">
                                                        <div class="col-md-12 margin-bottom-40">
                                                            <?php
                                                            if (isset($_SESSION['msg_id_imovel'])) {
                                                                echo $_SESSION['msg_id_imovel'];
                                                                unset($_SESSION['msg_id_imovel']);
                                                            }
                                                            ?>
                                                            <form role="form" method="post" action="<?php setHome(); ?>/tpl/actions/pesquisar_angariacao.php">
                                                                <div class="form-group">
                                                                    <input minlength="10" maxlength="14" name="id_imovel" type="text" class="form-control id_angariacao" placeholder="Digite o ID do Imóvel">
                                                                </div>
                                                                <div class="form-actions text-right">
                                                                    <input class="btn blue sbold uppercase" type="submit" name="enviar" value="Pesquisar Por ID do Imóvel"> 
                                                                    <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao" class="btn red-thunderbird sbold uppercase">Cancelar e Voltar</a>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="row <?= $row_consultor; ?>">
                                                        <div class="col-md-12 margin-bottom-40 <?= $busca_consultor ?>">
                                                            <?php
                                                            if (isset($_SESSION['msg_consultor'])) {
                                                                echo $_SESSION['msg_consultor'];
                                                                unset($_SESSION['msg_consultor']);
                                                            }
                                                            ?>
                                                            <form role="form" method="post" action="<?php setHome(); ?>/tpl/actions/pesquisar_angariacao.php">
                                                                <div class="form-group">
                                                                    <select name="id_consultor" class="form-control select2">
                                                                        <option value="<?= $id_consultor; ?>"><?= $nome_consultor; ?></option>
                                                                        <?= $opt_consultores_loja; ?>                                                                    
                                                                    </select>
                                                                </div>
                                                                <div class="form-actions text-right">
                                                                    <input class="btn purple-seance sbold uppercase" type="submit" name="enviar" value="Pesquisar Por Consultor"> 
                                                                    <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao" class="btn red-thunderbird sbold uppercase">Cancelar e Voltar</a>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-md-12 <?= $resultado_busca_consultor ?>">
                                                            <?php
                                                            if (isset($_SESSION['msg_busca_consultor'])) {
                                                                echo $_SESSION['msg_busca_consultor'];
                                                                unset($_SESSION['msg_busca_consultor']);
                                                            }
                                                            ?>
                                                            <div class="col-md-2 col-sm-2 col-xs-6 text-center uppercase bold font-red-thunderbird">Significado<br>das cores</div>
                                                            <div class="col-md-2 col-sm-2 col-xs-6">
                                                                <div class="color-demo">
                                                                    <div class="color-info bg-purple-seance bg-font-blue-hoki bold uppercase">Ativas</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-sm-2 col-xs-6">
                                                                <div class="color-demo">
                                                                    <div class="color-info bg-yellow bg-font-blue-hoki bold uppercase">Reservadas</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-sm-2 col-xs-6">
                                                                <div class="color-demo">
                                                                    <div class="color-info bg-green-jungle bg-font-blue-hoki bold uppercase">Vendidas</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-sm-2 col-xs-6">
                                                                <div class="color-demo">
                                                                    <div class="color-info bg-blue bg-font-blue-hoki bold uppercase">Arrendadas</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-sm-2 col-xs-6">
                                                                <div class="color-demo">
                                                                    <div class="color-info bg-red-thunderbird bg-font-blue-hoki bold uppercase">Canceladas</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 margin-bottom-40 <?= $resultado_busca_consultor ?>">
                                                            <div>
                                                                <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao/consultor" class="btn green-jungle btn-block uppercase bold"> Procurar Outro Consultor </a>
                                                            </div>
                                                            <h3 class="text-center bold font-blue">Resultado da busca</h3>
                                                            <?php
                                                            $bgcolor = "";
                                                            if ($consultor_pesquisado != "") {
                                                                $sel_angs_consultor = "
                                                                SELECT id_angariacao.id_imovel id_imovel_angs_consultor, livro_registo.estado_imovel estado_imovel_angs_consultor
                                                                FROM id_angariacao 
                                                                INNER JOIN livro_registo ON livro_registo.angariacao_id = id_angariacao.angariacao_id
                                                                WHERE id_angariacao.consultor = $consultor_pesquisado AND id_angariacao.loja = $user_id_loja 
                                                                ORDER BY livro_registo.estado_imovel
                                                            ";
                                                                $result_angs_consultor = $conn->query($sel_angs_consultor);
                                                                if ($result_angs_consultor->num_rows > 0) {
                                                                    while ($row_angs_consultor = $result_angs_consultor->fetch_assoc()) {
                                                                        $id_imovel_angs_consultor = $row_angs_consultor['id_imovel_angs_consultor'];
                                                                        $estado_imovel_angs_consultor = $row_angs_consultor['estado_imovel_angs_consultor'];
                                                                        switch ($estado_imovel_angs_consultor) {
                                                                            case 1:
                                                                                $bgcolor = "bg-purple-seance";
                                                                                break;
                                                                            case 2:
                                                                                $bgcolor = "bg-yellow";
                                                                                break;
                                                                            case 3:
                                                                                $bgcolor = "bg-green-jungle";
                                                                                break;
                                                                            case 4:
                                                                                $bgcolor = "bg-blue";
                                                                                break;
                                                                            case 5:
                                                                                $bgcolor = "bg-red-thunderbird";
                                                                                break;
                                                                            default:
                                                                                $bgcolor = "bg-default";
                                                                        }
                                                                        ?>
                                                                        <div class="col-md-2 col-sm-2 col-xs-6">
                                                                            <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao/id-imovel/<?= $id_imovel_angs_consultor ?>" style="text-decoration:none">
                                                                                <div class="color-demo tooltips" data-original-title="Carregue para Abrir">
                                                                                    <div class="color-info <?= $bgcolor; ?> bg-font-blue-hoki bold uppercase"><?= $id_imovel_angs_consultor ?></div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    echo "<div class='col-md-12'><div class='alert alert-info'><strong>Sem Resultados!</strong> Não existe Angariações deste Consultor </div></div>";
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="row <?= $row_form; ?>">
                                                        <div class="col-md-12 margin-bottom-40" id="form_wizard_1">
                                                            <?php
                                                            if (isset($_SESSION['msg_form'])) {
                                                                echo $_SESSION['msg_form'];
                                                                unset($_SESSION['msg_form']);
                                                            }
                                                            ?>
                                                            <form  role="form" class="margin-bottom-40" action="<?php setHome(); ?>/tpl/actions/upd_pesquisar-angariacao.php" id="submit_form" method="POST">
                                                                <div class="form-wizard">
                                                                    <div class="form-body">
                                                                        <ul class="nav nav-pills nav-justified steps">
                                                                            <li>
                                                                                <a href="#tab1" data-toggle="tab" class="step">
                                                                                    <span class="number"> 1 </span>
                                                                                    <span class="desc">
                                                                                        <i class="fa fa-check"></i> Resultado da Busca
                                                                                    </span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#tab2" data-toggle="tab" class="step">
                                                                                    <span class="number"> 2 </span>
                                                                                    <span class="desc">
                                                                                        <i class="fa fa-check"></i> Contratual 
                                                                                    </span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#tab3" data-toggle="tab" class="step">
                                                                                    <span class="number"> 3 </span>
                                                                                    <span class="desc">
                                                                                        <i class="fa fa-check"></i> Dados do Imóvel
                                                                                    </span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#tab4" data-toggle="tab" class="step">
                                                                                    <span class="number"> 4 </span>
                                                                                    <span class="desc">
                                                                                        <i class="fa fa-check"></i> Dados do Proprietário  
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
                                                                                <h3 class="block">Informações Atuais do Sistema</h3>
                                                                                <h4 class="form-section">Contratual</h4>
                                                                                <hr>
                                                                                <div class="row">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group font-red-thunderbird">
                                                                                            <label class="control-label bold">Número do Contrato</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $num_contrato; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group font-red-thunderbird">
                                                                                            <label class="control-label bold">ID Atual do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $id_atual_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">ID Inicial do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $id_inicial_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Consultor</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $nome_consultor; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Gestor</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $nome_gestor; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                    
                                                                                </div>                                                                                
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold font-red-thunderbird">Deve Contabilizar no Espelho de Angariações?</label>
                                                                                            <div>
                                                                                                <p class="form-control-static font-red-thunderbird"> <?= $espelho; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Duração do Contrato</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $duracao_contrato; ?> dias </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Data de Aprovação ilist</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $data_insercao_ilist; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Data do CMI</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $data_cmi; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                                                                                                        
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Tipo de Regime</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $regime; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Tipo de Comissão</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $tipo_comissao; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Valor da Comissão</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $valor_comissao; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Tipo de Angariação</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $tipo_angariacao; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Tipo de Negócio</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $tipo_negocio; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>                                                                                
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group font-red-thunderbird">
                                                                                            <label class="control-label bold">Estado do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $estado_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Data do Negócio</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $data_negocio; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Data da Rescisão</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $data_recisao; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Valor do Negócio</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $valor_negocio; ?>€ </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Comissão Recebida</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $comissao_recebida; ?>€ </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <h4 class="form-section">Dados do Imóvel</h4>
                                                                                <hr>
                                                                                <div class="row">
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Rua/Avenida do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $arruamento_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Número da Porta</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $num_porta_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Andar</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $complemento_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Código Postal</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $cod_postal_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                    
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Freguesia do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $freguesia_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                  
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Concelho do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $concelho_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                  
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Distrito do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $distrito_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                  
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Tipo do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $tipo_imovel; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group font-red-thunderbird">
                                                                                            <label class="control-label bold">Valor Atual do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $valor_atual_imovel; ?>€ </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Valor Inicial do Imóvel</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $valor_inicial_imovel; ?>€ </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Conservatória</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $conservatoria; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Ficha (CRP)</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $ficha_crp; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Matriz (CPU)</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $matriz_cpu; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Fração</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $fracao; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <h4 class="form-section">Dados do Proprietário</h4>
                                                                                <hr>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Nome do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $nome_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">NIF do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $nif_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Email do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $email_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Telefone do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $telefone_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Rua/Avenida do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $arruamento_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Número da Porta</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $num_porta_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Andar</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $complemento_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Código Postal</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $cod_postal_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                    
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Freguesia do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $freguesia_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                  
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Concelho do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $concelho_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                  
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label bold">Distrito do Proprietário</label>
                                                                                            <div>
                                                                                                <p class="form-control-static"> <?= $distrito_proprietario; ?> </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                  
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane" id="tab2">
                                                                                <h3 class="block">Contratual</h3>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Nº do Contrato
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="num_contrato" minlength="6" maxlength="13" class="form-control form-control-inline" type="text" value="<?= $num_contrato; ?>" placeholder="Digite o Número de Contrato" readonly />
                                                                                                <span class="help-block font-red"> Para alterar o Número de Contrato, informe o Marketing </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">ID Atual do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="id_atual_imovel" maxlength="14" class="form-control form-control-inline id_angariacao" type="text" value="<?= $id_atual_imovel; ?>" />
                                                                                                <input type="hidden" name="angariacao_id" value="<?= $angariacao_id; ?>">
                                                                                                <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                                                                                <input type="hidden" name="user_nome" value="<?= $user_nome; ?>">
                                                                                                <input type="hidden" name="user_id_loja" value="<?= $user_id_loja; ?>">
                                                                                                <input type="hidden" name="user_nome_loja" value="<?= $user_nome_loja; ?>">
                                                                                                <input type="hidden" name="id_imovel_procurado" value="<?= $id_imovel_procurado; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">ID Inicial do Imóvel (Livro de Registo)
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="id_inicial_imovel" maxlength="14" class="form-control form-control-inline id_angariacao" type="text" value="<?= $id_inicial_imovel; ?>" />
                                                                                                <span class="help-block font-red"> Só alterar o ID Inicial do Imóvel em caso de erro. </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Selecione o Consultor
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="id_consultor" class="form-control select2 dropd">
                                                                                                <option value="<?= $id_consultor; ?>"><?= $nome_consultor; ?></option>
                                                                                                <?= $opt_consultores_loja; ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Contabilizar para o Espelho de Angariações?
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="espelho_angariacao" class="form-control">
                                                                                                <option value="<?= $vai_espelho; ?>"><?= $espelho; ?></option>
                                                                                                <option value="1">Sim</option> 
                                                                                                <option value="0">Não</option> 
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Duração do Contrato
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="dur_contrato" maxlength="3" class="form-control form-control-inline numeros" type="text" value="<?= $duracao_contrato; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Data de Aprovação no Ilist <span class="erro">* </span></label>
                                                                                            <input name="insercao_ilist" class="form-control form-control-inline date-picker data" type="text" value="<?= $data_insercao_ilist; ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Data do CMI
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="data_cmi" class="form-control form-control-inline date-picker data" type="text" value="<?= $data_cmi; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Regime
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="regime" class="form-control select2 dropd desbloqueia">
                                                                                                <option value="<?= $id_regime; ?>"><?= $regime; ?></option>
                                                                                                <?= $opt_tipo_regime; ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Tipo de Comissão
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="tipo_comissao" class="form-control select2 dropd tip-com">
                                                                                                <option value="<?= $id_tipo_comissao; ?>"><?= $tipo_comissao; ?></option>
                                                                                                <?= $opt_tipo_comissao; ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Valor da Comissão
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="valor_comissao" class="form-control form-control-inline val-com <?= $fmt; ?>" type="text" value="<?= $valor_comissao; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Tipo de Angariação
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="tipo_angariacao" class="form-control select2 dropd">
                                                                                                <option value="<?= $id_tipo_angariacao; ?> "><?= $tipo_angariacao; ?> </option>
                                                                                                <?= $opt_tipo_angariacoes; ?> 
                                                                                            </select> 
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Tipo de Negócio
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="tipo_negocio" class="form-control select2 dropd">
                                                                                                <option value="<?= $id_tipo_negocio; ?> "><?= $tipo_negocio; ?> </option>
                                                                                                <?= $opt_tipo_negocio; ?> 
                                                                                            </select> 
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Estado do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <select name="estado_imovel" class="form-control select2 dropd">
                                                                                                <option value="<?= $id_estado_imovel; ?>"><?= $estado_imovel; ?></option>
                                                                                                <?= $opt_estado_imovel; ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Data do Negócio
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="data_negocio" class="form-control form-control-inline date-picker data" type="text" value="<?= $data_negocio; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Data da Rescisão
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="data_rescisao" class="form-control form-control-inline date-picker data" type="text" value="<?= $data_recisao; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Valor do Negócio
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="valor_negocio" class="form-control form-control-inline comissao" type="text" value="<?= $valor_negocio; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Comissão Recebida
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="comissao_recebida" class="form-control form-control-inline comissao" type="text" value="<?= $comissao_recebida; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>                                                                                    
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane" id="tab3">
                                                                                <h3 class="block">Dados do Imóvel</h3>
                                                                                <div class="row">
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Rua/Avenida do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="arruamento_imv" class="form-control form-control-inline" type="text" value="<?= $arruamento_imovel; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Número da Porta
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="num_porta_imv" class="form-control form-control-inline" type="text" value="<?= $num_porta_imovel; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Andar</label>
                                                                                            <div>
                                                                                                <input name="complemento_imv" class="form-control form-control-inline" type="text" value="<?= $complemento_imovel; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Código Postal
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="cod_postal_imv" class="form-control form-control-inline cep" type="text" value="<?= $cod_postal_imovel; ?>" />
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
                                                                                                <option value="<?= $id_freguesia_imovel; ?>"><?= $freguesia_imovel; ?></option>
                                                                                                <?= $opt_freguesias; ?>
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
                                                                                            <select name="tipo_imovel" class="form-control select2 dropd">
                                                                                                <option value="<?= $id_tipo_imovel; ?> "><?= $tipo_imovel; ?> </option>
                                                                                                <?= $opt_tipo_imoveis; ?> 
                                                                                            </select> 
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Valor Atual do Imóvel
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="valor_atual_imovel" class="form-control form-control-inline comissao" type="text" value="<?= $valor_atual_imovel; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Valor Inicial do Imóvel (Livro de Registo)
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="valor_inicial_imovel" class="form-control form-control-inline comissao" type="text" value="<?= $valor_inicial_imovel; ?>" />
                                                                                                <span class="help-block font-red"> Só alterar o Valor Inicial do Imóvel em caso de erro. </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Conservatória
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="conservatoria" class="form-control form-control-inline" type="text" value="<?= $conservatoria; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Ficha (CRP)
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="ficha_crp" class="form-control form-control-inline" type="text" value="<?= $ficha_crp; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Matriz (CPU)
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="matriz_cpu" class="form-control form-control-inline" type="text" value="<?= $matriz_cpu; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Fração</label>
                                                                                            <div>
                                                                                                <input name="fracao" class="form-control form-control-inline" type="text" value="<?= $fracao; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane" id="tab4">
                                                                                <h3 class="block">Dados dos Proprietário</h3>
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Nome do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="nome_prop" class="form-control form-control-inline" type="text" value="<?= $nome_proprietario; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">NIF do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="nif_prop" minlength="9" maxlength="9" class="form-control form-control-inline nif" type="text" value="<?= $nif_proprietario; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Email do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="email_prop" class="form-control form-control-inline" type="text" value="<?= $email_proprietario; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Telefone do Proprietário
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="telefone_prop" minlength="9" maxlength="20" class="form-control form-control-inline phone" type="text" value="<?= $telefone_proprietario; ?>" />
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
                                                                                                <input name="arruamento_prop" class="form-control form-control-inline" type="text" value="<?= $arruamento_proprietario; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Número da Porta
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="num_porta_prop" class="form-control form-control-inline" type="text" value="<?= $num_porta_proprietario; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Andar</label>
                                                                                            <div>
                                                                                                <input name="complemento_prop" class="form-control form-control-inline" type="text" value="<?= $complemento_proprietario; ?>" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label">Código Postal
                                                                                                <span class="required"> * </span>
                                                                                            </label>
                                                                                            <div>
                                                                                                <input name="cod_postal_prop" class="form-control form-control-inline cep" type="text" value="<?= $cod_postal_proprietario; ?>" />
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
                                                                                                <option value="<?= $id_freguesia_proprietario; ?>"><?= $freguesia_proprietario; ?></option>
                                                                                                <?= $opt_freguesias; ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>                                                                                
                                                                            </div>                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <div class="row margin-top-20">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao" class="btn red uppercase bold">
                                                                                    <i class="fa fa-angle-left"></i> Pesquisar Outro Imóvel
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 text-right">
                                                                            <div class="form-group">
                                                                                <a href="javascript:;" class="btn default uppercase bold button-previous">
                                                                                    <i class="fa fa-angle-left"></i> Voltar </a>
                                                                                <a href="javascript:;" class="btn blue uppercase bold button-next"> Editar Informações
                                                                                    <i class="fa fa-angle-right"></i>
                                                                                </a>
                                                                                <button type="submit" class="btn green-meadow uppercase bold button-submit"> Guardar Informações
                                                                                    <i class="fa fa-check"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>                                                                            
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="row <?= $row_erro; ?>">
                                                        <div class="col-md-12 margin-bottom-20">
                                                            <div class="note note-danger font-red-thunderbird">
                                                                <h4 class="block bold">Página Inexistente</h4>
                                                                <p>A página que pretende aceder não existe ou foi bloqueada pelo administrador. Por favor clique no botão abaixo e tente novamente!</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 margin-bottom-40">
                                                            <a href="<?php setHome(); ?>/coordenacao/pesquisar-angariacao" class="btn green-meadow btn-block sbold uppercase">
                                                                Pesquisar Novamente
                                                            </a>
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
    $('.tip-com').on('change', function () {
        if (this.value == '1') {
            $('.val-com').addClass("numeros");
            $('.val-com').removeClass("comissao");
        } else {
            $('.val-com').addClass("comissao");
            $('.val-com').removeClass("numeros");
        }
    });
    jQuery('.numponto').keyup(function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
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
    $('.data').mask("00/00/0000", {reverse: true});
    $('.numeros').mask("###", {reverse: true});
</script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>
<?php
if (isset($_SESSION['msg_id_imovel'])) {
    unset($_SESSION['msg_id_imovel']);
}
if (isset($_SESSION['msg_consultor'])) {
    unset($_SESSION['msg_consultor']);
}
if (isset($_SESSION['final_consultor'])) {
    unset($_SESSION['final_consultor']);
    if (isset($_SESSION['consultor_pesquisado'])) {
        unset($_SESSION['consultor_pesquisado']);
    }
}
?>