<?php
require_once "auth.php";
require_once "conexao.php";

// Alerta vindo de salvar/editar/excluir
$script_alerta = "";
if (isset($_SESSION['alerta'])) {
    $script_alerta = "Swal.fire(" . json_encode($_SESSION['alerta']) . ");";
    unset($_SESSION['alerta']);
}

// R - Listar itens
$sql = "SELECT id, nome_item, categoria, descricao, preco, disponivel FROM cardapio ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$itens = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel - Cafeteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">☕ Painel do Cardápio</span>
            <span class="text-white">
                Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                <a href="logout.php" class="btn btn-sm btn-outline-light ms-3">Sair</a>
            </span>
        </div>
    </nav>

    <div class="container">
        <!-- C - Cadastrar -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">Cadastrar novo item</div>
            <div class="card-body">
                <form method="POST" action="salvar_item.php" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome do Item</label>
                        <input type="text" name="nome_item" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Categoria</label>
                        <select name="categoria" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="Café">Café</option>
                            <option value="Doce">Doce</option>
                            <option value="Salgado">Salgado</option>
                            <option value="Bebida Gelada">Bebida Gelada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Preço (R$)</label>
                        <input type="text" name="preco" class="form-control" placeholder="0,00" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- R - Listar -->
        <div class="card shadow-sm mb-5">
            <div class="card-header">Itens do cardápio</div>
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($itens) === 0): ?>
                            <tr><td colspan="6" class="text-center">Nenhum item cadastrado.</td></tr>
                        <?php endif; ?>
                        <?php while ($item = mysqli_fetch_assoc($itens)): ?>
                            <tr>
                                <td><?= (int) $item['id'] ?></td>
                                <td><?= htmlspecialchars($item['nome_item']) ?></td>
                                <td><?= htmlspecialchars($item['categoria']) ?></td>
                                <td><?= htmlspecialchars($item['descricao'] ?? '') ?></td>
                                <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="editar_item.php?id=<?= (int) $item['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                    <a href="excluir_item.php?id=<?= (int) $item['id'] ?>" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Excluir este item?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script><?= $script_alerta ?></script>
</body>
</html>
<?php mysqli_stmt_close($stmt); ?>
