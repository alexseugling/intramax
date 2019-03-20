<?php

session_start();
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);

if (isset($submit)) {
    $_SESSION['nome'] = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $_SESSION['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $_SESSION['telefone'] = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);

    if ($_SESSION['nome'] == "" || $_SESSION['email'] == "" || $_SESSION['telefone'] == "") {
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>Todos os campos são necessários.</div>";
        header("Location: ../index.php");
    } else {
        $nome = $_SESSION['nome'];
        $email = $_SESSION['email'];
        $telefone = $_SESSION['telefone'];
        $_SESSION['msg'] = "<div class='alert alert-success' role='alert'>Obrigado pelo seu contacto.</div>";
        unset($_SESSION['nome']);
        unset($_SESSION['email']);
        unset($_SESSION['telefone']);
        //echo "$nome, $email e $telefone";

        include_once "./phpmailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.remaxvantagem.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'pedido-informacoes@remaxvantagem.com';                 // SMTP username
        $mail->Password = 'ars902104';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('pedido-informacoes@remaxvantagem.com', 'Pedido de Informações');
        $mail->addAddress("$mailto", "$user_nome");     // Add a recipient

        $mail->addReplyTo('intramax@remaxvantagem.pt', 'Intranet Remax Vantagem');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('alexseugling@outlook.com');

        $mail->isHTML(true);

        $mail->Subject = 'Pedido de Informação - Deixar casa com Grupo Remax Vantagem';
        $mail->Body = "<p>Nome: $nome</p>";
        $mail->Body .= "<p>Email: $email</p>";
        $mail->Body .= "<p>Telefone: $telefone</p>";
        $mail->Body .= "<br><br>";

        if (!$mail->send()) {
            echo 'Erro ao Enviar Email';
            echo 'Código do Erro: ' . $mail->ErrorInfo;
        } else {
            $sql_user_pass = "SELECT * FROM `user_pass` WHERE user = $user_id";

            $result_user_pass = $conn->query($sql_user_pass);

            if ($result_user_pass->num_rows > 0) {
                $sql_new_pass = "UPDATE user_pass SET pass = '$senha_enc' WHERE user = $user_id";
            } else {
                $sql_new_pass = "INSERT INTO user_pass (id, user, pass) VALUES (NULL, '$user_id', '$senha_enc')";
            }

            $result_new_pass = $conn->query($sql_new_pass);

            if (mysqli_affected_rows($conn) > 0) {
                $_SESSION['msn_login'] = "<div class='alert alert-success'><button class='close' data-close='alert'></button><span> Email de Recuperação Enviado.</span></div>";
                header("Location: ../../login");
            }
        }

        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}