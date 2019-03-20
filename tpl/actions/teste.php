<?php

session_start();
include_once("conexao.php");

$user_email = strtolower(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$email = str_replace('@remax.pt', '', $user_email);

$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);

$sql_login = "SELECT COL.id user_id, COL.nome user_nome, ULJ.loja user_id_loja, ILJ.nome user_nome_loja, CAR.tipo_cargo user_id_cargo, TCG.nome user_nome_cargo, DOC.num_doc user_nif, MAIL.info user_email, TEL.info user_fone, GID.gestor user_id_gestor, NGT.nome user_nome_gestor, PASS.pass user_pass
FROM colaboradores COL
INNER JOIN user_loja ULJ ON ULJ.user = COL.id
INNER JOIN info_loja ILJ ON ILJ.id = ULJ.loja
INNER JOIN cargo CAR ON CAR.user = COL.id
INNER JOIN tipo_cargo TCG ON TCG.id = CAR.tipo_cargo
INNER JOIN documentos DOC ON DOC.user = COL.id AND DOC.tipo_doc = 2
INNER JOIN contatos MAIL on MAIL.user = COL.id AND MAIL.tipo_contato = 1 OR MAIL.tipo_contato = 9
INNER JOIN user_pass PASS on PASS.user = COL.id
LEFT JOIN contatos TEL on TEL.user = COL.id AND TEL.tipo_contato = 2
LEFT JOIN gestor GID on GID.user = COL.id
LEFT JOIN colaboradores NGT ON NGT.id = GID.gestor
WHERE COL.status = 1 AND COL.id = (SELECT contatos.user FROM contatos WHERE contatos.info = '$email')
ORDER BY GID.data DESC LIMIT 1";

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

