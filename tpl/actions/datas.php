<?php

$data = date_create("2017-12-27 11:14:37");
$data = date_format($data, 'd/m/Y H:i:s');

//$data2 = date_create("23-02-1981");
//$data2 = date_format($data2, 'Y-m-d H:i:s');

$data2 = implode("-", array_reverse(explode("/", "23/02/1981"))) . " " . date("H:i:s");

echo 'Em Português<br>';
echo $data."<hr>";

echo 'Em Ingles<br>';
echo $data2;

echo '<hr><hr><hr>';

$next_year = date('Y', strtotime('+1 year'));
$this_year = date('Y');
$true_now = date('Y-m-d H:i:s');

$first_day = $this_year."-01-01 00:00:00";
$last_day = $next_year."-01-01 00:00:00";

if ($true_now >= $first_day && $true_now < $last_day){
    echo "A Data Atual é Maior que $first_day e Menor que $last_day por isso faz o incremento";
} else {
    echo 'Faz um reset na contagem';
}