<?php
// Inicia uma sessão para armazenar dados do usuário logado
session_start();
// Inclui o arquivo de conexão com o banco de dados
include("../config/conexao.php");
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
  </head>
  <body>
    <!-- Container principal da página -->
        <div class="container">
            <!-- Lado Esquerdo -->
            <div class="left">
                <img src="../img/logo.png" class="logo">
                <h2>Funcionario</h2>
            </div>

        <!-- Lado Direito -->
         <div class="right">
            
         <img src="../img/dog.png" class="dog">

         <div class="login-box">
            <h3>Login</h3>
            <!-- Formulário que envia os dados para o arquivo verifica_login.php -->
            <form method="POST" action="verifica_login.php">
                <label>Usuário:</label>
                <input type="text" name="usuario" required>

                <label>Senha:</label>
                <input type="password" name="senha" required>
                <button type="submit">Entrar  🐾</button>
            </form>
                <br>
                  <p style="text-align:center">
                   <p style="text-align:center">Sem cadastro <a href="cadastrar.php">Cadastrar</a></p>
                </p>

         </div>
    </div>
</div>
 <!-- Script do Bootstrap para funcionalidades em JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  </body>
</html> 