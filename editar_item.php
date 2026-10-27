<?php
require_once "auth.php";
require_once "conexao.php";

$categorias = ['Café', 'Doce', 'Salgado', 'Bebida Gelada'];

// U - Salvar a edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome_item'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = str_replace(',', '.', trim($_POST['preco'] ?? ''));

    if ($id > 0 && $nome !== '' && $categoria !== '' && is_numeric($preco)) {
        $preco = (float) $preco;
        $sql = "UPDATE cardapio SET nome_item = ?, categoria = ?, descricao = ?, preco = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssdi", $nome, $categoria, $descricao, $preco, $id);

            if (mysqli_stmt_execute($stmt)) {
                definir_alerta('success', 'Item atualizado com sucesso!');
            } else {
                definir_alerta('error', 'Erro ao atualizar o item.');
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
}

// Carregar o item para preencher o formulário
$id = (int) ($_GET['id'] ?? 0);
$item = null;

if ($id > 0) {
    $sql = "SELECT id, nome_item, categoria, descricao, preco FROM cardapio WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
}

if (!$item) {
    definir_alerta('error', 'Item não encontrado.');
    header("Location: painel.php");
    exit;
}

$titulo_pagina = "Editar item";
require "topo.php";
?>

<header>
    <h1>Editar item</h1>
    <p>Ajuste os dados do item #<?= (int) $item['id'] ?>.</p>
</header>

<section class="secao centro">
    <form method="POST" action="editar_item.php" class="grade-form">
        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">

        <div class="campo">
            <label for="nome_item">Nome do item</label>
            <input type="text" id="nome_item" name="nome_item" required
                   value="<?= htmlspecialchars($item['nome_item']) ?>">
        </div>
        <div class="campo">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria" required>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat ?>" <?= $item['categoria'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="campo">
            <label for="preco">Preço (R$)</label>
            <input type="text" id="preco" name="preco" required
                   value="<?= number_format($item['preco'], 2, ',', '') ?>">
        </div>
        <div class="campo inteira">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="3"><?= htmlspecialchars($item['descricao'] ?? '') ?></textarea>
        </div>
        <div class="inteira acoes-form">
            <button type="submit" class="btn btn-principal">Salvar alterações</button>
            <a href="painel.php" class="btn btn-claro">Cancelar</a>
        </div>
    </form>
</section>

<?php require "rodape.php"; ?>
