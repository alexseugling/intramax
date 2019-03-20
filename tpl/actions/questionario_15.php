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

    $classificacao_consultor = filter_input(INPUT_POST, 'classificacao_consultor', FILTER_SANITIZE_NUMBER_INT);
    $recomendacao_marca = filter_input(INPUT_POST, 'recomendacao_marca', FILTER_SANITIZE_NUMBER_INT);
    $mediacao_imobiliaria = filter_input(INPUT_POST, 'mediacao_imobiliaria', FILTER_SANITIZE_NUMBER_INT);
    $placa_imovel = filter_input(INPUT_POST, 'placa_imovel', FILTER_SANITIZE_NUMBER_INT);
    $obs_cliente = filter_input(INPUT_POST, 'sugestao', FILTER_SANITIZE_STRING);

//    echo "angariacao_id: " . $angariacao_id . "<br>";
//    echo "created_by: " . $created_by . "<br>";
//    echo "data: NOW()<br>";
//    echo "contrato_mediacao: " . $mediacao_imobiliaria . "<br>";
//    echo "placa_imovel: " . $placa_imovel . "<br>";
//    echo "efic_consultor: " . $classificacao_consultor . "<br>";
//    echo "rec_grupo: " . $recomendacao_marca . "<br>";
//    echo "obs_cliente: " . $obs_cliente . "<br>";
//    echo "satifacao: calcular<br>";
//    echo "id_imovel: " . $id_imovel . "<br>";
//    echo "loja: " . $id_loja . "<br>";

    if ($angariacao_id != "" && $created_by != "" && $id_imovel != "" && $id_loja != "" && $classificacao_consultor != "" && $recomendacao_marca != "" && $mediacao_imobiliaria != "" && $placa_imovel != "") {
        if ($classificacao_consultor <= 6) {
            $satisfacao = 0;
        } else {
            $satisfacao = 1;
        }
        include_once("conexao.php");
        $sql_insert = "
            INSERT INTO qlt_q15 
            (angariacao_id, data, created_by, loja, id_imovel, contrato_mediacao, placa_imovel, efic_consultor, rec_grupo, satisfacao, obs_cliente) 
            VALUES ({$angariacao_id}, NOW(), {$created_by}, {$id_loja}, '{$id_imovel}', {$mediacao_imobiliaria}, {$placa_imovel}, {$classificacao_consultor}, {$recomendacao_marca}, {$satisfacao}, '{$obs_cliente}');
        ";
        if ($conn->query($sql_insert) === TRUE) {
            $sql_upd = "UPDATE id_angariacao SET an_15 = 1 WHERE id_imovel = '{$id_imovel}'";

            if ($conn->query($sql_upd) === TRUE) {
                $_SESSION['msg_out'] = "<div class='note note-success bold font-green-meadow'><p> Parabéns! O questionário de 15 dias relacionado ao Imóvel {$id_imovel} foi finalizado com sucesso. </p></div>";
                header("Location: ../../qualidade/angariacoes-15-dias");
            } else {
                $_SESSION['msg_out'] = "<div class='note note-danger bold font-red-intense'><p> Atenção! O questionário do Imóvel {$id_imovel} foi salvo ENTRETANTO devido a um Erro, o imóvel ainda aparece nesta listagem. Informe o Departamento do Marketing </p></div>";
                header("Location: ../../qualidade/angariacoes-15-dias");
            }
        } else {
            $_SESSION['msg_err'] = "<div class='note note-danger bold font-red-intense'><p> Erro fatal! Anote o ID do Imóvel {$id_imovel} junto com as respostas do cliente e informe o Departamento do Marketing </p></div>";
            header("Location: ../../qualidade/questionario-15-dias/{$id_imovel}");
        }
    } else {
        $_SESSION['msg_err'] = '<div class="note note-danger bold font-red-intense"><p> Favor Preencher TODOS os Campos Obrigatórios! </p></div>';
        header("Location: ../../qualidade/questionario-15-dias/{$id_imovel}");
    }
}