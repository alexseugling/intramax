<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown <?php echo ($page == 'controlo_produto' || $page == 'consultor' || $page == 'lojas_dz') ? 'active' : ''; ?>">
    <a href="javascript:;">
        <i class="fa fa-check"></i> Controlo de Produto
        <span class="arrow"></span>
    </a>
    <ul class="dropdown-menu pull-left" style="min-width: 310px">
        <li <?php echo ($page == 'lojas_dz') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/controlo_produto/minhas-lojas">
                <i class="fa fa-bank"></i> Minhas Lojas
            </a>
        </li>
    </ul>
</li>