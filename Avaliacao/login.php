<?php
// Define o título da página
$page_title = "Login";
// Inclui o cabeçalho, que já contém o session_start() e o link para o CSS
include 'header.php';
?>

<div class="form-box">
    <h2>Login</h2>

    <?php
        // Bloco para exibir mensagens de status (logout, cadastro, erro)
        if (isset($_GET['status']) && $_GET['status'] == 'logout') {
            echo "<p class='form-message success'>Você saiu com sucesso!</p>";
        }
        if (isset($_GET['status']) && $_GET['status'] == 'success') {
            echo "<p class='form-message success'>Cadastro realizado com sucesso! Faça o login.</p>";
        }
        if (isset($_SESSION['login_error'])) {
            echo "<p class='form-message error'>" . htmlspecialchars($_SESSION['login_error']) . "</p>";
            unset($_SESSION['login_error']); // Limpa o erro após exibir
        }
        if (isset($_GET['status']) && $_GET['status'] == 'reset_success') {
            echo "<p class='form-message success'>Sua senha foi redefinida com sucesso! Por favor, faça o login.</p>";
        }
    ?>

    <?php 
    // Verifica se o login está bloqueado após 3 tentativas
    if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3): 
    ?>
        <p style="text-align: center;">Você excedeu o número de tentativas. <a href="recuperar_senha.php">Recuperar senha</a>.</p>
    <?php else: ?>
        <form action="processa_login.php" method="post">
            <div class="input-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn">Entrar</button>
        </form>

        <div class="form-link">
            <p>Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        </div>

    <?php endif; ?>
</div>

</main> </body>
</html>