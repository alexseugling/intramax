<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown <?php echo ($page == 'controlo_produto' || $page == 'consultor' || $page == 'lojas_dz') ? 'active' : ''; ?>">
    <a href="<?php setHome(); ?>/controlo_produto">
        <i class="fa fa-check"></i> Controlo de Produto
        <span class="arrow"></span>
    </a>
    <ul class="dropdown-menu pull-left" style="min-width: 310px">
        <li <?php echo ($page == 'consultor') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/tpl/actions/voltar_controlo_produto_consultor.php">
                <i class="fa fa-filter"></i> Filtro Consultor
            </a>
        </li>
        <li <?php echo ($page == 'lojas_dz') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/controlo_produto/minhas-lojas">
                <i class="fa fa-bank"></i> Minhas Lojas
            </a>
        </li>
    </ul>
</li>