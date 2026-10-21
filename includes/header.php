<!-- =====================================================
     includes/header.php — CABEÇALHO / MENU GLOBAL DO SITE
     O QUE FAZ: exibe o menu de navegação principal.
     POR QUÊ: sendo incluído (include) por todas as páginas,
     o menu fica em UM lugar só — arrumar um link aqui corrige
     em todas as páginas de uma vez (sem duplicar código).
     DETALHE: praticamente todos os links são ABSOLUTOS,
     com o prefixo /projetointegrador/, porque o site é
     servido com esse prefixo virtual no servidor da escola.
     ===================================================== -->
<header>

<?php
// ------------------------------------------------------------------
// Esconde o link "Início" para quem já está logado.
// POR QUÊ (pedido do dono do site): depois do login/cadastro,
// a página inicial (com o vídeo de abertura) sai do menu.
// ------------------------------------------------------------------
// Sessão ativa? Só tenta iniciar se NÃO saiu output ainda
// (session_start() após HTML gera warning "after headers have
// already been sent" — cabe à página iniciar a sessão antes,
// como exige o padrão do projeto; caso o header seja incluído
// depois de output por uma página futura, avisamos no log em
// vez de sujar a tela com warning).
if (session_status() == PHP_SESSION_NONE) {
    if (headers_sent()) {
        error_log("Sessão não iniciada antes de includes/header.php — a página deve chamar session_start() antes de qualquer HTML.");
    } else {
        session_start();
    }
}

// Sessão logada? A chave 'id' é gravada por login_user()
// (includes/functions.php) no login e no cadastro.
// Ao sair da conta (logout.php / delete.php), a chave some
// e o link "Início" volta a aparecer.
$esta_logado = isset($_SESSION['id']);

// ------------------------------------------------------------------
// Foto de perfil da pessoa logada, para o menu: o "Perfil" não é
// link-texto — clicar na foto de perfil abre o perfil (pedido do
// dono do site). Consulta só quando há sessão E conexão ($conexao
// existe porque todas as páginas requerem functions.php antes do
// header; index.php não, então cai no emoji padrão encapsulado).
// ------------------------------------------------------------------
$foto_menu   = null; // caminho absoluto da foto, quando existe
$nome_menu   = '';   // nome para o alt/title do link

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
            // Mesma pasta Padronizada pelo app/update.php, com
            // rawurlencode protege espaços/símbolos do nome do arquivo.
            $foto_menu = '/projetointegrador/imagens/perfis/'
                . rawurlencode($perfil_menu['foto_perfil']);
        }
    }
}
?>

    <nav>

        <div>

            <!--
                 Folha de estilos do site (ainda não criada no repo —
                 ver pendência em AGENTS.md).
            -->
            <link rel="stylesheet" href="../css/style.css">

            <!-- Estilo próprio do item "foto de perfil" do menu
                 (enquanto css/style.css não existe; sem duplicar o
                 que já é usado dentro das páginas de perfil). -->
            <style>
                /* Menu centralizado na página: links e a foto de perfil
                   formam um único grupo no meio, a foto logo após o
                   último link (pedido do dono do site). */
                nav            { display: flex; justify-content: center; align-items: center; }
                .menu-perfil   { margin-left: 12px; text-decoration: none; }
                .menu-foto     { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; vertical-align: middle; }
                .foto-padrao   { font-size: 26px; line-height: 36px; vertical-align: middle; }
            </style>

<?php if (!$esta_logado): ?>
            <!-- Visitante (sem sessão): NÃO pode entrar no site
                 pelo header (pedido do dono do site). O único link
                 é o "Início" (página de abertura) — Notícias,
                 Àlbuns, Álbuns solo e Linhas do tempo aparecem
                 apenas para quem está logado. -->
            <a href="/projetointegrador/index.php"> Início </a>
<?php else: ?>
            <!-- Página com as notícias externas + blog e comentários -->
            <a href="/projetointegrador/pages/noticias.php"> Notícias |</a>

            <!-- Página com os álbuns da banda -->
            <a href="/projetointegrador/pages/albuns.php"> Àlbuns |</a>

            <!-- Página com os álbuns solo de cada integrante -->
            <a href="/projetointegrador/pages/albunssolos.php"> Álbuns solo |</a>

            <!-- Página com as fotos por período -->
            <a href="/projetointegrador/pages/linhadotempo.php"> Linhas do tempo | </a>
<?php endif; ?>

             <a href="/projetointegrador/pages/integrantes.php"> Integrantes| </a>

<?php if ($esta_logado): ?>
            <!-- Perfil da pessoa logada: NÃO é link-texto; clicar na
                 foto de perfil abre o perfil (pedido do dono do site,
                 o mesmo comportamento de app/perfil.php e app/update.php).
                 Fica na MESMA LINHA dos outros links (dentro do mesmo
                 div) com um espaçamento maior à esquerda. Sem foto
                 cadastrada, aparece o emoji padrão 👤 de app/perfil.php
                 — continua clicável. -->
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
