<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_imovel = $_SESSION['id_imovel'];
    unset($_SESSION['id_imovel']);
    $created_by = $_SESSION['user_id'];
    $angariacao_id = $_SESSION['angariacao_id'];
    unset($_SESSION['angariacao_id']);
    $id_loja = $_SESSION['id_loja'];
    unset($_SESSION['id_loja']);
    $satisfacao = "";

    $freq_contato = filter_input(INPUT_POST, 'freq_contato', FILTER_SANITIZE_STRING);
    $quant_contatos = filter_input(INPUT_POST, 'quant_contatos', FILTER_SANITIZE_NUMBER_INT);
    $last_contact = implode("-", array_reverse(explode("/", filter_input(INPUT_POST, 'last_contact', FILTER_SANITIZE_STRING)))) . " " . date("H:i:s");
    $freq_visitas = filter_input(INPUT_POST, 'freq_visitas', FILTER_SANITIZE_STRING);
    $quant_visitas = filter_input(INPUT_POST, 'quant_visitas', FILTER_SANITIZE_NUMBER_INT);
    $classificacao_consultor = filter_input(INPUT_POST, 'classificacao_consultor', FILTER_SANITIZE_NUMBER_INT);
    $recomendacao_marca = filter_input(INPUT_POST, 'recomendacao_marca', FILTER_SANITIZE_NUMBER_INT);
    $obs_cliente = filter_input(INPUT_POST, 'sugestao', FILTER_SANITIZE_STRING);

//    echo "angariacao_id: " . $angariacao_id . "<br>";
//    echo "data: NOW()<br>";
//    echo "created_by: " . $created_by . "<br>";
//    echo "loja: " . $id_loja . "<br>";
//    echo "id_imovel: " . $id_imovel . "<br>";
//    
//    echo "freq_contato: " . $freq_contato . "<br>";
//    echo "quant_contatos: " . $quant_contatos . "<br>";
//    echo "last_contact: " . $last_contact . "<br>";
//    echo "freq_visitas: " . $freq_visitas . "<br>";
//    echo "quant_visitas: " . $quant_visitas . "<br>";
//    echo "classificacao_consultor: " . $classificacao_consultor . "<br>";
//    echo "recomendacao_marca: " . $recomendacao_marca . "<br>";
//    echo "satisfacao: calcular<br>";
//    echo "obs_cliente: " . $obs_cliente . "<br>";

    if ($id_imovel != "" && $angariacao_id != "" && $created_by != "" && $id_loja != "" && $freq_contato != "" && $last_contact != "" && $freq_visitas != "" && $classificacao_consultor != "" && $recomendacao_marca != "") {
        if ($freq_contato == "mensal" && $quant_contatos == 1 || $freq_contato == "nunca") {
            $satisfacao = 0;
        } else {
            $satisfacao = 1;
        }
        if ($freq_contato == "nunca") {
            $quant_contatos = 0;
        }
        if ($freq_visitas == "nunca"){
            $quant_visitas = 0;
        }
        include_once("conexao.php");
//        echo "Satisfação: $satisfacao<br>Quantidade de Contatos: $quant_contatos";
        $sql_insert = "
            INSERT INTO qlt_q60 (angariacao_id, data, created_by, loja, id_imovel, freq_contato, quant_contatos, last_contact, freq_visitas, quant_visitas, classificacao_consultor, recomendacao_marca, satisfacao, obs_cliente) 
            VALUES ({$angariacao_id}, NOW(), {$created_by}, {$id_loja}, '{$id_imovel}', '{$freq_contato}', {$quant_contatos}, '{$last_contact}', '{$freq_visitas}', {$quant_visitas}, {$classificacao_consultor}, {$recomendacao_marca}, {$satisfacao}, '{$obs_cliente}')
        ";
        if ($conn->query($sql_insert) === TRUE) {
            $sql_upd = "UPDATE id_angariacao SET an_60 = 1 WHERE id_imovel = '{$id_imovel}'";

            if ($conn->query($sql_upd) === TRUE) {
                $_SESSION['msg_out'] = "<div class='note note-success bold font-green-meadow'><p> Parabéns! O questionário de 60 dias relacionado ao Imóvel {$id_imovel} foi finalizado com sucesso. </p></div>";
                header("Location: ../../qualidade/angariacoes-60-dias");
            } else {
                $_SESSION['msg_out'] = "<div class='note note-danger bold font-red-intense'><p> Atenção! O questionário do Imóvel {$id_imovel} foi salvo ENTRETANTO devido a um Erro, o imóvel ainda aparece nesta listagem. Informe o Departamento do Marketing </p></div>";
                header("Location: ../../qualidade/angariacoes-60-dias");
            }
        } else {
            $_SESSION['msg_err'] = "<div class='note note-danger bold font-red-intense'><p> Erro fatal! Anote o ID do Imóvel {$id_imovel} junto com as respostas do cliente e informe o Departamento do Marketing </p></div>";
            header("Location: ../../qualidade/questionario-60-dias/{$id_imovel}");
        }
    } else {
        $_SESSION['msg_err'] = '<div class="note note-danger bold font-red-intense"><p> Favor Preencher TODOS os Campos Obrigatórios! </p></div>';
        header("Location: ../../qualidade/questionario-60-dias/{$id_imovel}");
    }
}