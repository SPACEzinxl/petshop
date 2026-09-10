<?php
// Inicia a sessão para controlar o login do usuário
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Inclui o arquivo de conexão com o banco de dados
include("../config/conexao.php");

/* TOTAL DE PRODUTOS */

// Consulta SQL que conta quantos produtos existem na tabela produtos
$sql_produtos = "SELECT COUNT(*) AS total_produtos FROM produtos";

// Executa a consulta no banco
$res_produtos = mysqli_query($conexao, $sql_produtos);

// Pega o resultado da consulta e salva na variável
// Se não houver resultado, retorna 0
$total_produtos = mysqli_fetch_assoc($res_produtos)['total_produtos'] ?? 0;

/* TOTAL EM ESTOQUE */

// Consulta SQL que soma todas as quantidades da tabela estoque
$sql_estoque = "SELECT SUM(quantidade) AS total_estoque FROM estoque";

// Executa a consulta
$res_estoque = mysqli_query($conexao, $sql_estoque);

// Pega o resultado da soma das quantidades
$total_estoque = mysqli_fetch_assoc($res_estoque)['total_estoque'] ?? 0;

/* PRODUTOS ABAIXO DO ESTOQUE MÍNIMO */

// Consulta SQL que conta quantos produtos estão abaixo do estoque mínimo
$sql_minimo = "SELECT COUNT(*) AS abaixo_minimo FROM estoque WHERE quantidade < quantidade_minima";

// Executa a consulta
$res_minimo = mysqli_query($conexao, $sql_minimo);

// Guarda o resultado da consulta
$abaixo_minimo = mysqli_fetch_assoc($res_minimo)['abaixo_minimo'] ?? 0;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/styledash.css">
</head>
<body>

<!-- Container principal da página -->
<div class="container">

<!-- Inclui o menu lateral do sistema (sidebar) -->
    <?php include("includes/sidebar.php"); ?>

<!-- Área principal onde aparece o conteúdo -->
    <main class="content">
        <h1>Dashboard</h1>

<!-- Área onde ficam os cartões com informações -->
<div class="cards">

<!-- Card que mostra quantos produtos estão cadastrados -->
    <div class="card">
        <h3>Produtos Cadastrados</h3>
        <p><?php echo $total_produtos; ?></p>
    </div>

<!-- Card que mostra o total de itens em estoque -->
    <div class="card">
        <h3>Total em Estoque</h3>
        <p><?php echo $total_estoque; ?></p>
    </div>

<!-- Card de alerta mostrando produtos abaixo do estoque mínimo -->
    <div class="card alerta">
        <h3>Abaixo do Estoque Mínimo</h3>
        <p><?php echo $abaixo_minimo; ?></p>
    </div>

</div>
    </main>
</div>

</body>
</html>