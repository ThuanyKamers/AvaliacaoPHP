<?php
require 'auth_check.php';
require 'config.php';

$carrinho = isset($_COOKIE['carrinho']) ? json_decode($_COOKIE['carrinho'], true) : [];
$cliente_id = $_SESSION['cliente_id'];

if (empty($carrinho)) {
    header("Location: carrinho.php");
    exit();
}

// Inicia a transação para garantir a integridade dos dados
$pdo->beginTransaction();

try {
    // Calcula o total e busca os preços no BD para segurança
    $ids_produtos = array_keys($carrinho);
    $placeholders = implode(',', array_fill(0, count($ids_produtos), '?'));
    
    $stmt = $pdo->prepare("SELECT id, preco FROM produtos WHERE id IN ($placeholders)");
    $stmt->execute($ids_produtos);
    $produtos_db = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Retorna [id => preco]

    $total_compra = 0;
    foreach ($carrinho as $produto_id => $quantidade) {
        // CORREÇÃO AQUI: Acessamos o preço diretamente, sem o ['preco'] extra.
        $total_compra += $produtos_db[$produto_id] * $quantidade;
    }

    // 1. Insere na tabela 'pedidos'
    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, total) VALUES (?, ?)");
    $stmt->execute([$cliente_id, $total_compra]);
    $pedido_id = $pdo->lastInsertId();

    // 2. Insere cada item na tabela 'itens_pedido'
    $stmt_item = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    foreach ($carrinho as $produto_id => $quantidade) {
        // Usamos $produtos_db[$produto_id] que já contém o preço unitário
        $stmt_item->execute([$pedido_id, $produto_id, $quantidade, $produtos_db[$produto_id]]);
    }

    // Confirma a transação
    $pdo->commit();

    // Limpa o cookie do carrinho
    setcookie('carrinho', '', time() - 3600, "/");

    // Redireciona para o resumo do pedido
    header("Location: resumo_pedido.php?pedido_id=" . $pedido_id);
    exit();

} catch (Exception $e) {
    // Desfaz a transação em caso de erro
    $pdo->rollBack();
    die("Erro ao finalizar a compra: " . $e->getMessage());
}
?>