<?php
require_once "auth.php";
require_once "conexao.php";

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM cardapio WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
            definir_alerta('success', 'Item excluído com sucesso!');
        } else {
            definir_alerta('error', 'Não foi possível excluir o item.');
        }
        mysqli_stmt_close($stmt);
    } else {
        definir_alerta('error', 'Falha ao preparar a consulta.');
    }
} else {
    definir_alerta('warning', 'ID inválido.');
}

header("Location: painel.php");
exit;
