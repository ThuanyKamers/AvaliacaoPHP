<?php
// Requer autenticação; se o usuário não estiver logado, será redirecionado.
require 'auth_check.php';
require 'config.php';

$page_title = "Meu Carrinho";
include 'header.php';

// Lê o carrinho do cookie ou cria um array vazio se não existir.
$carrinho = isset($_COOKIE['carrinho']) ? json_decode($_COOKIE['carrinho'], true) : [];
$itens_carrinho = [];
$total_compra = 0;

if (!empty($carrinho)) {
    // Pega as chaves (IDs dos produtos) do carrinho para usar na query SQL.
    $ids_produtos = array_keys($carrinho);
    
    // Cria os placeholders (?) para a cláusula IN da query, para segurança.
    $placeholders = implode(',', array_fill(0, count($ids_produtos), '?'));
    
    // Query para buscar as informações dos produtos que estão no carrinho.
    $stmt = $pdo->prepare("SELECT id, nome, preco FROM produtos WHERE id IN ($placeholders)");
    $stmt->execute($ids_produtos);
    $produtos_db = $stmt->fetchAll();

    // Organiza os itens do carrinho com os dados do banco e calcula os totais.
    foreach ($produtos_db as $produto) {
        $quantidade = $carrinho[$produto['id']];
        $subtotal = $quantidade * $produto['preco'];
        $itens_carrinho[] = [
            'id' => $produto['id'],
            'nome' => $produto['nome'],
            'preco' => $produto['preco'],
            'quantidade' => $quantidade,
            'subtotal' => $subtotal
        ];
        $total_compra += $subtotal;
    }
}
?>

<div class="content-box">
    <h2>Meu Carrinho de Compras</h2>

    <?php if (empty($itens_carrinho)): ?>
        <p style="text-align: center; margin-top: 20px;">Seu carrinho está vazio.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" class="btn">Ver Produtos</a>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens_carrinho as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nome']); ?></td>
                            <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo $item['quantidade']; ?></td>
                            <td>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                            <td>
                                <form action="gerencia_carrinho.php" method="post">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="produto_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn-remove">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <div class="cart-actions">
                 <form action="gerencia_carrinho.php" method="post">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="btn btn-secondary">Limpar Carrinho</button>
                </form>
            </div>
            <div class="cart-total">
                <h3>Total: <span>R$ <?php echo number_format($total_compra, 2, ',', '.'); ?></span></h3>
                <form action="finalizar_compra.php" method="post">
                    <button type="submit" class="btn">Finalizar Compra</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

</main> </body>
</html>