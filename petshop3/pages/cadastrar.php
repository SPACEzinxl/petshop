<?php
// Inclui o arquivo de conexão com o banco de dados
include("../config/conexao.php");

// Variável que irá guardar mensagens para mostrar na tela
$mensagem = "";

// Verifica se o formulário foi enviado usando o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Recebe os dados digitados no formulário
    $usuario = $_POST["usuario"];
    $senhaDigitada = $_POST["senha"];

    // Verifica se os campos não estão vazios
    if (!empty($usuario) && !empty($senhaDigitada)) {

        // Criptografa a senha digitada para maior segurança
        $senha = password_hash($senhaDigitada, PASSWORD_DEFAULT);

        // Comando SQL para inserir o usuário e senha no banco
        $sql = "INSERT INTO login (usuario, senha)
                VALUES (?, ?)";

        // Prepara a query para evitar SQL Injection
        $stmt = mysqli_prepare($conexao, $sql);
        
         // Liga os parâmetros (usuario e senha) ao comando SQL
        mysqli_stmt_bind_param($stmt, "ss", $usuario, $senha);

        // Executa a query
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "✅ Usuário criado com sucesso!";
        } else {
            $mensagem = "❌ Erro ao criar usuário.";
        }

    } else {
        $mensagem = "⚠️ Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../CSS/cadastrar.css">
<title>Cadastro</title>
</head>
<body>

<!-- Lado esquerdo da tela -->
<div class="left">
    <img src="../img/logo.png" alt="Logo">
    <h1>Funcionário</h1>
</div>

<!-- Lado direito da tela -->
<div class="right">

<!-- Caixa onde fica o formulário -->
    <div class="card">
        <h2>Cadastro</h2>

 <!-- Mostra a mensagem criada no PHP (sucesso, erro ou aviso) -->
        <?php if (!empty($mensagem)) echo "<p class='mensagem'>$mensagem</p>"; ?>

<!-- Formulário de cadastro -->
        <form method="POST">

            <label>Usuário</label>
            <input type="text" name="usuario" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <button type="submit">Cadastrar</button>

        </form>

        <br>

<!-- Link para voltar para a tela de login -->
        <p style="text-align:center">
            <a href="login.php">Ir para Login</a>
        </p>
    </div>
</div>

</body>
</html>