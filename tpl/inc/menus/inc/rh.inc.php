<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown <?php echo ($page == 'rh' || $page == 'adicionar-colaborador' || $page == 'atualizar-colaborador' || $page == 'remover-colaborador' || $page == 'localizar-colaborador') ? 'active' : ''; ?>">
    <a href="javascript:;">
        <i class="fa fa-gears"></i> RH
        <span class="arrow"></span>
    </a>
    <ul class="dropdown-menu pull-left">
        <li <?php echo ($page == 'adicionar-colaborador') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/rh/adicionar-colaborador">
                <i class="fa fa-user-plus"></i> Adicionar Colaborador
            </a>
        </li>
        <li <?php echo ($page == 'atualizar-colaborador') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/rh/atualizar-colaborador">
                <i class="fa fa-refresh"></i> Atualizar Colaborador
            </a>
        </li>
        <li <?php echo ($page == 'remover-colaborador') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/rh/remover-colaborador">
                <i class="fa fa-user-times"></i> Remover Colaborador
            </a>
        </li>
        <li <?php echo ($page == 'localizar-colaborador') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/rh/localizar-colaborador">
                <i class="fa fa-search"></i> Localizar Colaborador
            </a>
        </li>
    </ul>
</li>