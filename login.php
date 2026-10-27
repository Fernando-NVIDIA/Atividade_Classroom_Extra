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
    <title>Entrar - Café Grão Dourado</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-tela">
        <section class="login-lado">
            <img src="grao.svg" alt="" class="grao g1">
            <img src="grao.svg" alt="" class="grao g2">
            <img src="grao.svg" alt="" class="grao g3">
            <h1>Café Grão Dourado</h1>
            <p>Área reservada ao gerente. Entre para organizar o cardápio da casa.</p>
        </section>

        <section class="login-form-area">
            <form class="login-form" method="POST" action="login.php">
                <h2>Bem-vindo de volta</h2>
                <p class="sub">Informe seus dados de acesso.</p>

                <div class="campo">
                    <label for="usuario">Usuário</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="campo">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-principal">Entrar</button>
            </form>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script><?= $script_alerta ?></script>
</body>
</html>
