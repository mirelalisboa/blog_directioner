<!-- protege as paginas que exigem login: sem sessao, manda pra home -->
<?php

// inicia a sessao se ainda nao estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// sem 'id' na sessao = ninguem logado → volta pra home
if (!isset($_SESSION['id'])) {
    header("Location: /projetointegrador/index.php");
    // o exit() para o codigo aqui; sem ele a pagina
    // protegida apareceria mesmo depois do redirect
    exit();
}

?>
