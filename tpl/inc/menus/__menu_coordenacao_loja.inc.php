<?php
session_start();
if (!isset($_SESSION['login'])) {
    echo 'não permitido';
    exit();
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