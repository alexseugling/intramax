<script src="<?php setHome(); ?>/tpl/assets/global/plugins/respond.min.js"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/excanvas.min.js"></script> 
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?php setHome(); ?>/tpl/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?php setHome(); ?>/tpl/assets/layouts/remax_vantagem/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/layouts/remax_vantagem/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php setHome(); ?>/tpl/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<!-- BEGIN DATA-HORA -->
<script type="text/javascript">
    function mostrarDataHora(mostrahora, dataextenso, horas, minutos, segundos, dia, mes, ano, Dia, Mes) {
        document.getElementById("horas").innerHTML = mostrahora;
        document.getElementById("dataextenso").innerHTML = dataextenso;
    }
    function atualizarDataHora() {
        var currentTime = new Date()
        var horas = currentTime.getHours();
        var minutos = currentTime.getMinutes();
        var segundos = currentTime.getSeconds();
        var dia = currentTime.getDate();
        var mes = currentTime.getMonth();
        var ano = currentTime.getFullYear();
        var Dia = currentTime.getDay();
        var Mes = currentTime.getUTCMonth();
        arrayDia = new Array();
        arrayDia[0] = "Domingo";
        arrayDia[1] = "Segunda-Feira";
        arrayDia[2] = "Terça-Feira";
        arrayDia[3] = "Quarta-Feira";
        arrayDia[4] = "Quinta-Feira";
        arrayDia[5] = "Sexta-Feira";
        arrayDia[6] = "Sábado";
        var arrayMes = new Array();
        arrayMes[0] = "Janeiro";
        arrayMes[1] = "Fevereiro";
        arrayMes[2] = "Março";
        arrayMes[3] = "Abril";
        arrayMes[4] = "Maio";
        arrayMes[5] = "Junho";
        arrayMes[6] = "Julho";
        arrayMes[7] = "Agosto";
        arrayMes[8] = "Setembro";
        arrayMes[9] = "Outubro";
        arrayMes[10] = "Novembro";
        arrayMes[11] = "Dezembro";
        if (minutos < 10)
            minutos = "0" + minutos
        if (segundos < 10)
            segundos = "0" + segundos
        if (dia < 10)
            dia = "0" + dia
        if (mes < 10)
            mes = "0" + mes
        mostrahora = horas + ":" + minutos + ":" + segundos;
        var dataextenso = arrayDia[Dia] + ", " + dia + " de " + arrayMes[Mes] + " de " + ano;
        mostrarDataHora(mostrahora, dataextenso, horas, minutos, segundos, dia, mes, ano, Dia, Mes);
        setTimeout("atualizarDataHora()", 1000);
    }
</script>
<!-- END DATA-HORA -->
