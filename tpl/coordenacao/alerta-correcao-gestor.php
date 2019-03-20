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
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php setHome(); ?>/tpl/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'alerta-correcao-gestor';
include_once("tpl/actions/conexao.php");
$user_id_loja = $_SESSION['user_id_loja'];

$sql_c = "SELECT consultor.nome
FROM colaboradores consultor
INNER JOIN user_loja ON user_loja.user = consultor.id and user_loja.loja = $user_id_loja
INNER JOIN cargo ON cargo.user = consultor.id and cargo.tipo_cargo in (1,2,18,19)
WHERE consultor.status = 1
ORDER BY consultor.nome ASC";
$result_c = $conn->query($sql_c);

$sql_g = "SELECT gestor.nome
FROM colaboradores gestor
INNER JOIN cargo ON cargo.user = gestor.id AND cargo.tipo_cargo in (3,20)
INNER JOIN user_loja ON user_loja.user = gestor.id and user_loja.loja = $user_id_loja
WHERE gestor.status = 1
ORDER BY gestor.nome ASC";
$result_g = $conn->query($sql_g);
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
                                    <span>Alerta para Correção de Gestor</span>
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
                                                        <i class="fa fa-bell-o font-blue"></i>
                                                        <span class="caption-subject font-blue bold uppercase"> Alerta para Correção de Gestor</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">                                                    
                                                    <div class="row margin-bottom-40">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($_SESSION['msg_alerta_correcao_gestor'])) {
                                                                echo $_SESSION['msg_alerta_correcao_gestor'];
                                                                unset($_SESSION['msg_alerta_correcao_gestor']);
                                                            }
                                                            ?>
                                                            <form class="form-horizontal" role="form" action="<?php setHome(); ?>/tpl/actions/alerta_correcao_gestor.php" method="post">
                                                                <div class="form-group">
                                                                    <div class="col-md-6">
                                                                        <label for="single" class="control-label">Selecione o Constultor</label>
                                                                        <select name="nome_consultor" class="form-control select2" required>
                                                                            <option></option>
                                                                            <?php
                                                                            if ($result_c->num_rows > 0) {
                                                                                while ($row_c = $result_c->fetch_assoc()) {
                                                                                    $nome_consultor = $row_c['nome'];
                                                                                    echo "<option>$nome_consultor</option>";
                                                                                }
                                                                            } else {
                                                                                echo '<option>Não existe consultores nesta loja</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="single" class="control-label">Selecione o Gestor Responsável</label>
                                                                        <select name="nome_gestor" class="form-control select2" required>
                                                                            <option></option>
                                                                            <?php
                                                                            if ($result_g->num_rows > 0) {
                                                                                while ($row_g = $result_g->fetch_assoc()) {
                                                                                    $nome_gestor = $row_g['nome'];
                                                                                    echo "<option>$nome_gestor</option>";
                                                                                }
                                                                            } else {
                                                                                echo '<option>Não existe gestores nesta loja</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-actions margin-top-30">
                                                                    <button type="submit" class="btn blue btn-block bold uppercase">Solicitar Correção ao RH</button>
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
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>