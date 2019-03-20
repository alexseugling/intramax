<?php

session_start();
include_once("conexao.php");

$user_email = strtolower(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$email = str_replace('@remax.pt', '', $user_email);

$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);

$sql_login = "SELECT colaboradores.id user_id, colaboradores.nome user_nome, 
user_loja.loja user_id_loja, 
info_loja.nome user_nome_loja, 
cargo.tipo_cargo user_id_cargo, 
tipo_cargo.nome user_nome_cargo, 
documentos.num_doc user_nif, 
MAIL.info user_email, 
PHONE.info user_fone, 
gestor.gestor user_id_gestor, 
GST.nome user_nome_gestor, 
info_loja.cod user_store_code,
user_pass.pass user_pass
FROM colaboradores
INNER JOIN user_loja ON user_loja.user = colaboradores.id
INNER JOIN info_loja ON info_loja.id = user_loja.loja
INNER JOIN cargo ON cargo.user = colaboradores.id
INNER JOIN tipo_cargo ON tipo_cargo.id = cargo.tipo_cargo
INNER JOIN documentos ON documentos.user = colaboradores.id
INNER JOIN contatos MAIL ON MAIL.user = colaboradores.id AND MAIL.tipo_contato in (1, 9)
LEFT JOIN contatos PHONE ON PHONE.user = colaboradores.id AND PHONE.tipo_contato = 2
LEFT JOIN gestor ON gestor.user = colaboradores.id
LEFT JOIN colaboradores GST ON GST.id = gestor.gestor
LEFT JOIN user_pass ON user_pass.user = colaboradores.id
WHERE colaboradores.status = 1 AND MAIL.info = '$email'
ORDER BY gestor.data DESC LIMIT 1";

$result_login = $conn->query($sql_login);

if ($result_login->num_rows > 0) {
    $row_login = mysqli_fetch_assoc($result_login);
    $senha = $row_login['user_pass'];

    if (password_verify($pass, $senha)) {
        $_SESSION['user_email'] = $user_email;
        $_SESSION['user_id'] = $row_login['user_id'];
        $_SESSION['user_nome'] = $row_login['user_nome'];
        $_SESSION['user_id_loja'] = $row_login['user_id_loja'];
        $_SESSION['user_nome_loja'] = $row_login['user_nome_loja'];
        $_SESSION['user_id_cargo'] = $row_login['user_id_cargo'];
        $_SESSION['user_nome_cargo'] = $row_login['user_nome_cargo'];
        $_SESSION['user_nif'] = $row_login['user_nif'];
        $_SESSION['user_email'] = $row_login['user_email'];
        $_SESSION['user_fone'] = $row_login['user_fone'];
        $_SESSION['user_id_gestor'] = $row_login['user_id_gestor'];
        $_SESSION['user_nome_gestor'] = $row_login['user_nome_gestor'];
        $_SESSION['user_store_code'] = $row_login['user_store_code'];
        $_SESSION['user_primeiro_nome'] = substr($row_login['user_nome'], 0, strpos($row_login['user_nome'], ' '));
        $_SESSION['login'] = "session_login";
        //A SESSION ABAIXO É NECESSÁRIO PARA A PÁGINA ATUALIZAR O MEU PERFIL
        $_SESSION['act_info'] = "active";
        header("Location: ../../home");
    } else {
        $_SESSION['msn_login'] = "<div class='alert alert-danger'><button class='close' data-close='alert'></button><span> Senha Incorreta. </span></div>";
        header("Location: ../../login");
    }
} else {
    $_SESSION['msn_login'] = "<div class='alert alert-danger'><button class='close' data-close='alert'></button><span> Este Utilizador não Existe. </span></div>";
    header("Location: ../../login");
}
$conn->close();

