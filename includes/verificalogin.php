<!-- =====================================================
     includes/verificalogin.php — GUARDA DE SESSÃO (1 dos 2)
     O QUE FAZ: protege páginas que exigem login.
     POR QUÊ: usado com require_once no topo de páginas
     autenticadas (app/, pages/). Se ninguém estiver logado,
     redireciona ANTES de qualquer HTML para a home.
     IMPORTANTE: checa a chave $_SESSION['id'] — a MESMA chave
     gravada por login_user() no includes/functions.php.
     Sair no meio (header após HTML existente) causaria erro
     "headers already sent", por isso o guard fica no topo.
     ===================================================== -->
<?php

// Inicia a sessão se ainda não estiver iniciada.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sem 'id' na sessão = ninguém logado → volta para a home.
if (!isset($_SESSION['id'])) {
    header("Location: /projetointegrador/index.php");
    // exit() é obrigatório: sem ele o script continuaria
    // rodando e o conteúdo protegido seria exibido mesmo
    // depois do header de redirecionamento.
    exit();
}

?>
