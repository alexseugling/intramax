<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_NUMBER_INT);

    if ($search != "") {
        include_once("conexao.php");
        $sql_id = "
            SELECT id_angariacao.id_imovel, qlt_obs.comentario 
            FROM id_angariacao 
            INNER JOIN livro_registo ON livro_registo.angariacao_id = id_angariacao.angariacao_id 
            LEFT JOIN qlt_obs ON qlt_obs.angariacao_id = id_angariacao.angariacao_id 
            WHERE livro_registo.nif_proprietario = '{$search}' OR livro_registo.telefone_proprietario = '{$search}'
        ";
        $result_id = $conn->query($sql_id);

        if ($result_id->num_rows > 0) {
            while ($row_id = $result_id->fetch_assoc()) {
                $id_imovel = $row_id["id_imovel"];
                $comentario = $row_id["comentario"];
                $_SESSION['search_msg'] = "<div class='note note-success bold font-green-meadow'><p>IMÓVEL:{$id_imovel} LOCALIZADO COM SUCESSO. </p></div>";
                header("Location: ../../qualidade/pesquisa-timeline");
            }
        } else {
            $_SESSION['search_msg'] = "<div class='note note-danger bold font-red-intense'><p> NENHUM RESULTADO ENCONTRADO. </p></div>";
            header("Location: ../../qualidade/pesquisa-timeline");
        }
    } else {
        $_SESSION['search_msg'] = "<div class='note note-danger bold font-red-intense'><p> POR FAVOR DIGITE O MESMO NIF OU TELEFONE ASSOCIADO AO IMÓVEL DO CLIENTE. </p></div>";
        header("Location: ../../qualidade/pesquisa-timeline");
    }
}