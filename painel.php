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
$sql = "SELECT id, nome_item, categoria, descricao, preco FROM cardapio ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$itens = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Resumo e classe de cor por categoria
$total = count($itens);
$contagem = ['Café' => 0, 'Doce' => 0, 'Salgado' => 0, 'Bebida Gelada' => 0];
foreach ($itens as $i) {
    if (isset($contagem[$i['categoria']])) {
        $contagem[$i['categoria']]++;
    }
}
$classes = ['Café' => 'cafe', 'Doce' => 'doce', 'Salgado' => 'salgado', 'Bebida Gelada' => 'gelada'];

$titulo_pagina = "Cardápio";
require "topo.php";
?>

<header>
    <h1>Cardápio</h1>
    <p>Cadastre, ajuste e retire itens da casa.</p>
</header>

<div class="resumo">
    <div class="bloco"><small>Total de itens</small><b><?= $total ?></b></div>
    <?php foreach ($contagem as $nome_cat => $qtd): ?>
        <div class="bloco"><small><?= htmlspecialchars($nome_cat) ?></small><b><?= $qtd ?></b></div>
    <?php endforeach; ?>
</div>

<!-- C - Cadastrar -->
<section class="secao">
    <h2>Novo item</h2>
    <form method="POST" action="salvar_item.php" class="grade-form">
        <div class="campo">
            <label for="nome_item">Nome do item</label>
            <input type="text" id="nome_item" name="nome_item" required>
        </div>
        <div class="campo">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria" required>
                <option value="">Selecione...</option>
                <option value="Café">Café</option>
                <option value="Doce">Doce</option>
                <option value="Salgado">Salgado</option>
                <option value="Bebida Gelada">Bebida Gelada</option>
            </select>
        </div>
        <div class="campo">
            <label for="preco">Preço (R$)</label>
            <input type="text" id="preco" name="preco" placeholder="0,00" required>
        </div>
        <div class="campo inteira">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="2"></textarea>
        </div>
        <div class="inteira acoes-form">
            <button type="submit" class="btn btn-principal">Adicionar ao cardápio</button>
        </div>
    </form>
</section>

<!-- R - Listar -->
<section class="secao">
    <h2>Itens cadastrados</h2>
    <div class="tabela-area">
        <table class="cardapio">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total === 0): ?>
                    <tr><td colspan="6" class="vazio">Nenhum item cadastrado ainda.</td></tr>
                <?php endif; ?>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= (int) $item['id'] ?></td>
                        <td class="nome"><?= htmlspecialchars($item['nome_item']) ?></td>
                        <td>
                            <span class="etiqueta <?= $classes[$item['categoria']] ?? '' ?>">
                                <?= htmlspecialchars($item['categoria']) ?>
                            </span>
                        </td>
                        <td class="desc"><?= htmlspecialchars($item['descricao'] ?? '') ?></td>
                        <td class="preco">R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td class="acoes">
                            <a href="editar_item.php?id=<?= (int) $item['id'] ?>" class="btn btn-claro btn-pequeno">Editar</a>
                            <a href="excluir_item.php?id=<?= (int) $item['id'] ?>" class="btn btn-perigo btn-pequeno"
                               onclick="return confirm('Excluir este item?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require "rodape.php"; ?>
