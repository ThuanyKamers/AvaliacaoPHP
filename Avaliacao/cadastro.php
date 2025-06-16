<?php
// Define o título da página para o cabeçalho
$page_title = "Cadastro de Cliente"; 
// Inclui o cabeçalho padrão, que traz o menu e o link para o CSS
include 'header.php'; 
?>

<div class="form-box">
    
    <h2>Cadastro de Cliente</h2>

    <form action="processa_cadastro.php" method="post" enctype="multipart/form-data">

        <div class="input-group">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
        </div>

        <div class="input-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="input-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>

        <div class="input-group">
            <label for="foto">Foto de Perfil (JPG, PNG):</label>
            <input type="file" id="foto" name="foto" accept="image/jpeg, image/png" required>
        </div>

        <div class="input-group">
            <label for="pdf">Documento (PDF):</label>
            <input type="file" id="pdf" name="pdf" accept="application/pdf" required>
        </div>

        <button type="submit" class="btn">Cadastrar</button>

    </form>
</div>

</main> </body>
</html>