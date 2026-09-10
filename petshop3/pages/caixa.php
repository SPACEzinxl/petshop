<?php
// Inicia sessão para controle de login
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Inclui o arquivo de conexão com o banco de dados
include(__DIR__ . "/../config/conexao.php");

/* 1️⃣ BUSCAR PRODUTOS PARA VENDA */
$sql_produtos = "
SELECT 
    p.id_produto,
    p.nome,
    p.preco,
    e.quantidade
FROM produtos p
JOIN estoque e ON e.id_produto = p.id_produto
ORDER BY p.nome
";
$result_produtos = mysqli_query($conexao,$sql_produtos);

/* 2️⃣ REGISTRA VENDA */
if(isset($_POST['venda'])){
    $id_produto = $_POST['id_produto'];
    $qtd_vendida = $_POST['quantidade'];
    $sql_check = "
    SELECT p.preco, e.quantidade
    FROM produtos p
    JOIN estoque e ON e.id_produto = p.id_produto
    WHERE p.id_produto = ?
    ";
    $stmt = mysqli_prepare($conexao,$sql_check);
    mysqli_stmt_bind_param($stmt,"i",$id_produto);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $produto = mysqli_fetch_assoc($result);

    if(!$produto){
        $erro = "Produto não encontrado.";
    } elseif($qtd_vendida > $produto['quantidade']){
        $erro = "Estoque insuficiente.";
    } else {
        $valor_unitario = $produto['preco'];
        $total = $valor_unitario * $qtd_vendida;
        $sql_venda = "INSERT INTO venda(data_venda,total) VALUES(NOW(),?)";
        $stmt_venda = mysqli_prepare($conexao,$sql_venda);
        mysqli_stmt_bind_param($stmt_venda,"d",$total);
        mysqli_stmt_execute($stmt_venda);
        $id_venda = mysqli_insert_id($conexao);
        $sql_item = "INSERT INTO itens_venda
        (id_venda,id_produto,quantidade,preco_unitario)
        VALUES(?,?,?,?)";
        $stmt_item = mysqli_prepare($conexao,$sql_item);
        mysqli_stmt_bind_param($stmt_item,"iiid",$id_venda,$id_produto,$qtd_vendida,$valor_unitario);
        mysqli_stmt_execute($stmt_item);
        $sql_update = "UPDATE estoque
                       SET quantidade = quantidade - ?
                       WHERE id_produto = ?";
        $stmt_update = mysqli_prepare($conexao,$sql_update);
        mysqli_stmt_bind_param($stmt_update,"ii",$qtd_vendida,$id_produto);
        mysqli_stmt_execute($stmt_update);
        $sql_caixa = "INSERT INTO caixa(tipo,valor,data_movimento,descricao)
                      VALUES('entrada',?,NOW(),'Venda')";
        $stmt_caixa = mysqli_prepare($conexao,$sql_caixa);
        mysqli_stmt_bind_param($stmt_caixa,"d",$total);
        mysqli_stmt_execute($stmt_caixa);
        $sucesso = "Venda registrada!";
    }
}

/* 3️⃣ RETIRAR DINHEIRO DO CAIXA */
if(isset($_POST['retirada'])){
    $valor_saida = $_POST['valor_saida'];
    $descricao = $_POST['descricao'];
    $id_funcionario = $_SESSION['id_funcionario'];
    $sql_saida = "INSERT INTO caixa
    (tipo,valor,data_movimento,descricao,id_funcionario)
    VALUES('saida',?,NOW(),?,?)";
    $stmt_saida = mysqli_prepare($conexao,$sql_saida);
    mysqli_stmt_bind_param($stmt_saida,"dsi",$valor_saida,$descricao,$id_funcionario);
    mysqli_stmt_execute($stmt_saida);
    $sucesso = "Retirada registrada!";
}

