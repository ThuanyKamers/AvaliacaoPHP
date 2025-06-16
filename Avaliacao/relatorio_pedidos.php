<?php
// 1. GARANTE QUE APENAS USUÁRIOS LOGADOS ACESSEM A PÁGINA
// Esta linha é crucial. Ela inicia a sessão e nos dá acesso ao $_SESSION['cliente_id']
require 'auth_check.php'; 
require 'config.php';

// 2. PEGA O ID DO CLIENTE QUE ESTÁ LOGADO
$cliente_logado_id = $_SESSION['cliente_id'];

$page_title = "Meus Pedidos";
include 'header.php';

// 3. MODIFICA A QUERY SQL PARA FILTRAR PELO CLIENTE LOGADO
// Adicionamos a cláusula "WHERE c.id = ?" no final
$sql = "
    SELECT
        c.nome AS cliente_nome,
        c.foto_perfil,
        c.pdf_documento,
        p.id AS pedido_id,
        prod.nome AS produto_nome
    FROM clientes c
    JOIN pedidos p ON c.id = p.cliente_id
    JOIN itens_pedido ip ON p.id = ip.pedido_id
    JOIN produtos prod ON ip.produto_id = prod.id
    WHERE c.id = ? 
    ORDER BY p.id DESC;
";

// 4. EXECUTA A CONSULTA DE FORMA SEGURA COM PREPARED STATEMENTS
$stmt = $pdo->prepare($sql);
$stmt->execute([$cliente_logado_id]);
$itens_comprados = $stmt->fetchAll();

// Pega as informações do cliente (serão as mesmas para todos os registros)
$cliente_info = !empty($itens_comprados) ? $itens_comprados[0] : null;

?>

<div class="content-box">
    <h2>Meus Pedidos</h2>
</div>

<?php if (empty($itens_comprados)): ?>
    <div class="content-box" style="margin-top: 20px;">
        <p>Você ainda não realizou nenhum pedido.</p>
    </div>
<?php else: ?>
    <div class="content-box client-report">
        
        <div class="client-info">
            <div class="client-photo">
                <img src="<?php echo htmlspecialchars($cliente_info['foto_perfil']); ?>" alt="Foto de Perfil">
            </div>
            <div class="client-details">
                <h3>Cliente: <?php echo htmlspecialchars($cliente_info['cliente_nome']); ?></h3>
                <p><a href="<?php echo htmlspecialchars($cliente_info['pdf_documento']); ?>" target="_blank">Ver Documento PDF</a></p>
            </div>
        </div>

        <hr>

        <h4>Histórico de Pedidos:</h4>

        <?php
        // Agrupa os produtos por pedido_id para uma exibição clara
        $pedidos_agrupados = [];
        foreach ($itens_comprados as $item) {
            $pedidos_agrupados[$item['pedido_id']][] = $item['produto_nome'];
        }

        // Itera sobre os pedidos agrupados para exibi-los
        foreach ($pedidos_agrupados as $pedido_id => $produtos_do_pedido):
        ?>
            <div class="order-details">
                <strong>Pedido #<?php echo $pedido_id; ?>:</strong>
                <ul>
                    <?php foreach ($produtos_do_pedido as $produto_nome): ?>
                        <li><?php echo htmlspecialchars($produto_nome); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

    </div>
<?php endif; ?>

</main> </body>
</html>