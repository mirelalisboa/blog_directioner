<?php
// verifica_user.php: Verifica se há um usuário conectado e, caso não exista, redireciona a pessoa para a página inicial (index.php).

// Verifica se não há uma sessão iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se não há um usuário conectado pelo ID
if (!isset($_SESSION['id_usuario'])) {

    // Se não estiver conectado, redireciona para a página inicial.
    header("Location: ../index.php");
    exit();
}
