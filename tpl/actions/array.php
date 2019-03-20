<?php

$to = [];

$to[] = "alexseugling@outlook.com";
$to[] = "vseugling@gmail.com";

foreach ($to as $email):
    $mail->AddCC($email);
endforeach;