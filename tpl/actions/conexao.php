<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$dbname = "remaxvan_intramax";

//Criar a conexao
$conn = new mysqli($servidor, $usuario, $senha, $dbname);
mysqli_set_charset($conn, "utf8");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 