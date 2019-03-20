<?php
$today = date("Y-m-d");
$datetime1 = new DateTime('2017-11-06');
$datetime2 = new DateTime($today);
$interval = $datetime1->diff($datetime2);
echo $interval->format('%a days');