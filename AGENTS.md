# AGENTS.md — projeto.integrador2

Site legado em PHP puro (blog One Direction), sem Composer, sem testes e sem build. Português (pt-BR) é o idioma do código.

## Papel do agente

Corrigir erros de execução/sintaxe/lógica, caminhos e duplicação, **sem alterar a estrutura de pastas nem a organização dos arquivos**. Mudanças mínimas; sempre ler o arquivo completo antes de editar. Ao final, listar cada correção (arquivo, trecho, motivo). Problemas que exijam mudar estrutura → registrar como pendência, não executar.

## Estrutura (imutável — não criar/mover/renomear/excluir arquivos)

- `index.php` — página inicial (HTML + include de `includes/`)
- `app/` — páginas autenticadas: `admin.php` (área do admin, exige `tipo='admin'`), `admin_login.php` (login próprio do admin), `create.php`, `delete.php`, `perfil.php`, `select.php`, `select_w.php`, `update.php`
- `database/connect.php` — conexão PDO; define `$conexao` no escopo global. **Nenhum output antes do `<?php`** neste arquivo (quebraria `header()`/`session_start()` de quem o requer).
- `includes/functions.php` — biblioteca central: CRUD de usuários (`usuarios`), blog (`noticias`, `comentarios`), curtidas/favoritos (`curtidas`, `favoritos` — par único notícia+usuário), login/sessão, guardas e funções de admin.
- `includes/` (outros) — `header.php` (menu; esconde "Início" p/ logados), `footer.php`, `verificalogin.php` (guarda de sessão).
- `login/` — `login.php`, `cadastro.php`, `atualiza_perfil.php`, `logout.php`, `verificauser.php`
- `pages/` — páginas públicas do blog: `albuns.php`, `albunssolos.php`, `integrantes.php`, `linhadotempo.php`, `noticias.php`
- `documentacao/` — especificação do site; `videos/` e `images/` — mídia

Novos arquivos só se estritamente necessários e justificados.

## Banco de dados

- **PostgreSQL, não MySQL** — o DSN é `pgsql:` (verificado em `database/connect.php`). Host `192.168.10.62`, banco `sitehtml` — acessível apenas na rede local do projeto.
- **Sempre use `$conexao`**: inclua `includes/functions.php` (caminho relativo via `__DIR__ . '/../...'`), pois ele carrega `connect.php` e deixa `$conexao` disponível no escopo global. Incluir `connect.php` sozinho retorna a conexão, mas o padrão do projeto é via `functions.php`.
- Prepared statements (bindValue/array) sempre que houver dados do usuário; `password_hash`/`password_verify` para senha.

## Caminhos (fonte de erros frequente)

- **`require`/`include`: usar sempre `__DIR__ . '/../pasta/arquivo.php'`**, resolvido a partir da pasta do arquivo que inclui.
- **Links absolutos `href`/`Location`: começam em `/projetointegrador/...`** (ex.: `/projetointegrador/pages/noticias.php`) — o site é servido com prefixo virtual `/projetointegrador/`; servir de outro docroot quebra os links.
- **Logo** — removida a pedido do dono do site (o header exibe só o menu). Caso volte a ser necessária, a versão anterior era um SVG inline com `fill="currentColor"`/`var(--logo-color)` em `includes/header.php`.
- Quirks verificados (legado; preservar comportamento, registrar pendência se necessário):
  - `login/login.php` na real **cria conta**; `login/cadastro.php` na real **faz login** (nomes trocados no código).
  - **`includes/header.php`** esconde o link "Início" quando existe sessão logada (`isset($_SESSION['id'])`) — pedido do dono do site; o perfil (`app/perfil.php`) tem botões de volta para todas as páginas de `pages/`.
  - `css/style.css` é referenciado por `header.php`/`footer.php` mas ainda não existe (404 inofensivo até você criar o arquivo).
  - `pages/noticias.php` incluiu `pages/contato.php`, que nunca existiu (includes já removidos; criar o arquivo só se você quiser uma página de contato).
  - `includes/functions.php` → `verifica_user()` agora redireciona para `/projetointegrador/login/cadastro.php` (a página real de entrar).
  - Vídeos têm nomes reais como `videos/video.mp4/videoone.mp4.png.mp4.mp4` — não "normalizar".
- Não "corrigir" os nomes de arquivo trocados por conta própria: mantê-los e usar o mapeamento acima.

## Como verificar

```bash
# lint de um arquivo alterado
php -l arquivo.php

# servidor de desenvolvimento (o projeto precisa ficar exposto sob o prefixo /projetointegrador)
php -S 127.0.0.1:8000 <docroot-pai-que-exponha-o-projeto-como-/projetointegrador>
```

- Valide `php -l` em todos os `.php` alterados; teste as páginas no navegador se tiver acesso ao banco/servidor (`192.168.10.62`).
- Erros comuns a checar: `$conexao` indefinido (esqueceu de incluir `functions.php`), função chamada antes do `require` que a define (ex.: `app/create.php` chama `verifica_user()` **antes** de requerer `functions.php`), `header("Location: ...")` após output já enviado, includes de arquivos inexistentes.

## Segurança (mínimo)

- Prepared statements para toda consulta com dado do usuário.
- `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` na saída de dados do banco/usuário.
- Nunca remover nem burlar as verificações de `includes/verificalogin.php` (checa `$_SESSION['id']`) e `login/verificauser.php` em páginas autenticadas.
- **Atenção, incompatibilidade verificada**: `login_user()` (em `functions.php`) grava `$_SESSION['id']`, mas `login/verificauser.php` checa `$_SESSION['id_usuario']`, que ninguém grava — ou seja, `verificauser.php` sempre redireciona, a menos que outra verificação (`verificalogin.php`) já tenha liberado a página. Ao mexer em fluxo de login/logout, distinguir qual guarda cada página usa; não tente "unificar" as chaves de sessão sem autorização.

## Checklist final

1. Estrutura de pastas/arquivos intacta; nenhum arquivo renomeado/movido.
2. `php -l` passou em todos os arquivos editados.
3. Todos os `require`/`include`/`href`/`Location` conferidos a partir da pasta do arquivo que os usa.
4. Duplicação centralizada em `includes/functions.php` (ou `database/connect.php`), sem mudar comportamento visível.
5. Prepared statements + `htmlspecialchars` mantidos.
6. Correções documentadas (arquivo, trecho, motivo); pendências estruturais anotadas para o usuário.
