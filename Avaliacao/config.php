<?php
// ===============================================================
// ARQUIVO DE CONFIGURAÇÃO DO BANCO DE DADOS
// ===============================================================

// --- Parâmetros de Conexão ---
// Altere estes valores de acordo com a sua configuração local.

// Onde o banco de dados está rodando. 'localhost' é o padrão para o XAMPP.
$host = 'localhost';

// O nome do banco de dados que você criou no phpMyAdmin.
$db_name = 'sistema_loja'; 

// O usuário para acessar o banco de dados. 'root' é o padrão do XAMPP.
$username = 'root'; 

// A senha para o usuário do banco de dados. Por padrão, no XAMPP, a senha é vazia.
$password = ''; 

// --- Estabelecendo a Conexão ---

try {
    // Cria uma nova conexão PDO (PHP Data Objects). Esta é a forma moderna e segura de se conectar.
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);

    // Configura o PDO para lançar exceções em caso de erros de SQL.
    // Isso ajuda muito a encontrar problemas.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define o modo padrão de busca de dados para retornar arrays associativos.
    // Facilita o acesso aos dados (ex: $resultado['nome'] em vez de $resultado[0]).
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Se a conexão falhar, o script para e exibe uma mensagem de erro clara.
    // Em um site em produção, você poderia logar este erro em vez de exibi-lo na tela.
    die("ERRO: Não foi possível conectar ao banco de dados. " . $e->getMessage());
}
?>