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

<!-- END PAGE LEVEL PLUGINS -->

<?php
include 'tpl/inc/head_body.inc.php';
$page = 'observacoes';
$id_imovel = $url[2];
$last = $url[3];
switch ($last) {
    case "15":
        $_SESSION['field'] = 15;
        break;
    case "60":
        $_SESSION['field'] = 60;
        break;
    case "120":
        $_SESSION['field'] = 120;
        break;
    case "130":
        $_SESSION['field'] = 130;
        break;
    default:
        $_SESSION['field'] = "";
}

$sel_field = $_SESSION['field'];

include_once("tpl/actions/conexao.php");

$sql = "SELECT livro_registo.angariacao_id FROM livro_registo WHERE livro_registo.id_inicial_imovel = '{$id_imovel}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['id_imovel'] = $id_imovel;
    $erro = "hidden";
    $encontrado = "";
    while ($row = $result->fetch_assoc()) {
        $angariacao_id = $row["angariacao_id"];
        $_SESSION['angariacao_id'] = $angariacao_id;
        $sql_obs = "
        SELECT qlt_obs.created_by, colaboradores.nome, qlt_obs.data, qlt_obs.comentario, qlt_obs.field 
        FROM qlt_obs 
        INNER JOIN colaboradores ON qlt_obs.created_by = colaboradores.id
        WHERE qlt_obs.angariacao_id = {$angariacao_id} AND qlt_obs.field = {$sel_field}
        ORDER BY qlt_obs.id DESC
        ";
    }
} else {
    $erro = $_SESSION['id_imovel'] = "";
    $encontrado = "hidden";
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
                                    <span>Observações</span>
                                </li>
                            </ul>
                            <!-- END PAGE BREADCRUMBS -->
                            <!-- BEGIN PAGE CONTENT INNER -->
                            <div class="page-content-inner">
                                <div class="mt-content-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet box blue-hoki">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-comment"></i> Observações
                                                    </div>
                                                </div>
                                                <div class="portlet-body">   
                                                    <div class="row <?= $erro; ?>">
                                                        <div class="portlet light portlet-fit">
                                                            <div class="portlet-body">
                                                                <div class="note note-danger bold font-red-intense">
                                                                    <p> ESTE ID DE IMÓVEL NÃO EXISTE! POR FAVOR VERIFIQUE. </p>
                                                                </div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <div class="row <?= $encontrado; ?>">
                                                        <div class="portlet light portlet-fit">
                                                            <div class="portlet-body">
                                                                <?php
                                                                if (isset($_SESSION['msg_obs'])) {
                                                                    echo $_SESSION['msg_obs'];
                                                                    unset($_SESSION['msg_obs']);
                                                                }
                                                                ?>
                                                                <h4 class="bold font-blue">Por qual razão não conseguiu fazer o Questionário de Satisfação?</h4>
                                                                <form class="form-horizontal margin-bottom-40" role="form" action="<?php setHome(); ?>/tpl/actions/qlt_observacoes.php" method="post">
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
                                                                        //$field = $row_obs["field"];
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