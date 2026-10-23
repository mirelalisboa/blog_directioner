<!-- segunda protecao por login: redireciona pra ../index.php se nao estiver logado -->
<?php
// inicia a sessao antes de ler $_SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// sem 'id' na sessao → volta pra pagina inicial
// (o ../ sobe pra raiz do projeto porque quem inclui
// esse arquivo fica em subpastas)
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
