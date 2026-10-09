
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";

$usuario = consulta_user($conexao, $email);

if ($usuario && password_verify($senha, $usuario["senha"])) {

    session_regenerate_id(true);

    $_SESSION["id"] = $usuario["id"];
    $_SESSION["usuario"] = $usuario["name_sobrenome"];

    header("Location: ../noticias.php");
    exit();

} else {
    header("Location: login.php?erro=1");
    exit();
}

?>