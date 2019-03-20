<?php

session_start();
$id_imovel = $_SESSION['id_imovel'];
$field = $_SESSION['field'];
$trying_limit = "";

if (!filter_has_var(INPUT_POST, 'btn_obs') || $_SESSION['field'] === "") {
    $_SESSION['msg_obs'] = '<div class="note note-danger bold font-red-intense"><p> NÃO VISITE AS URLS DIRETAMENTE, UTILIZE NAVEGAÇÃO MANUAL. </p></div>';
    header("Location: ../../qualidade/observacoes/{$id_imovel}/{$field}");
} else {
    $obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_STRING);
    if ($obs != "") {
        $angariacao_id = $_SESSION['angariacao_id'];
        $created_by = $_SESSION['user_id'];
        if ($angariacao_id != "") {
            include_once("conexao.php");
            $sql_auth = "
                SELECT NOW() agora, DATE_ADD(qlt_obs.data, INTERVAL +24 HOUR) permit FROM qlt_obs WHERE qlt_obs.angariacao_id = $angariacao_id ORDER BY qlt_obs.id DESC LIMIT 1
                ";
            $result_auth = $conn->query($sql_auth);
            $row_auth = $result_auth->fetch_assoc();
            $agora = $row_auth["agora"];
            $permit = $row_auth["permit"];

            //echo "<br>Agora: $agora e Permitido: $permit<br>";

            if ($agora >= $permit) {
                //echo '<br><br><br>Já passou o tempo';
                $sql_qnt = "SELECT qlt_obs.id FROM qlt_obs WHERE qlt_obs.angariacao_id = $angariacao_id AND qlt_obs.field = $field";
                $result_qnt = $conn->query($sql_qnt);
                if ($result_qnt->num_rows < 2) {
                    $sql_insert = "INSERT INTO qlt_obs (angariacao_id, created_by, data, comentario, field) VALUES ('$angariacao_id', '$created_by', NOW(), '$obs', '$field')";
                    if ($conn->query($sql_insert) === TRUE) {
                        $_SESSION['msg_obs'] = '<div class="note note-success bold font-green-meadow"><p> COMENTÁRIO INSERIDO COM SUCESSO. </p></div>';
                        unset($_SESSION['field']);
                        unset($_SESSION['angariacao_id']);
                        header("Location: ../../qualidade/observacoes/{$id_imovel}/{$field}");
                    } else {
                        $_SESSION['msg_obs'] = '<div class="note note-danger bold font-red-intense"><p> ERRO 981: Informe o Departamento de Marketing. </p></div>';
                        header("Location: ../../qualidade/observacoes/{$id_imovel}/{$field}");
                    }
                } else {
                    $sql_insert = "INSERT INTO qlt_obs (angariacao_id, created_by, data, comentario, field) VALUES ('$angariacao_id', '$created_by', NOW(), '$obs', '$field')";

                    switch ($field) {
                        case "15":
                            $trying_limit = "angariacoes-15-dias";
                            break;
                        case "60":
                            $trying_limit = "angariacoes-60-dias";
                            break;
                        case "120":
                            $trying_limit = "angariacoes-120-dias";
                            break;
                        case "130":
                            $trying_limit = "angariacoes-130-dias";
                            break;
                    }

                    if ($conn->query($sql_insert) === TRUE) {
                        //Aqui já foram feitas 3 tentativas de contacto com o cliente. (STATUS 2 na Tabela ID_ANGARIACAO)
                        $sql_out = "UPDATE id_angariacao SET an_{$field} = '2' WHERE id_angariacao.id_imovel = '$id_imovel'";
                        if ($conn->query($sql_out) === TRUE) {
                            $_SESSION['msg_out'] = "<div class='note note-danger bold font-red-thunderbird'><p> O SEU COMENTÁRIO FOI INSERIDO. O IMÓVEL (ID: {$id_imovel}) SAIU DA LISTAGEM POIS ATINGIU O MÁXIMO DE TENTATIVAS DE CONTATO SEM SUCESSO.</p></div>";
                            unset($_SESSION['field']);
                            unset($_SESSION['angariacao_id']);
                            header("Location: ../../qualidade/{$trying_limit}");
                        } else {
                            $_SESSION['msg_obs'] = '<div class="note note-danger bold font-red-intense"><p> ERRO 1524: Informe o Departamento de Marketing. </p></div>';
                            header("Location: ../../qualidade/observacoes/{$id_imovel}");
                        }
                    } else {
                        $_SESSION['msg_obs'] = '<div class="note note-danger bold font-red-intense"><p> ERRO 777: Informe o Departamento de Marketing. </p></div>';
                        header("Location: ../../qualidade/observacoes/{$id_imovel}");
                    }
                }
            } else {
                $_SESSION['msg_obs'] = "<div class='note note-danger bold font-red-intense'><p> AGUARDE 24H PARA FAZER UM NOVO COMENTÁRIO. (CASO COMETEU UM ERRO INFORME AO DEPARTAMENTO DE MARKETING!) </p></div>";
                header("Location: ../../qualidade/observacoes/{$id_imovel}/{$field}");
            }
        } else {
            $_SESSION['msg_obs'] = '<div class="note note-danger bold font-red-intense"><p> ERRO 2302: Informe o Departamento de Marketing. </p></div>';
            header("Location: ../../qualidade/observacoes/{$id_imovel}/{$field}");
        }
    } else {
        $_SESSION['msg_obs'] = '<div class="note note-danger bold font-red-intense"><p> O CAMPO DE "COMENTÁRIO" NÃO PODE FICAR VAZIO. </p></div>';
        header("Location: ../../qualidade/observacoes/{$id_imovel}/{$field}");
    }
}