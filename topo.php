<?php
// Estrutura compartilhada das páginas internas (menu lateral)
$titulo_pagina = $titulo_pagina ?? 'Painel';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo_pagina) ?> - Café Grão Dourado</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app">
    <aside class="lateral">
        <div class="marca"><img src="grao.svg" alt="" class="grao-mini">Grão <span>Dourado</span></div>
        <a href="painel.php" class="menu-item ativo">Cardápio</a>
        <div class="rodape-lateral">
            <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong>
            <a href="logout.php">Sair</a>
        </div>
    </aside>
    <main class="conteudo">
