# 🐾 Petshop 3 - Sistema de Gestão

Um sistema web completo para gerenciamento de pet shops, desenvolvido com **PHP** e **MySQL**. O objetivo do projeto é facilitar o controle de estoque, vendas e fluxo de caixa, além de gerenciar o acesso de funcionários.

## 🚀 Funcionalidades

O sistema conta com as seguintes funcionalidades principais:

*   **Autenticação de Usuários:** Sistema seguro de login para funcionários (`login.php` e `verifica_login.php`).
*   **Gestão de Funcionários:** Cadastro de funcionários com cargos, CPF e vinculação de usuários para acesso ao sistema.
*   **Controle de Estoque:** Cadastro de produtos (com nome, descrição, categoria e preço) e gerenciamento contínuo das quantidades em estoque (`estoque.php`).
*   **Frente de Caixa (PDV):** Registro de vendas, entradas e saídas financeiras, calculando totais de forma automática (`caixa.php`).
*   **Dashboard Administrativo:** Painel central para visualizar informações rápidas e navegar entre os módulos do sistema (`dashboard.php`).

## 🛠️ Tecnologias Utilizadas

*   **Front-End:** HTML5, CSS3
*   **Back-End:** PHP
*   **Banco de Dados:** MySQL (Relacional)

## 🗄️ Estrutura do Banco de Dados

O banco de dados `petshop` possui uma arquitetura relacional estruturada nas seguintes tabelas:

1.  `funcionarios`: Armazena dados dos colaboradores.
2.  `login`: Credenciais de acesso vinculadas aos funcionários.
3.  `produtos`: Catálogo de itens do pet shop.
4.  `estoque`: Quantidade de produtos disponíveis.
5.  `caixa`: Registro de fluxo financeiro (entradas e saídas).
6.  `venda` e `itens_venda`: Armazenam os registros detalhados das transações realizadas.

## 📁 Estrutura do Projeto

```text
petshop3/
├── CSS/                  # Estilos do sistema
├── img/                  # Imagens e ícones
├── config/               # Arquivos de configuração (ex: conexão com o banco)
├── pages/                # Páginas da aplicação
│   ├── cadastrar.php     # Tela de cadastro
│   ├── caixa.php         # Módulo de caixa/vendas
│   ├── dashboard.php     # Painel principal
│   ├── estoque.php       # Gestão do estoque de produtos
│   ├── login.php         # Tela de autenticação
│   ├── logout.php        # Encerramento da sessão
│   └── verifica_login.php# Validação de credenciais e segurança
└── petshop.sql           # Script de criação do Banco de Dados
```

## ⚙️ Como Executar o Projeto

1.  Certifique-se de ter um servidor local instalado (como **XAMPP**, **WAMP** ou **MAMP**).
2.  Clone ou mova este repositório para a pasta pública do seu servidor (ex: `htdocs` no XAMPP).
3.  Abra o painel do seu banco de dados (ex: `phpMyAdmin`) e importe o arquivo `petshop.sql` para criar as tabelas necessárias.
4.  Configure as credenciais do banco de dados na pasta `config`, se necessário.
5.  Acesse o projeto pelo navegador: `http://localhost/petshop3/pages/login.php`
