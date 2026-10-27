<?php
session_start();

// Bloqueia quem não fez login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Guarda uma mensagem para ser exibida no próximo carregamento do painel
function definir_alerta($icone, $mensagem)
{
    $_SESSION['alerta'] = ['icon' => $icone, 'text' => $mensagem];
}
