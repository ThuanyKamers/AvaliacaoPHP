<?php
require 'config.php';
$token = $_GET['token'] ?? '';
$error = '';
$token_valid = false;

if ($token) {
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE reset_token = ? AND reset_token_expires_at > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $token_valid = true;
    } else {
        $error = "Token inválido ou expirado. Por favor, solicite um novo link de recuperação.";
    }
} else {
    $error = "Nenhum token fornecido.";
}

$page_title = "Redefinir Senha";
// ✅ 1. Inclui o cabeçalho padrão.
include 'header.php';
?>

<div class="form-box">
    <h2>Redefinir Senha</h2>

    <?php if ($token_valid): ?>
        <form action="processa_reset.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="input-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>
            
             <button type="submit" class="btn">Salvar Nova Senha</button>
        </form>
    <?php else: ?>
        <p class="form-message error"><?php echo $error; ?></p>
    <?php endif; ?>
</div>

</main> </body>
</html>