-- CRIA BANCO DE DADOS PETSHOP

CREATE DATABASE IF NOT EXISTS petshop
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE petshop;

-- TABELA DE FUNCIONÁRIOS

CREATE TABLE funcionarios (
    id_funcionario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    cpf VARCHAR(14),
    cargo VARCHAR(50)
);

-- TABELA DE LOGIN

CREATE TABLE login (
    id_login INT PRIMARY KEY AUTO_INCREMENT,
    usuario VARCHAR(50),
    senha VARCHAR(255),
    id_funcionario INT,
    FOREIGN KEY (id_funcionario)
        REFERENCES funcionarios(id_funcionario)
);

-- TABELA DE PRODUTOS

CREATE TABLE produtos (
    id_produto INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255),
    preco DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50),
    data_cadastro DATE,
    -- Impede duplicação do mesmo produto na mesma categoria
    UNIQUE(nome, categoria)
);

-- TABELA DE ESTOQUE

CREATE TABLE estoque (
    id_estoque INT PRIMARY KEY AUTO_INCREMENT,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    quantidade_minima INT,
    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- TABELA DE CAIXA

CREATE TABLE caixa (
    id_caixa INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('entrada','saida') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_movimento DATETIME,
    descricao VARCHAR(255),
    id_funcionario INT,
    FOREIGN KEY (id_funcionario)
        REFERENCES funcionarios(id_funcionario)
);

-- TABELA DE VENDAS

CREATE TABLE venda (
    id_venda INT PRIMARY KEY AUTO_INCREMENT,
    data_venda DATETIME,
    total DECIMAL(10,2)
);

-- TABELA DE ITENS DA VENDA

CREATE TABLE itens_venda (
    id_item INT PRIMARY KEY AUTO_INCREMENT,
    id_venda INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2),
    FOREIGN KEY (id_venda)
        REFERENCES venda(id_venda)
        ON DELETE CASCADE,
    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto)
        ON DELETE CASCADE
);