<?php
// Requer autenticação e outros arquivos de base
require 'auth_check.php';
require 'config.php';

// Pega o ID do pedido da URL (ex: resumo_pedido.php?pedido_id=5)
$pedido_id = $_GET['pedido_id'] ?? 0;

if (!$pedido_id) {
    // Se não houver ID do pedido, redireciona para a página inicial
    header("Location: index.php");
    exit();
}

// Busca os detalhes do pedido e os itens comprados usando JOIN
try {
    $sql = "
        SELECT
            p.id AS produto_id,
            p.nome AS produto_nome,
            ip.quantidade,
            ip.preco_unitario
        FROM itens_pedido ip
        JOIN produtos p ON ip.produto_id = p.id
        WHERE ip.pedido_id = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pedido_id]);
    $itens_do_pedido = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar detalhes do pedido: " . $e->getMessage());
}

$page_title = "Resumo do Pedido #" . $pedido_id;
include 'header.php';
?>

<div class="content-box">
    <h2>Obrigado pela sua compra!</h2>
    <p>Seu pedido nº <strong><?php echo htmlspecialchars($pedido_id); ?></strong> foi realizado com sucesso.</p>
    
    <h3 style="margin-top: 30px;">Detalhes do Pedido:</h3>
    
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_final = 0;
                foreach ($itens_do_pedido as $item): 
                    $subtotal = $item['quantidade'] * $item['preco_unitario'];
                    $total_final += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['produto_nome']); ?></td>
                        <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">Total do Pedido:</td>
                    <td style="font-weight: bold; color: var(--cor-destaque);">R$ <?php echo number_format($total_final, 2, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="index.php" class="btn">Continuar Comprando</a>
    </div>
</div>

</main> </body>
</html>