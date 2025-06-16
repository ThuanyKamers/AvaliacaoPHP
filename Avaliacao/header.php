<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Sistema de Compras'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">VERSE</a>
        <nav>
            <a href="index.php">Produtos</a>
            <a href="carrinho.php">Carrinho</a>
            <?php if (isset($_SESSION['cliente_id'])): ?>
                <a href="relatorio_pedidos.php">Meus Pedidos</a>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="cadastro.php">Cadastro</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="container">