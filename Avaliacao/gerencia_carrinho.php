<?php
session_start();

// Pega o carrinho atual do cookie ou cria um array vazio
$carrinho = isset($_COOKIE['carrinho']) ? json_decode($_COOKIE['carrinho'], true) : [];

$action = $_POST['action'] ?? '';
$produto_id = $_POST['produto_id'] ?? 0;

if ($action === 'add' && $produto_id > 0) {
    // Adiciona ou incrementa a quantidade do produto
    if (isset($carrinho[$produto_id])) {
        $carrinho[$produto_id]++;
    } else {
        $carrinho[$produto_id] = 1;
    }
}

if ($action === 'remove' && isset($carrinho[$produto_id])) {
    // Remove o produto do carrinho
    unset($carrinho[$produto_id]);
}

if ($action === 'clear') {
    // Limpa todo o carrinho
    $carrinho = [];
}

// Salva o carrinho de volta no cookie (válido por 30 dias)
setcookie('carrinho', json_encode($carrinho), time() + (86400 * 30), "/");

// Redireciona de volta para a página do carrinho ou de onde veio
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit();
?>