<?php
// Copie este arquivo para conexao.php e preencha com os seus dados.
$host = "localhost";
$usuario_db = "root";
$senha_db = "SUA_SENHA_AQUI";
$banco = "gestao_cafeteria";

$conn = mysqli_connect($host, $usuario_db, $senha_db, $banco);

if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
