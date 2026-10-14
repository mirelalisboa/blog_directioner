```php
<?php

// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Importa as funções e a conexão com o banco
require_once __DIR__ . '/../includes/functions.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe os dados do formulário
    $nome = trim($_POST['name_sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Verifica se os campos foram preenchidos
    if ($nome === '' || $email === '' || $senha === '') {
        exit('Preencha todos os campos.');
    }

    // Verifica se o e-mail é válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit('Digite um e-mail válido.');
    }

    // Cadastra o usuário no banco de dados
    $resultado = cadastra_user($conexao, $nome, $email, $senha);

    // Verifica se o cadastro foi realizado
    if ($resultado === true) {
        header('Location: cadastro.php');
        exit();
    } else {
        echo "Não foi possível cadastrar o usuário.";
        echo '<p><a href="login.php">Tentar novamente</a></p>';
    }
} else {
    echo "O formulário não foi enviado corretamente.";
}
?>
```