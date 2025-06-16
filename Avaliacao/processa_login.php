<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicializa as tentativas de login se não existirem
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }
    
    // Bloqueia se já excedeu as tentativas
    if ($_SESSION['login_attempts'] >= 3) {
        $_SESSION['login_error'] = "Número de tentativas excedido.";
        header("Location: login.php");
        exit();
    }

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT id, nome, senha FROM clientes WHERE email = ?");
    $stmt->execute([$email]);
    $cliente = $stmt->fetch();

    // Verifica se o cliente existe e a senha está correta
    if ($cliente && password_verify($senha, $cliente['senha'])) {
        // Login bem-sucedido
        unset($_SESSION['login_attempts']); // Limpa as tentativas
        $_SESSION['cliente_id'] = $cliente['id'];
        $_SESSION['cliente_nome'] = $cliente['nome'];
        header("Location: index.php"); // Redireciona para a página inicial
        exit();
    } else {
        // Login falhou
        $_SESSION['login_attempts']++;
        $_SESSION['login_error'] = "E-mail ou senha inválidos.";
        header("Location: login.php");
        exit();
    }
}
?>