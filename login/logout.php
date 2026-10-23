<!-- desloga a pessoa e volta pra pagina inicial -->
<?php
// so pode sair quem esta logado (o arquivo acima ja checa)
require_once __DIR__ . '/../login/verificauser.php';

// garante a sessao ativa pra poder destruir ela
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// limpa tudo que a sessao guardava (id, usuario, email...)
$_SESSION = [];

// encerra a sessao no servidor
session_destroy();

// volta pra pagina inicial, ja deslogado
header("Location: /projetointegrador/index.php");
exit();
