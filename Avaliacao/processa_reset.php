<?php
// ✅ 1. INICIAMOS A SESSÃO PARA PODER MODIFICÁ-LA
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nova_senha = $_POST['nova_senha'];

    // Valida o token novamente por segurança
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE reset_token = ? AND reset_token_expires_at > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Criptografa a nova senha
        $hashed_password = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        // Atualiza a senha e limpa o token para que não possa ser usado novamente
        $stmt = $pdo->prepare("UPDATE clientes SET senha = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);
        
        // ✅ 2. LIMPAMOS O CONTADOR DE TENTATIVAS DA SESSÃO
        // Esta linha remove o bloqueio da sessão do usuário.
        unset($_SESSION['login_attempts']);
        
        // Redireciona para o login com uma mensagem de sucesso
        header("Location: login.php?status=reset_success");
        exit();
    } else {
        die("Token inválido ou expirado. A senha não pode ser redefinida.");
    }
}
?>