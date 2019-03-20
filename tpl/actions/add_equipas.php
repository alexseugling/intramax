<?php

session_start();

$consultor = filter_input(INPUT_POST, 'consultor', FILTER_SANITIZE_NUMBER_INT);
$responsavel_angariacao = filter_input(INPUT_POST, 'responsavel_angariacao', FILTER_SANITIZE_NUMBER_INT);
$enquadramento = filter_input(INPUT_POST, 'enquadramento', FILTER_SANITIZE_NUMBER_INT);
$contabilizacao_consultores = filter_input(INPUT_POST, 'contabilizacao_consultores', FILTER_SANITIZE_NUMBER_INT);
$nome_equipa = filter_input(INPUT_POST, 'nome_equipa', FILTER_SANITIZE_STRING);

//echo "Consultor = $consultor <br>";
//echo "Chefe de Equipa = $responsavel_angariacao <br>";
//echo "Enquadramento = $enquadramento <br>";
//echo "Contabilização dos Consultores = $contabilizacao_consultores <br>";
//echo "Nome da Equipa = $nome_equipa <br>";

if ($consultor == "" || $responsavel_angariacao == "" || $enquadramento == "" || $nome_equipa == "" || $contabilizacao_consultores == "" || $contabilizacao_consultores < 1) {
    $_SESSION['msg_adicionar_equipas'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Atenção ao Prenchimento!</h4><p>Preencha todos os campos obrigatórios corretamente, o campo Contabilização dos Consultores deve ser Maior que Zero.</p></div>";
    header("Location: ../../adicionar-equipas");
} else {
    include_once("conexao.php");
    $insert = "INSERT INTO equipas (data, consultor, responsavel_angariacao, enquadramento, contabilizacao_consultores, nome_equipa) VALUES (NOW(), $consultor, $responsavel_angariacao, $enquadramento, $contabilizacao_consultores, '$nome_equipa')";

    if (mysqli_query($conn, $insert)) {
        $_SESSION['msg_adicionar_equipas'] = "<div class='note note-success font-green-haze'><h4 class='block bold'>✔ Consultor Adicionado com Sucesso!</h4><p>Parabéns por ter Formado uma Nova Equipa.</p></div>";
        header("Location: ../../adicionar-equipas");
    } else {
        $_SESSION['msg_adicionar_equipas'] = "<div class='note note-danger font-red-thunderbird'><h4 class='block bold'>⚠ Impossível Adicionar Consultor!</h4><p>Este Consultor já pertence a Outra Equipa. informe o Marketing.</p></div>";
        header("Location: ../../adicionar-equipas");
    }

    mysqli_close($conn);
}