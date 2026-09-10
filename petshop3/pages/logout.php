<?php
// Inicia a sessão atual do usuário
session_start();
// Destroi todos os dados da sessão (remove usuário logado)
session_destroy();
// Redireciona o usuário para a página de login
header('Location: login.php');
exit;
?>