<?php
require_once "auth.php";
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: painel.php");
    exit;
}

$nome = trim($_POST['nome_item'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco = str_replace(',', '.', trim($_POST['preco'] ?? ''));

if ($nome !== '' && $categoria !== '' && is_numeric($preco)) {
    $preco = (float) $preco;
    $sql = "INSERT INTO cardapio (nome_item, categoria, descricao, preco) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssd", $nome, $categoria, $descricao, $preco);

        if (mysqli_stmt_execute($stmt)) {
            definir_alerta('success', 'Item cadastrado com sucesso!');
        } else {
            definir_alerta('error', 'Erro ao cadastrar o item.');
        }
        mysqli_stmt_close($stmt);
    } else {
        definir_alerta('error', 'Falha ao preparar a consulta.');
    }
} else {
    definir_alerta('warning', 'Preencha todos os campos corretamente.');
}

header("Location: painel.php");
exit;
