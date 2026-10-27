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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar item - Cafeteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container" style="max-width: 700px; margin-top: 50px;">
        <div class="card shadow-sm">
            <div class="card-header">Editar item #<?= (int) $item['id'] ?></div>
            <div class="card-body">
                <form method="POST" action="editar_item.php" class="row g-3">
                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                    <div class="col-md-8">
                        <label class="form-label">Nome do Item</label>
                        <input type="text" name="nome_item" class="form-control" required
                               value="<?= htmlspecialchars($item['nome_item']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Preço (R$)</label>
                        <input type="text" name="preco" class="form-control" required
                               value="<?= number_format($item['preco'], 2, ',', '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Categoria</label>
                        <select name="categoria" class="form-select" required>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat ?>" <?= $item['categoria'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($item['descricao'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Salvar alterações</button>
                        <a href="painel.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
