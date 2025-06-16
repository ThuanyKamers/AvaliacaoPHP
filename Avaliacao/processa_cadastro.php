<?php
// 1. INCLUI O ARQUIVO DE CONEXÃO COM O BANCO DE DADOS (CORRIGE O ERRO DO $pdo)
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta de dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Sempre use hash para senhas!

    // --- Processamento da Foto de Perfil ---
    $foto_perfil = $_FILES['foto'];
    
    // 2. DEFINE OS DIRETÓRIOS DE UPLOAD (ELES PRECISAM EXISTIR!)
    $upload_dir_fotos = 'uploads/fotos/';

    // Validação da imagem (tipo e tamanho)
    $allowed_image_types = ['image/jpeg', 'image/png'];
    $max_image_size = 2 * 1024 * 1024; // 2MB

    if ($foto_perfil['error'] === UPLOAD_ERR_OK && $foto_perfil['size'] <= $max_image_size && in_array($foto_perfil['type'], $allowed_image_types)) {
        $foto_nome = uniqid() . '-' . basename($foto_perfil['name']);
        $foto_path = $upload_dir_fotos . $foto_nome;
        // Esta linha só funcionará se as pastas existirem
        move_uploaded_file($foto_perfil['tmp_name'], $foto_path);
    } else {
        die("Erro no upload da foto: tipo ou tamanho inválido.");
    }

    // --- Processamento do PDF ---
    $pdf_documento = $_FILES['pdf'];
    $upload_dir_docs = 'uploads/documentos/';
    
    // Validação do PDF
    $allowed_pdf_type = 'application/pdf';
    $max_pdf_size = 5 * 1024 * 1024; // 5MB

    if ($pdf_documento['error'] === UPLOAD_ERR_OK && $pdf_documento['size'] <= $max_pdf_size && $pdf_documento['type'] === $allowed_pdf_type) {
        $pdf_nome = uniqid() . '-' . basename($pdf_documento['name']);
        $pdf_path = $upload_dir_docs . $pdf_nome;
        // Esta linha só funcionará se as pastas existirem
        move_uploaded_file($pdf_documento['tmp_name'], $pdf_path);
    } else {
        die("Erro no upload do PDF: tipo ou tamanho inválido.");
    }

    // Inserção no Banco de Dados
    try {
        // Esta linha agora funcionará porque $pdo existe
        $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, senha, foto_perfil, pdf_documento) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senha, $foto_path, $pdf_path]);

        // Redireciona para a página de login após o sucesso
        header("Location: login.php?status=success");
        exit();
    } catch (PDOException $e) {
        // Trata erro de e-mail duplicado
        if ($e->errorInfo[1] == 1062) {
            die("Erro: O e-mail informado já está cadastrado.");
        } else {
            die("Erro ao cadastrar: " . $e->getMessage());
        }
    }
}
?>