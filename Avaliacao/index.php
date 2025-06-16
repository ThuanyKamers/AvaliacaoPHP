<?php
// 1. INCLUI OS ARQUIVOS NECESSÁRIOS
// config.php para a conexão com o banco de dados ($pdo)
require 'config.php';
// header.php para o cabeçalho da página e o menu de navegação
$page_title = "Página Inicial";
include 'header.php';

// 2. BUSCA OS PRODUTOS NO BANCO DE DADOS (AGORA INCLUINDO A IMAGEM)
try {
    // ✅ MUDANÇA: Adicionamos 'imagem_url' à consulta SELECT
    $stmt = $pdo->prepare("SELECT id, nome, preco, imagem_url FROM produtos ORDER BY nome ASC");
    $stmt->execute();
    // Armazena os resultados em um array
    $produtos = $stmt->fetchAll();
} catch (PDOException $e) {
    // Em caso de erro, exibe uma mensagem
    echo "<p class='form-message error'>Erro ao buscar produtos: " . $e->getMessage() . "</p>";
    $produtos = []; // Garante que a variável exista como um array vazio
}
?>

<div class="content-box">
    <h2>Produtos</h2>
</div>

<div class="product-grid">
    <?php if (empty($produtos)): ?>
        <p>Nenhum produto encontrado no momento.</p>
    <?php else: ?>
        <?php foreach ($produtos as $produto): ?>
            <div class="product-card">
                <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="Foto do produto <?php echo htmlspecialchars($produto['nome']); ?>">
                </div>
                
                <p class="price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                
                <form action="gerencia_carrinho.php" method="post">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                    <button type="submit" class="btn">Adicionar ao Carrinho</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php

?>
</main> </body>
</html>