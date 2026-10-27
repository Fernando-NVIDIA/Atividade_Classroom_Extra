<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: painel.php");
    exit;
}

$script_alerta = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "conexao.php";

    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($usuario !== '' && $senha !== '') {
        $sql = "SELECT id, nome FROM usuarios WHERE usuario = ? AND senha = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $usuario, $senha);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            $dados = mysqli_fetch_assoc($resultado);
            mysqli_stmt_close($stmt);

            if ($dados) {
                $_SESSION['usuario_id'] = $dados['id'];
                $_SESSION['usuario_nome'] = $dados['nome'];
                header("Location: painel.php");
                exit;
            } else {
                $script_alerta = "Swal.fire({icon: 'error', title: 'Erro', text: 'Usuário ou senha inválidos.'});";
            }
        } else {
            $script_alerta = "Swal.fire({icon: 'error', title: 'Erro', text: 'Falha ao preparar a consulta.'});";
        }
    } else {
        $script_alerta = "Swal.fire({icon: 'warning', title: 'Atenção', text: 'Preencha usuário e senha.'});";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Cafeteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container" style="max-width: 400px; margin-top: 100px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="text-center mb-4">☕ Gestão da Cafeteria</h4>
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script><?= $script_alerta ?></script>
</body>
</html>
