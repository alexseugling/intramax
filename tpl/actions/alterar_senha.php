<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
include_once("conexao.php");

$senha_atual = filter_input(INPUT_POST, 'senha_atual', FILTER_SANITIZE_STRING);
$nova_senha = filter_input(INPUT_POST, 'nova_senha', FILTER_SANITIZE_STRING);
$conf_nova_senha = filter_input(INPUT_POST, 'conf_nova_senha', FILTER_SANITIZE_STRING);
$user_id = $_SESSION['user_id'];

if ($nova_senha != $conf_nova_senha) {
    $_SESSION['act_info'] = "";
    $_SESSION['act_pass'] = "active";
    $_SESSION['msn_new_pass'] = "<div class='alert alert-danger'><button class='close' data-close='alert'></button><span> Os campos Nova Senha e Confirme A Nova Senha precisam Ser Iguais!</span></div>";
    header("Location: ../../atualizar-meu-perfil");
} else {
    $options = [
        'cost' => 10,
    ];

    $nova_senha_enc = password_hash($nova_senha, PASSWORD_BCRYPT, $options);

    $sql_hash = "SELECT pass hash_banco FROM user_pass WHERE user = $user_id";

    $result_hash = $conn->query($sql_hash);

    if ($result_hash->num_rows > 0) {
        $row_hash = $row = $result_hash->fetch_assoc();
        $hash_banco = $row_hash['hash_banco'];

        if (password_verify($senha_atual, $hash_banco)) {
            $sql_upd_pass = "UPDATE user_pass SET pass = '$nova_senha_enc' WHERE user = $user_id";

            if ($conn->query($sql_upd_pass) === TRUE) {
                $_SESSION['act_info'] = "";
                $_SESSION['act_pass'] = "active";
                $_SESSION['msn_new_pass'] = "<div class='alert alert-success'><button class='close' data-close='alert'></button><span> Parabéns! Senha Atualizada Com Sucesso.</span></div>";
                header("Location: ../../atualizar-meu-perfil");
            } else {
                $_SESSION['act_info'] = "";
                $_SESSION['act_pass'] = "active";
                $_SESSION['msn_new_pass'] = "<div class='alert alert-danger'><button class='close' data-close='alert'></button><span> IMPOSSÍVEL ATUALIZAR, CONTATE O SUPORTE!</span></div>";
                header("Location: ../../atualizar-meu-perfil");
            }
        } else {
            $_SESSION['act_info'] = "";
            $_SESSION['act_pass'] = "active";
            $_SESSION['msn_new_pass'] = "<div class='alert alert-danger'><button class='close' data-close='alert'></button><span> Senha Atual Incorreta!</span></div>";
            header("Location: ../../atualizar-meu-perfil");
        }
    } else {
        $_SESSION['msg_suporte'] = "<div class='alert alert-danger'><button class='close' data-close='alert'></button><span> Existe um Erro Fatal! Informe Suporte.</span></div>";
        header("Location: ../../suporte");
    }
    $conn->close();
}

