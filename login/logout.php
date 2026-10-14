
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpa os dados da sessão.
$_SESSION = [];

// Encerra a sessão.
session_destroy();

// Volta para a página de login.
header("Location: cadastro.php");
exit();

?>

