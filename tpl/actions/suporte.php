<?php

session_start();

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_NUMBER_INT);
$email = strtolower(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$assunto = filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_STRING);
$mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);

if ($nome != '' && $telefone != '' && $email != '' && $assunto != '' && $mensagem != '') {
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

    $mail->setFrom("$email", "$nome");
    $mail->addAddress("alexseugling@outlook.com", "Alex Seugling");     // Add a recipient

    $mail->addReplyTo("$email", "$nome");
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('alexseugling@outlook.com');

    $mail->isHTML(true);

    $mail->Subject = "Suporte Intramax - $assunto";
    $mail->Body = "<h3>$assunto</h3>";
    $mail->Body .= "<p>------------------------------------------------------------------------------------------------------------------</p>";
    $mail->Body .= "<p>Mensagem: $mensagem</p>";
    $mail->Body .= "<p>------------------------------------------------------------------------------------------------------------------</p>";
    $mail->Body .= "<br><br>";

    if (!$mail->send()) {
        echo 'Erro ao Enviar Email';
        echo 'Código do Erro: ' . $mail->ErrorInfo;
    } else {
        $_SESSION['msg_suporte'] = "<div class='note note-success'><h4 class='block bold font-green-seagreen'>Parabéns! A Sua Mensagem Foi Enviada.</h4><p>Responderemos em até 24 Horas.</p></div>";
        header("Location: ../../suporte");
    }
} else {
    $_SESSION['msg_suporte'] = "<div class='note note-danger'><h4 class='block bold font-red-thunderbird'>Atenção ao preenchimento do formulário!</h4><p>Todos os campos são obrigatórios para enviar a sua mensagem.</p></div>";
    header("Location: ../../suporte");
}