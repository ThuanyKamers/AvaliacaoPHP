<?php
require 'config.php';

// --- Início da Lógica PHP (sem imprimir nada ainda) ---
$html_output = ''; // Variável para guardar a mensagem a ser exibida
$page_title = 'Status da Recuperação'; // Título padrão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verifica se o e-mail existe no banco
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Gera um token de segurança aleatório e único
        $token = bin2hex(random_bytes(32));
        
        // Define a data de expiração do token (ex: 1 hora a partir de agora)
        $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Salva o token e a data de expiração no registro do usuário
        $stmt = $pdo->prepare("UPDATE clientes SET reset_token = ?, reset_token_expires_at = ? WHERE email = ?");
        $stmt->execute([$token, $expires_at, $email]);

        // Prepara a mensagem de SUCESSO
        $page_title = "Link Gerado";
        $reset_link = "http://localhost/Avaliacao/reset_senha.php?token=" . $token;
        $html_output = "
            <h2>Link de Recuperação Gerado</h2>
            <p>Em um sistema real, o link abaixo seria enviado para o seu e-mail.</p>
            <p>Para continuar o teste, clique no link:</p>
            <p><a href='{$reset_link}'>{$reset_link}</a></p>
        ";

    } else {
        // Prepara a mensagem de ERRO
        $page_title = "E-mail não encontrado";
        $html_output = "
            <h2>E-mail não encontrado!</h2>
            <p>Nenhum usuário com este e-mail foi encontrado em nosso sistema.</p>
            <p><a href='recuperar_senha.php'>Tentar novamente</a></p>
        ";
    }
} else {
    // Mensagem para caso a página seja acessada diretamente
    $page_title = "Acesso Inválido";
    $html_output = "<p>Esta página deve ser acessada através do envio do formulário de recuperação.</p>";
}

// --- Fim da Lógica PHP ---


// --- Início da Saída HTML (agora com estilo) ---

// Inclui o cabeçalho padrão
include 'header.php'; 
?>

<div class="content-box">
    <?php 
    // Imprime a mensagem que foi preparada acima (seja de sucesso ou erro)
    echo $html_output; 
    ?>
</div>

</main> </body>
</html>