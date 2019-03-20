<?php

session_start();
if (isset($_SESSION['id_consultor'])) {
    unset($_SESSION['id_consultor']);
    header("Location: ../../controlo_produto/consultor");
} else {
    header("Location: ../../controlo_produto/consultor");
}