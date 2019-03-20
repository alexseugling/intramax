<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
include_once("conexao.php");

$recovery_email = strtolower(filter_input(INPUT_POST, 'recovery_email', FILTER_SANITIZE_EMAIL));
$pure_email = str_replace('@remax.pt', '', $recovery_email);

$senha_gerada = geraSenha(20, true, true, true);

function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';
    $caracteres .= $lmin;
    if ($maiusculas)
        $caracteres .= $lmai;
    if ($numeros)
        $caracteres .= $num;
    if ($simbolos)
        $caracteres .= $simb;
    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}

$options = [
    'cost' => 10,
];

$senha_enc = password_hash($senha_gerada, PASSWORD_BCRYPT, $options);

$sql_recovery = "SELECT colaboradores.id user_id, colaboradores.nome user_nome, contatos.tipo_contato user_tipo_contato FROM colaboradores
INNER JOIN contatos ON contatos.user = colaboradores.id
WHERE contatos.info = '$pure_email' AND colaboradores.status = 1";

$result_recovery = $conn->query($sql_recovery);

if ($result_recovery->num_rows > 0) {
    $row_recovery = mysqli_fetch_assoc($result_recovery);
    $user_id = $row_recovery['user_id'];
    $user_nome = $row_recovery['user_nome'];
    $user_primeiro_nome = substr($user_nome, 0, strpos($user_nome, ' '));
    $user_tipo_contato = $row_recovery['user_tipo_contato'];

    if ($user_tipo_contato == 1) {
        $mailto = $pure_email . "@remax.pt";
    } else {
        $mailto = $pure_email;
    }

    include_once "./phpmailer/PHPMailerAutoload.php";

    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.remaxvantagem.pt';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'intramax@remaxvantagem.pt';                 // SMTP username
    $mail->Password = 'Vantagem';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom('intramax@remaxvantagem.pt', 'Intranet Remax Vantagem');
    $mail->addAddress("$mailto", "$user_nome");     // Add a recipient

    $mail->addReplyTo('intramax@remaxvantagem.pt', 'Intranet Remax Vantagem');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('alexseugling@outlook.com');

    $mail->isHTML(true);

    $mail->Subject = 'Recuperação de Senha - Intramax';
    $mail->Body = "<h1>Recuperação de Senha</h1>";
    $mail->Body .= "<p>Olá $user_primeiro_nome, está a receber este email porque alguém solicitou uma recuperação de senha da intramax</p>";
    $mail->Body .= "<p>A Sua Nova Senha é: $senha_gerada</p>";
    $mail->Body .= "<p>Não se esqueça de alterar a sua senha após entrar na sua conta.</p>";
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
} else {
    $_SESSION['msn_login'] = "<div class='alert alert-danger'><button class='close' data-close='alert'></button><span> Email Não Encontrado!. </span></div>";
    header("Location: ../../login");
}
$conn->close();
