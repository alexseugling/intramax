<?php
if (!isset($_SESSION['login'], $_SESSION['menu_coordenacao'])) {
    ?>
    <script type="text/javascript">location.href = "<?php setHome(); ?>/tpl/actions/logout.php";</script>
    <noscript><meta http-equiv="refresh" content="0; URL=<?php setHome(); ?>/tpl/actions/logout.php" /></noscript>
    <?php
    exit;
}
?>
<!-- BEGIN HEADER MENU -->
<div class="page-header-menu">
    <div class="container">
        <div class="hor-menu hor-menu-light">
            <ul class="nav navbar-nav">
                <?php include_once 'inc/inicio.inc.php'; ?>
                <?php include_once 'inc/coordenacao.inc.php'; ?>
            </ul>
        </div>
        <!-- END MEGA MENU -->
    </div>
</div>