<?php
$page_title = "Recuperar Senha";
// ✅ 1. Inclui o cabeçalho padrão, que traz o menu e o CSS.
include 'header.php'; 
?>

<div class="form-box">
    <h2>Recuperar Senha</h2>
    <p style="text-align: center; color: var(--cor-texto-secundario); margin-bottom: 20px;">
        Digite seu e-mail e enviaremos um link para você redefinir sua senha.
    </p>

    <form action="processa_recuperacao.php" method="post">
        <div class="input-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit" class="btn">Enviar Link</button>
    </form>
</div>

</main> </body>
</html>