/* 4️⃣ CONSULTAR HISTÓRICO DO CAIXA */
$sql_caixa = "
SELECT
    c.tipo,
    c.valor,
    c.descricao,
    c.data_movimento,
    f.nome AS funcionario
FROM caixa c
LEFT JOIN funcionarios f ON f.id_funcionario = c.id_funcionario
ORDER BY c.data_movimento DESC
";
$result_caixa = mysqli_query($conexao,$sql_caixa);

/* 5️⃣ CALCULAR SALDO ATUAL DO CAIXA */
$sql_saldo = "
SELECT 
    SUM(CASE WHEN tipo = 'entrada' THEN valor ELSE 0 END) AS total_entrada,
    SUM(CASE WHEN tipo = 'saida' THEN valor ELSE 0 END) AS total_saida
FROM caixa
";
$result_saldo = mysqli_query($conexao, $sql_saldo);
$saldo_entrada = 0;
$saldo_saida = 0;
$saldo_atual = 0;
if ($row_saldo = mysqli_fetch_assoc($result_saldo)) {
    $saldo_entrada = $row_saldo['total_entrada'] ?? 0;
    $saldo_saida = $row_saldo['total_saida'] ?? 0;
    $saldo_atual = $saldo_entrada - $saldo_saida;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Caixa</title>
<link rel="stylesheet" href="../CSS/caixa.css">
</head>
<body>
<div class="container">
<?php include(__DIR__ . "/includes/sidebar.php"); ?>
<main class="content">

<h1>Caixa</h1>

<!-- Exibe o saldo atual do caixa -->
<div class="card">
    <h3>Saldo Atual do Caixa</h3>
    <p>R$ <?= number_format($saldo_atual, 2, ',', '.') ?></p>
</div>

<!-- Mensagem de erro -->
<?php if(!empty($erro)): ?>
<p style="color:red"><?= $erro ?></p>
<?php endif; ?>

<!-- Mensagem de sucesso -->
<?php if(!empty($sucesso)): ?>
<p style="color:green"><?= $sucesso ?></p>
<?php endif; ?>

<div class="caixa-area">

    <!-- FORMULÁRIO DE VENDA -->
    <form method="POST" class="form-estoque">
        <h2>Registrar Venda</h2>
        <label>Produto</label>
        <select name="id_produto" required>
            <option value="">Selecione</option>
            <?php while($p = mysqli_fetch_assoc($result_produtos)): ?>
            <option value="<?= $p['id_produto'] ?>">
                <?= $p['nome'] ?> (<?= $p['quantidade'] ?> em estoque)
            </option>
            <?php endwhile; ?>
        </select>
        <label>Quantidade</label>
        <input type="number" name="quantidade" min="1" required>
        <button type="submit" name="venda">Registrar Venda</button>
    </form>

    <!-- FORMULÁRIO DE RETIRADA -->
    <form method="POST" class="form-estoque">
        <h2>Retirar dinheiro do caixa</h2>
        <label>Valor</label>
        <input type="number" step="0.01" name="valor_saida" required>
        <label>Motivo</label>
        <input type="text" name="descricao" required>
        <button type="submit" name="retirada">Retirar dinheiro</button>
    </form>

</div>

<!-- HISTÓRICO DO CAIXA-->
<h2>Histórico do Caixa</h2>
<table border="1" class="tabela-estoque">
<tr>
<th>Tipo</th>
<th>Valor</th>
<th>Descrição</th>
<th>Funcionário</th>
<th>Data</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result_caixa)): ?>
<tr>
<td><?= htmlspecialchars($row['tipo']) ?></td>
<td>R$ <?= number_format($row['valor'],2,',','.') ?></td>
<td><?= htmlspecialchars($row['descricao']) ?></td>
<td><?= htmlspecialchars($row['funcionario']) ?></td>
<td><?= date('d/m/Y H:i', strtotime($row['data_movimento'])) ?></td>
</tr>
<?php endwhile; ?>
</table>

</main>
</div>
</body>