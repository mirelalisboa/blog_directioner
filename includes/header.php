<!-- menu do site; e incluido por todas as paginas,
     entao os links ficam aqui em um lugar so -->
<header>

<?php
// esconde o link "Inicio" pra quem ja esta logado
// (a pagina inicial com o video sai do menu)

// so tenta iniciar a sessao se ainda nao saiu html,
// porque session_start() depois do html da erro
if (session_status() == PHP_SESSION_NONE) {
    if (headers_sent()) {
        error_log("Sessão não iniciada antes de includes/header.php — a página deve chamar session_start() antes de qualquer HTML.");
    } else {
        session_start();
    }
}

// ta logado? a chave 'id' e gravada no login
// e some quando sai da conta
$esta_logado = isset($_SESSION['id']);

// busca a foto de perfil pra mostrar no menu
// (clicar na foto abre o perfil). so roda se
// tiver sessao e conexao com o banco
$foto_menu   = null; // caminho da foto, quando existe
$nome_menu   = '';   // nome pro alt da imagem

if ($esta_logado && isset($conexao) && $conexao instanceof PDO) {
    $stmt_menu = $conexao->prepare(
        "SELECT name_sobrenome, foto_perfil
         FROM usuarios
         WHERE id = :id"
    );
    $stmt_menu->execute([':id' => $_SESSION['id']]);
    $perfil_menu = $stmt_menu->fetch(PDO::FETCH_ASSOC);

    if ($perfil_menu) {
        $nome_menu   = htmlspecialchars($perfil_menu['name_sobrenome'], ENT_QUOTES, 'UTF-8');
        if (!empty($perfil_menu['foto_perfil'])) {
            // mesma pasta usada pelo update.php;
            // rawurlencode protege o nome do arquivo na url
            $foto_menu = '/projetointegrador/imagens/perfis/'
                . rawurlencode($perfil_menu['foto_perfil']);
        }
    }
}
?>

    <nav>

        <div>

            <!-- css do site (o arquivo ainda nao existe) -->
            <link rel="stylesheet" href="../css/style.css">

            <!-- estilo da foto de perfil no menu -->
            <style>
                /* menu centralizado, a foto fica depois dos links */
                nav            { display: flex; justify-content: center; align-items: center; }
                .menu-perfil   { margin-left: 12px; text-decoration: none; }
                .menu-foto     { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; vertical-align: middle; }
                .foto-padrao   { font-size: 26px; line-height: 36px; vertical-align: middle; }
            </style>

<?php if (!$esta_logado): ?>
            <!-- quem nao esta logado so ve o inicio,
                 as outras paginas aparecem so no menu logado -->
            <a href="/projetointegrador/index.php"> Início </a>
<?php else: ?>
            <!-- pagina de noticias (blog + comentarios) -->
            <a href="/projetointegrador/pages/noticias.php"> Notícias |</a>

            <!-- albuns da banda -->
            <a href="/projetointegrador/pages/albuns.php"> Àlbuns |</a>

            <!-- albuns solo de cada integrante -->
            <a href="/projetointegrador/pages/albunssolos.php"> Álbuns solo |</a>

            <!-- fotos por periodo (linha do tempo) -->
            <a href="/projetointegrador/pages/linhadotempo.php"> Linhas do tempo | </a>
<?php endif; ?>

             <a href="/projetointegrador/pages/integrantes.php"> Integrantes| </a>

<?php if ($esta_logado): ?>
            <!-- a foto abre o perfil (como no app/perfil.php);
                 sem foto cadastrada aparece o emoji padrao -->
            <a class="menu-perfil" href="/projetointegrador/app/perfil.php" title="Ver meu perfil">
                <?php if ($foto_menu !== null): ?>
                    <img class="menu-foto"
                         src="<?php echo $foto_menu; ?>"
                         alt="Foto de perfil de <?php echo $nome_menu; ?>">
                <?php else: ?>
                    <span class="foto-padrao" aria-label="Abrir meu perfil">👤</span>
                <?php endif; ?>
            </a>
<?php endif; ?>

        </div>

    </nav>

</header>
