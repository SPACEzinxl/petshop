<?php
// Inicia a sessão para armazenar dados do usuário logado
session_start();

// Inclui o arquivo de conexão com o banco de dados
include "../config/conexao.php";

// Recebe os dados enviados pelo formulário de login
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

/* Buscar usuário */

// Query SQL para buscar o usuário na tabela login
$sql = "SELECT * FROM login WHERE usuario = ?";

// Prepara a query para evitar SQL Injection
$stmt = mysqli_prepare($conexao, $sql);

// Associa o parâmetro da query
// "s" significa string
mysqli_stmt_bind_param($stmt, "s", $usuario);

// Executa a consulta no banco
mysqli_stmt_execute($stmt);

// Obtém o resultado
$result = mysqli_stmt_get_result($stmt);

/* VERIFICAR SE USUÁRIO EXISTE */

// Verifica se encontrou exatamente 1 usuário
if (mysqli_num_rows($result) === 1) {
// Converte o resultado em array associativo
    $dados = mysqli_fetch_assoc($result);

 // Verifica se a senha digitada corresponde à senha criptografada do banco
    if (password_verify($senha, $dados['senha'])) {
// Cria sessão com o nome do usuário
        $_SESSION['usuario'] = $dados['usuario'];
// Cria sessão com o nome do usuário
        $_SESSION['id_funcionario'] = $dados['id_funcionario'];

// Redireciona para o dashboard do sistema
        header("Location: dashboard.php");
        exit;
    } 
}

// Cria mensagem de erro na sessão
$_SESSION['erro'] = "Usuário ou senha inválidos";

// Redireciona de volta para a página de login
header("Location: login.php");
exit;