<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se não houver uma sessão de cliente_id, redireciona para o login
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}
?>