sistema_loja


Por favor, gere o código SQL completo para criar o banco de dados de um projeto de sistema de e-commerce e cadastro de clientes em PHP e MySQL.

O banco de dados deve ter a seguinte estrutura:

Tabela clientes:

Deve conter colunas para: id (chave primária, auto-incremento), nome, email (único), senha, foto_perfil (para armazenar o caminho do arquivo), pdf_documento (para o caminho do arquivo).
Deve incluir também as colunas para a funcionalidade de recuperação de senha: reset_token e reset_token_expires_at.
Tabela produtos:

Deve conter colunas para: id (chave primária, auto-incremento), nome, preco e imagem_url (para armazenar o caminho da imagem do produto).
Tabela pedidos:

Deve conter colunas para: id (chave primária, auto-incremento), cliente_id (chave estrangeira referenciando clientes.id), data_pedido e total.
Tabela itens_pedido:

Deve conter colunas para: id (chave primária, auto-incremento), pedido_id (chave estrangeira referenciando pedidos.id), produto_id (chave estrangeira referenciando produtos.id), quantidade e preco_unitario.
Requisitos Adicionais:

As chaves estrangeiras devem ser definidas corretamente para manter a integridade relacional.
Por favor, inclua também comandos INSERT para adicionar 3 produtos de exemplo na tabela produtos, para que o sistema possa ser testado.
O resultado final deve ser um script SQL único com todos os comandos CREATE TABLE e INSERT.
