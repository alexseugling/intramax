<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown <?php echo ($page == 'qualidade' || $page == 'angariacoes-15-dias' || $page == 'angariacoes-60-dias' || $page == 'angariacoes-120-dias' || $page == 'angariacoes-130-dias' || $page == 'pesquisa-timeline') ? 'active' : ''; ?>">
    <a href="<?php setHome(); ?>/qualidade">
        <i class="fa fa-check"></i> Qualidade
        <span class="arrow"></span>
    </a>
    <ul class="dropdown-menu pull-left" style="min-width: 310px">
        <li <?php echo ($page == 'angariacoes-15-dias') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/qualidade/angariacoes-15-dias">
                <i class="fa fa-calendar-check-o"></i> Angariações - 15 Dias
            </a>
        </li>
        <li <?php echo ($page == 'angariacoes-60-dias') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/qualidade/angariacoes-60-dias">
                <i class="fa fa-calendar-check-o"></i> Angariações - 60 Dias
            </a>
        </li>
        <li <?php echo ($page == 'angariacoes-120-dias') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/qualidade/angariacoes-120-dias">
                <i class="fa fa-calendar-check-o"></i> Angariações - 120 Dias
            </a>
        </li>
        <li <?php echo ($page == 'angariacoes-130-dias') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/qualidade/angariacoes-130-dias">
                <i class="fa fa-calendar-check-o"></i> Angariações - 130 Dias
            </a>
        </li>
        <li <?php echo ($page == 'pesquisa-timeline') ? 'class="active"' : ''; ?>>
            <a href="<?php setHome(); ?>/qualidade/pesquisa-timeline">
                <i class="fa fa-search"></i> <i class="fa fa-phone"></i> Pesquisa (Timeline)
            </a>
        </li>
    </ul>
</li>