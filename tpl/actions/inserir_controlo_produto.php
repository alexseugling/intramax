<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loja = $_SESSION['user_id_loja'];
    $by_user = $_SESSION['user_id'];
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $id_cst = filter_input(INPUT_POST, 'id_cst', FILTER_SANITIZE_NUMBER_INT);
    $id_gst = filter_input(INPUT_POST, 'id_gst', FILTER_SANITIZE_NUMBER_INT);
    $pre_angariacao = filter_input(INPUT_POST, 'pre_angariacao', FILTER_SANITIZE_NUMBER_INT);
    $estudo_mercado = filter_input(INPUT_POST, 'estudo_mercado', FILTER_SANITIZE_NUMBER_INT);
    $previsao_fecho = filter_input(INPUT_POST, 'previsao_fecho', FILTER_SANITIZE_NUMBER_INT);
    if ($descricao == "" || $id_cst == "" || $pre_angariacao == "" || $estudo_mercado == "" || $previsao_fecho == "") {
        $_SESSION['msg_filtrado'] = "<div class='note note-danger bold font-red-intense'><p>PREENCHA OS CAMPOS OBRIGATÓRIOS</p></div>";
        header("Location: ../../controlo_produto/consultor/$id_cst");
    } else {
        include_once("conexao.php");
        $insert = "
            INSERT INTO controlo_produto (consultor, gestor, loja, descricao_negocio, pre_angariacao, estudo_mercado, 
            data_insercao, previsao_fecho, data_fecho, data_morte, estado, by_user) VALUES 
            ('$id_cst', '$id_gst', '$loja', '$descricao', '$pre_angariacao', '$estudo_mercado', NOW(), '$previsao_fecho', NULL, NULL, '1', '$by_user');
        ";
        if ($conn->query($insert) === TRUE) {
            $_SESSION['msg_filtrado'] = "<div class='note note-success bold font-green-meadow'><p>NOVO PRODUTO ADICIONADO</p></div>";
            header("Location: ../../controlo_produto/consultor/$id_cst");
        } else {
            $_SESSION['msg_filtrado'] = "<div class='note note-danger bold font-red-intense'><p>ERRO FATAL</p></div>";
            header("Location: ../../controlo_produto/consultor/$id_cst");
        }
    }
} else {
    $_SESSION['msg_filtrado'] = "<div class='note note-danger bold font-red-intense'><p>SISTEMA DE PROTEÇÃO ANTI-HACKER</p></div>";
    header("Location: ../../controlo_produto/consultor");
}