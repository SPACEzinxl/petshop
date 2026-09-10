<?php
// Inicia a sessão para controlar login do usuário
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Importa o arquivo de conexão com o banco de dados
include(__DIR__ . "/../config/conexao.php");

/* CADASTRAR PRODUTO OU ATUALIZAR ESTOQUE */

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recebe dados do formulário
    $nome       = $_POST['nome'];
    $categoria  = $_POST['categoria'];
    $quantidade = $_POST['quantidade'];
    $valor      = $_POST['valor'];

    /* VERIFICAR SE PRODUTO JÁ EXISTE (MESMO NOME E CATEGORIA) */

    // Query que busca produto com mesmo nome e categoria
    $sql_verifica = "SELECT id_produto FROM produtos 
                     WHERE nome = ? AND categoria = ?";

    // Prepara a consulta
    $stmt_verifica = mysqli_prepare($conexao, $sql_verifica);

    // Associa parâmetros
    mysqli_stmt_bind_param($stmt_verifica, "ss", $nome, $categoria);

    // Executa
    mysqli_stmt_execute($stmt_verifica);

    // Pega resultado
    $resultado = mysqli_stmt_get_result($stmt_verifica);

    // Se produto já existir
    if (mysqli_num_rows($resultado) > 0) {

        // Pega o ID do produto existente
        $produto = mysqli_fetch_assoc($resultado);
        $id_produto = $produto['id_produto'];

        /* ATUALIZAR ESTOQUE */

        $sql_update = "UPDATE estoque
                       SET quantidade = quantidade + ?
                       WHERE id_produto = ?";

        // Prepara consulta
        $stmt_update = mysqli_prepare($conexao, $sql_update);

        // Liga parâmetros
        mysqli_stmt_bind_param($stmt_update, "ii", $quantidade, $id_produto);

        // Executa
        mysqli_stmt_execute($stmt_update);

    } else {

        /* PRODUTO NÃO EXISTE → CADASTRAR NOVO */

        // Query para inserir novo produto
        $sql_produto = "INSERT INTO produtos (nome, categoria, preco, data_cadastro)
                        VALUES (?, ?, ?, NOW())";

        // Prepara consulta
        $stmt = mysqli_prepare($conexao, $sql_produto);

        // s = string | d = decimal
        mysqli_stmt_bind_param($stmt, "ssd", $nome, $categoria, $valor);

        // Executa
        mysqli_stmt_execute($stmt);

        // Pega ID do produto criado
        $id_produto = mysqli_insert_id($conexao);

        /* CADASTRAR ESTOQUE INICIAL */

        $sql_estoque = "INSERT INTO estoque (id_produto, quantidade, quantidade_minima)
                        VALUES (?, ?, 5)";

        // Prepara consulta
        $stmt2 = mysqli_prepare($conexao, $sql_estoque);

        // Liga parâmetros
        mysqli_stmt_bind_param($stmt2, "ii", $id_produto, $quantidade);

        // Executa
        mysqli_stmt_execute($stmt2);
    }

    // Mensagem de sucesso
    $sucesso = true;
}

/* LISTAR ESTOQUE */

// Consulta que busca produtos e seus estoques
$sql_lista = "
SELECT 
    p.nome,
    p.categoria,
    p.preco,
    e.quantidade,
    e.quantidade_minima
FROM produtos p
INNER JOIN estoque e ON e.id_produto = p.id_produto
ORDER BY p.nome
";

// Executa consulta
$result_lista = mysqli_query($conexao, $sql_lista);

// Verifica erro
if (!$result_lista) {
    die("Erro na listagem: " . mysqli_error($conexao));
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Estoque</title>
<link rel="stylesheet" href="../CSS/estoque.css">
</head>

<body>

<div class="container">

<!-- Sidebar do sistema -->
<?php include(__DIR__ . "/includes/sidebar.php"); ?>

<main class="content">

<h1>Cadastro de Produto</h1>

<!-- Mensagem de sucesso -->
<?php if (!empty($sucesso)): ?>
<p style="color: green;">Produto cadastrado/estoque atualizado com sucesso!</p>
<?php endif; ?>

<!-- FORMULÁRIO DE CADASTRO -->

<form method="POST" class="form-estoque">

<label>Nome do Produto</label>
<input type="text" name="nome" required>

<label>Categoria</label>
<input type="text" name="categoria" required>

<label>Quantidade</label>
<input type="number" name="quantidade" min="1" required>

<label>Valor Unitário (R$)</label>
<input type="number" name="valor" step="0.01" min="0" required>

<button type="submit">Cadastrar Produto</button>

</form>

<!-- LISTA DE PRODUTOS -->

<h2 style="margin-top:40px;">Estoque Atual</h2>

<table class="tabela-estoque">

<thead>
<tr>
<th>Produto</th>
<th>Categoria</th>
<th>Quantidade</th>
<th>Valor Unitário</th>
<th>Valor Total</th>
<th>Status</th>
</tr>
</thead>

<tbody>

<?php 
// Loop para mostrar produtos
while ($row = mysqli_fetch_assoc($result_lista)):

// Calcula valor total do estoque
$valor_total = $row['quantidade'] * $row['preco'];

// Verifica se estoque está baixo
$baixo = $row['quantidade'] < $row['quantidade_minima'];
?>

<tr class="<?= $baixo ? 'baixo' : '' ?>">

<td><?= htmlspecialchars($row['nome']) ?></td>

<td><?= htmlspecialchars($row['categoria']) ?></td>

<td><?= $row['quantidade'] ?></td>

<td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>

<td>R$ <?= number_format($valor_total, 2, ',', '.') ?></td>

<td><?= $baixo ? '⚠️ Estoque baixo' : '✅ OK' ?></td>

</tr>

<?php endwhile; ?>

</tbody>
</table>

</main>
</div>

</body>
</html>