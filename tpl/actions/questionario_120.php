<?php

session_start();

ini_set('display_errors', 0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $q130 = FALSE;
    $id_imovel = $_SESSION['id_imovel'];
    unset($_SESSION['id_imovel']);
    $created_by = $_SESSION['user_id'];
    $angariacao_id = $_SESSION['angariacao_id'];
    unset($_SESSION['angariacao_id']);
    $id_loja = $_SESSION['id_loja'];
    unset($_SESSION['id_loja']);
    $nome_proprietario = $_SESSION['nome_proprietario'];
    unset($_SESSION['nome_proprietario']);
    $telefone_proprietario = $_SESSION['telefone_proprietario'];
    unset($_SESSION['telefone_proprietario']);
    $satisfacao = 1;

    $freq_contato = filter_input(INPUT_POST, 'freq_contato', FILTER_SANITIZE_STRING);
    $quant_contatos = filter_input(INPUT_POST, 'quant_contatos', FILTER_SANITIZE_NUMBER_INT);
    $last_contact = implode("-", array_reverse(explode("/", filter_input(INPUT_POST, 'last_contact', FILTER_SANITIZE_STRING)))) . " " . date("H:i:s");
    $email_last_contact = filter_input(INPUT_POST, 'last_contact', FILTER_SANITIZE_STRING);
    $freq_visitas = filter_input(INPUT_POST, 'freq_visitas', FILTER_SANITIZE_STRING);
    $quant_visitas = filter_input(INPUT_POST, 'quant_visitas', FILTER_SANITIZE_NUMBER_INT);
    $estudo_mercado = filter_input(INPUT_POST, 'estudo_mercado', FILTER_SANITIZE_NUMBER_INT);
    $estrategia_promocao = filter_input(INPUT_POST, 'estrategia_promocao', FILTER_SANITIZE_NUMBER_INT);

    $strat = $_POST["estrategias"];
    $outras_estrategias = filter_input(INPUT_POST, 'outras_estrategias', FILTER_SANITIZE_STRING);

    $classificacao_consultor = filter_input(INPUT_POST, 'classificacao_consultor', FILTER_SANITIZE_NUMBER_INT);
    $recomendacao_marca = filter_input(INPUT_POST, 'recomendacao_marca', FILTER_SANITIZE_NUMBER_INT);
    $obs_cliente = filter_input(INPUT_POST, 'sugestao', FILTER_SANITIZE_STRING);

    //SE OS PRINCIPAIS CAMPOS NÃO FOREM EM BRANCO
    if ($freq_contato != "" && $last_contact != "" && $freq_visitas != "" && $estudo_mercado != "" && $estrategia_promocao != "" && $classificacao_consultor != "" && $recomendacao_marca != "") {
        array_push($strat, ucwords(strtolower($outras_estrategias)));
        $strategies = implode($strat, ", ");

        if ($outras_estrategias == "") {
            $outras_empty = implode($strat, " e ");
            $estrategias = substr($outras_empty, 0, -2);
        } else {
            $estrategias = $strategies;
        }

        if ($freq_contato == "nunca") {
            $quant_contatos = 0;
        }

        if ($freq_visitas == "nunca") {
            $quant_visitas = 0;
        }

        if ($estrategia_promocao == 0) {
            $estrategias = "";
        }

        if ($estudo_mercado == 0 && $estrategia_promocao == 0) {
            $q130 = TRUE;
            $satisfacao = 0;
        }

//        echo "data: NOW<br>";
//        echo "id_imovel: " . $id_imovel . "<br>";
//        echo "created_by: " . $created_by . "<br>";
//        echo "angariacao_id: " . $angariacao_id . "<br>";
//        echo "loja: " . $id_loja . "<br>";
//        echo "satisfacao: calcular<br>";
//        echo "freq_contato: " . $freq_contato . "<br>";
//        echo "quant_contatos: " . $quant_contatos . "<br>";
//        echo "last_contact: " . $last_contact . "<br>";
//        echo "freq_visitas: " . $freq_visitas . "<br>";
//        echo "quant_visitas: " . $quant_visitas . "<br>";
//        echo "estudo_mercado: " . $estudo_mercado . "<br>";
//        echo "estrategia_promocao: " . $estrategia_promocao . "<br>";
//        echo "estrategias: " . $estrategias . "<br>";
//        echo "classificacao_consultor: " . $classificacao_consultor . "<br>";
//        echo "recomendacao_marca: " . $recomendacao_marca . "<br>";
//        echo "obs_cliente: " . $obs_cliente . "<br>";

        include_once("conexao.php");

        $insert_q120 = "
            INSERT INTO qlt_q120 (data, id_imovel, created_by, angariacao_id, loja, freq_contato, quant_contatos, last_contact, freq_visitas, quant_visitas, estudo_mercado, estrategia_promocao, estrategias, classificacao_consultor, recomendacao_marca, satisfacao, obs_cliente) 
            VALUES (NOW(), '{$id_imovel}', {$created_by}, {$angariacao_id}, {$id_loja}, '{$freq_contato}', {$quant_contatos}, '{$last_contact}', '{$freq_visitas}', {$quant_visitas}, {$estudo_mercado}, {$estrategia_promocao}, '{$estrategias}', {$classificacao_consultor}, {$recomendacao_marca}, {$satisfacao}, '{$obs_cliente}');
        ";
        //QUESTIONÁRIO INSERIDO NA BASE DE DADOS qlt_q120
        if ($conn->query($insert_q120) === TRUE) {
            $sql_upd = "UPDATE id_angariacao SET an_120 = 1 WHERE id_imovel = '{$id_imovel}'";
            //ATUALIZA A TABELA id_angariação E TIRA DA LISTAGEM DE 120 DIAS
            if ($conn->query($sql_upd) === TRUE) {
                $_SESSION['msg_out'] = "<div class='note note-success bold font-green-meadow'><p> Parabéns! O questionário de 120 dias relacionado ao Imóvel {$id_imovel} foi finalizado com sucesso. </p></div>";
            } else {
                $_SESSION['msg_out'] = "<div class='note note-danger bold font-red-intense'><p> Atenção! O questionário do Imóvel {$id_imovel} foi salvo ENTRETANTO devido a um Erro, o imóvel ainda aparece nesta listagem. Informe o Departamento do Marketing </p></div>";
                header("Location: ../../qualidade/angariacoes-120-dias");
            }

            //SE ENTRAR NO QUESTIONÁRIO DE 130 DIAS, ENVIAR EMAIL
            if ($q130 === TRUE) {
                $sql_upd2 = "UPDATE id_angariacao SET an_130 = 2 WHERE id_imovel = '{$id_imovel}'";

                if ($conn->query($sql_upd2) === TRUE) {
                    require 'phpmailer/PHPMailerAutoload.php';
                    $mail = new PHPMailer;
                    $mail->CharSet = 'UTF-8';

                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'mail.remaxvantagem.pt';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'eventos@remaxvantagem.pt';                 // SMTP username
                    $mail->Password = 'ars902104';                           // SMTP password
                    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 465;                                    // TCP port to connect to

                    $mail->setFrom('eventos@remaxvantagem.pt', 'Qualidade Remax Vantagem');
                    //$mail->addAddress('carmen.ribeiro@remax.pt', 'Carmen Ribeiro');     // Add a recipient
                    $mail->addAddress('alexseugling@outlook.com', 'Alex Seugling');     // Add a recipient
//                    $mail->addCC('f.augusto@remax.pt', 'Fernando Augusto');
//                    $mail->addCC('jlpereira@remax.pt', 'José Leite Pereira');
//                    $mail->addCC('alexseugling@outlook.com', 'Alex Seugling');

                    $mail->addReplyTo('marketing.vantagem@remax.pt', 'Marketing Remax Vantagem');

                    $mail->isHTML(true);

                    $mail->Subject = 'Inquérito 120 dias - Problemas com a angariação - Falta de contacto do consultor';
                    $mail->Body = "<br><br>";

                    $mail->Body .= "<table style='width: 100%;'>";
                    $mail->Body .= "<tr>";
                    $mail->Body .= "<td><b>- ID do Imóvel: {$id_imovel}</b></td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "<tr>";
                    $mail->Body .= "<td><b>- Nome do Cliente: {$nome_proprietario}</b></td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "<tr>";
                    $mail->Body .= "<td><b>- Telefone do Cliente: {$telefone_proprietario}</b></td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "<tr>";
                    $mail->Body .= "<td><b>- Frequência de Contato: {$freq_contato}, $quant_contatos vezes</b></td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "<tr>";
                    $mail->Body .= "<td><b>- Último Contato do Consultor: {$email_last_contact}</b></td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "<tr>";
                    $mail->Body .= "<td colspan='2'> </td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "<tr>";
                    $mail->Body .= "<td colspan='2'> </td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "<tr>";
                    $mail->Body .= "<td colspan='2'> </td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "<tr bgcolor='#F3F3F3'>";
                    $mail->Body .= "<td colspan='2'><font color='#D70810'><b>* Este email é automático.</b></font></td>";
                    $mail->Body .= "</tr>";
                    $mail->Body .= "</table>";
                    $mail->Body .= "<br><br>";
                    $mail->Body .= "<br><br>";

                    if (!$mail->send()) {
                        $_SESSION['msg_out'] = "<div class='note note-danger bold font-red-intense'><p> Informe o Departamento de Maketing que o Email não foi enviado para os diretores. Relacionado ao Imóvel {$id_imovel} </p></div>";
                        header("Location: ../../qualidade/angariacoes-120-dias");
                    } else {
                        $_SESSION['msg_out'] = "<div class='note note-success bold font-green-meadow'><p> Parabéns! O questionário de 120 dias relacionado ao Imóvel {$id_imovel} foi finalizado com sucesso. </p></div>";
                        header("Location: ../../qualidade/angariacoes-120-dias");
                    }
                } else {
                    $_SESSION['msg_out'] = "<div class='note note-danger bold font-red-intense'><p> Atenção! O questionário do Imóvel {$id_imovel} foi salvo ENTRETANTO devido a um Erro, Não vai aparecer na listagem de 130 dias. Informe o Departamento do Marketing </p></div>";
                    header("Location: ../../qualidade/angariacoes-120-dias");
                }
            } else {
                header("Location: ../../qualidade/angariacoes-120-dias");
            }
        } else {
            $_SESSION['msg_err'] = "<div class='note note-danger bold font-red-intense'><p> Erro fatal! Anote o ID do Imóvel {$id_imovel} junto com as respostas do cliente e informe o Departamento do Marketing </p></div>";
            header("Location: ../../qualidade/questionario-120-dias/{$id_imovel}");
        }
    } else {
        $_SESSION['msg_err'] = '<div class="note note-danger bold font-red-intense"><p> Favor Preencher TODOS os Campos Obrigatórios! </p></div>';
        header("Location: ../../qualidade/questionario-120-dias/{$id_imovel}");
    }
}