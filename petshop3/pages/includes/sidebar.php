<?php
// Pega o nome da página atual que está sendo aberta
// Exemplo: se estiver em "dashboard.php", $pagina será "dashboard.php"
$pagina = basename($_SERVER['PHP_SELF']);
?>

<!-- Importa o arquivo CSS responsável pelo estilo da sidebar -->
<link rel="stylesheet" href="../CSS/sidebar.css">

<!-- Barra lateral do sistema -->
<aside class="sidebar">
    <h2>🐾 Pet Shop</h2>

<!-- Lista do menu de navegação -->
    <ul class="menu">

<!-- Item do menu: Dashboard / Início -->
<!-- Se a página atual for dashboard.php, adiciona a classe "active" -->
 <!-- Isso serve para destacar a página atual no menu -->
        <li class="<?= $pagina == 'dashboard.php' ? 'active' : '' ?>">
            <a href="dashboard.php">Início</a>
        </li>

 <!-- Item do menu: Estoque -->
 <!-- Destaca se a página atual for estoque.php -->
        <li class="<?= $pagina == 'estoque.php' ? 'active' : '' ?>">
            <a href="estoque.php">Estoque</a>
        </li>

<!-- Item do menu: Caixa -->
        <!-- Destaca se a página atual for caixa.php -->
        <li class="<?= $pagina == 'caixa.php' ? 'active' : '' ?>">
            <a href="caixa.php">Caixa</a>
        </li>

<!-- Item do menu: Logout -->
<!-- Leva para o arquivo que encerra a sessão do usuário -->
        <li>
            <a href="logout.php">Sair</a>
        </li>
    </ul>
</aside>