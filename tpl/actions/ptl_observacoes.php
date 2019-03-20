<?php

session_start();

$id_imovel = $_SESSION['id_imovel'];
$angariacao_id = $_SESSION['angariacao_id'];

if ($id_imovel != "" && $angariacao_id != "") {
    $obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_STRING);
    $created_by = $_SESSION['user_id'];
    if ($created_by != "" && $obs != "") {
        include_once("conexao.php");
        $sql_insert = "INSERT INTO qlt_obs (angariacao_id, created_by, data, comentario, field) VALUES ('$angariacao_id', '$created_by', NOW(), '$obs', 'pvr')";
        if ($conn->query($sql_insert) === TRUE) {
            $_SESSION['msg_obs'] = '<div class="note note-success bold font-green-meadow"><p> COMENTÁRIO INSERIDO COM SUCESSO. </p></div>';
            unset($_SESSION['id_imovel']);
            unset($_SESSION['angariacao_id']);
            header("Location: ../../qualidade/pesquisa-timeline/{$id_imovel}");
        } else {
            $_SESSION['msg_obs'] = '<div class="note note-danger bold font-red-intense"><p> ERRO 981: Informe o Departamento de Marketing. </p></div>';
            header("Location: ../../qualidade/pesquisa-timeline/{$id_imovel}");
        }
    }
} else {
    echo 'Hacker not allowed';
}
