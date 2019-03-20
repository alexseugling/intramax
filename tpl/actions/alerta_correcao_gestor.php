<?php

session_start();

$nome_consultor = filter_input(INPUT_POST, 'nome_consultor', FILTER_SANITIZE_STRING);
$nome_gestor = filter_input(INPUT_POST, 'nome_gestor', FILTER_SANITIZE_STRING);
$nome_utilizador = $_SESSION['user_nome'];
$email_utilizador = $_SESSION['user_email'];
$nome_loja = $_SESSION['user_nome_loja'];

if ($nome_utilizador != '' && $nome_loja != '') {

    if ($nome_consultor != '' && $nome_gestor != '') {

        include_once "./phpmailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.remaxvantagem.pt';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'intramax@remaxvantagem.pt';                 // SMTP username
        $mail->Password = 'ars902104';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom("$email_utilizador", "$nome_utilizador");
        $mail->addAddress("rh.grupovantagem.vieira@remax.pt.com", "Inês Vieira");     // Add a recipient

        $mail->addReplyTo("$email_utilizador", "$nome_utilizador");
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('alexseugling@outlook.com');

        $mail->isHTML(true);

        $mail->Subject = "[ALERTA] Correção Gestor / Consultor";
        $mail->Body = "<h3>[ALERTA] Correção Gestor / Consultor</h3>";
        $mail->Body .= "<p>------------------------------------------------------------------------------------------------------------------</p>";
        $mail->Body .= "<p>Olá Inês,</p>";
        $mail->Body .= "<p>$nome_utilizador da loja $nome_loja</p>";
        $mail->Body .= "<p>Informou que o responsável pelo(a) Consultor(a) $nome_consultor é o(a) Gestor(a) $nome_gestor</p>";
        $mail->Body .= "<p>Pode atualizar esta informação na sua área de RH.</p>";
        $mail->Body .= "<p>Se possível, informe $nome_utilizador após efetuar a correção solicitada.</p>";
        $mail->Body .= "<p>Obrigado.</p>";
        $mail->Body .= "<p>------------------------------------------------------------------------------------------------------------------</p>";
        $mail->Body .= "<br><br>";

        if (!$mail->send()) {
            echo 'Erro ao Enviar Email';
            echo 'Código do Erro: ' . $mail->ErrorInfo;
        } else {
            $_SESSION['msg_alerta_correcao_gestor'] = "<div class='note note-success'><h4 class='block bold font-green-seagreen'>Alerta Enviado com Sucesso!</h4><p>O responsável vai atualizar o registo deste consultor em nossa base de dados assim que possível.</p></div>";
            header("Location: ../../coordenacao/alerta-correcao-gestor");
        }
    } else {
        $_SESSION['msg_alerta_correcao_gestor'] = "<div class='note note-danger'><h4 class='block bold font-red-thunderbird'>Atenção ao Preenchimento!</h4><p>Selecione o Nome do Consultor e o Nome do Gestor responsável pelo mesmo.</p></div>";
        header("Location: ../../coordenacao/alerta-correcao-gestor");
    }
} else {
    $_SESSION['msg_alerta_correcao_gestor'] = "<div class='note note-danger'><h4 class='block bold font-red-thunderbird'>Existe um erro no sistema!</h4><p>Por favor informe o programador ou o RH.</p></div>";
    header("Location: ../../coordenacao/alerta-correcao-gestor");
}

