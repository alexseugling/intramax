<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("conexao.php");
    $user_id_loja = $_SESSION['user_id_loja'];
    $enviar = filter_input(INPUT_POST, 'enviar', FILTER_SANITIZE_STRING);
    switch ($enviar) {
        case "Pesquisar Por ID do Imóvel":
            $id_imovel = filter_input(INPUT_POST, 'id_imovel', FILTER_SANITIZE_STRING);
            if (!empty($id_imovel)) {
                $sel_id_imovel = "
                    SELECT id_angariacao.loja 
                    FROM id_angariacao 
                    INNER JOIN livro_registo ON livro_registo.angariacao_id = id_angariacao.angariacao_id 
                    WHERE livro_registo.id_inicial_imovel = '$id_imovel' OR id_angariacao.id_imovel = '$id_imovel'
                ";
                $result_id_imovel = mysqli_query($conn, $sel_id_imovel);
                if (mysqli_num_rows($result_id_imovel) > 0) {
                    while ($row_id_imovel = mysqli_fetch_assoc($result_id_imovel)) {
                        $loja = $row_id_imovel["loja"];
                    }
                    if ($user_id_loja != $loja) {
                        $_SESSION['msg_id_imovel'] = "<div class='alert alert-danger'><strong>Acesso Bloqueado!</strong> Não é permitido visualizar imóveis de outras lojas </div>";
                        header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel");
                    } else {
                        $_SESSION['show_form'] = "sess_show_form";
                        $_SESSION['pesq_ang_id_imv'] = "sess_pesq_ang_id_imv";
                        header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel/$id_imovel");
                    }
                } else {
                    $_SESSION['msg_id_imovel'] = "<div class='alert alert-danger'><strong>ID Inexistente!</strong> O ID de Imóvel que está procurando não existe, por favor verifique. </div>";
                    header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel");
                }
            } else {
                $_SESSION['msg_id_imovel'] = "<div class='alert alert-danger'><strong>Campo Obrigatório!</strong> Digite o ID do Imóvel. </div>";
                header("Location: ../../coordenacao/pesquisar-angariacao/id-imovel");
            }
            break;

        case "Pesquisar Por Consultor":
            $id_consultor = filter_input(INPUT_POST, 'id_consultor', FILTER_SANITIZE_NUMBER_INT);
            if (!empty($id_consultor)) {
                $_SESSION['show_form'] = "sess_show_form";
                $_SESSION['pesq_ang_consultor'] = "sess_pesq_consultor";
                $_SESSION['pesq_ang_id_imv'] = "sess_pesq_ang_id_imv";
                $_SESSION['consultor_pesquisado'] = $id_consultor;
                header("Location: ../../coordenacao/pesquisar-angariacao/consultor");
            } else {
                $_SESSION['msg_consultor'] = "<div class='alert alert-danger'><strong>Campo Obrigatório!</strong> Selecione o Consultor </div>";
                header("Location: ../../coordenacao/pesquisar-angariacao/consultor");
            }
            break;
    }
} else {
    header("Location: ../../coordenacao/pesquisar-angariacao");
}