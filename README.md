# Blog Directioner — fan blog da banda One Direction, feito em PHP puro

O **Blog Directioner** é um site desenvolvido em PHP puro (sem framework, sem Composer) que funciona como um blog sobre a banda One Direction. Ele reúne páginas públicas com conteúdo sobre a banda — integrantes, álbuns, carreiras solos e linha do tempo, desde a época do *X Factor* até a fase atual — e uma área de blog interativa em que os usuários cadastrados podem publicar notícias, comentar, curtir e favoritar, além de uma área exclusiva de administração para gerenciar o conteúdo.

O projeto foi criado como **Projeto Integrador** (contexto acadêmico, SENAI) e é mantido em um servidor acessível apenas na rede local da escola. O destino são fãs da banda ("directioners"): o site combina um "fan site" informativo (com fotos e vídeos organizados por período da banda) com recursos de comunidade típicos de um blog. A documentação da proposta original está em `documentacao/documentacao.md`.

## 1. Tecnologias utilizadas

- **PHP:** linguagem principal do projeto — PHP puro (sem framework), responsável pelas páginas, sessões de login e toda a lógica do site.
- **PostgreSQL:** banco de dados que armazena usuários, notícias, comentários, curtidas, favoritos e álbuns; acessado via **PDO** (DSN `pgsql:`), centralizado em `database/connect.php`.
- **HTML5 e CSS3:** estrutura e estilização das páginas (o CSS fica embutido nas próprias páginas PHP, além do estilo vindo de `includes/header.php` e `includes/footer.php`).
- **Prepared statements + `password_hash()`:** padrões de segurança da aplicação (proteção contra SQL injection e hash de senhas) — aplicados em `includes/functions.php` e `login/`.
- **Git e GitHub:** controle de versão e armazenamento do código-fonte.

## 2. Funcionalidades do sistema

O sistema tem três áreas: **pública** (conteúdo aberto), **do usuário cadastrado** (exige login) e **administrativa** (exige conta com `tipo='admin'`).

### 2.1 Área pública (`index.php` e `pages/`)
- Página de abertura com vídeo de apresentação da banda (`index.php`).
- **Integrantes** (`pages/integrantes.php`): apresentação dos membros da banda.
- **Álbuns** (`pages/albuns.php`) e **Álbuns solos** (`pages/albunssolos.php`): discografia da banda e das carreiras individuais, com comentários por álbum.
- **Linha do tempo** (`pages/linhadotempo.php`): história da banda desde o *X Factor*.
- **Notícias** (`pages/noticias.php`): página central do blog, com vídeo, links externos de notícias e os posts vindos do banco de dados.
- **Galeria de fotos** organizada por período em `images/` (2010-11, 2012-13, 2014-15, 2016-atual), fotos de perfil em `imagens/perfis/` e vídeos da banda em `videos/video.mp4/` (os arquivos têm nomes legados, ex.: `videogotta.mp4.png.mp4` — não renomear, os `src` das páginas apontam para eles).

### 2.2 Área do usuário (login e `app/perfil.php`)
- **Cadastro de conta** e **login/logout** com sessão (`login/`), senhas armazenadas com `password_hash()`.
- **Meu perfil** (`app/perfil.php`): exibe nome, e-mail e foto da conta logada, com atalhos para atualizar dados/foto, sair da conta (`login/logout.php`) e excluir a conta.
- **Publicar notícia** no blog (`pages/noticias.php`).
- **Comentar** notícias (`comentarios`).
- **Curtir/descurtir** e **favoritar** notícias (toggle por par notícia + usuário, nas tabelas `curtidas` e `favoritos`).

### 2.3 Área administrativa (`app/admin.php` e `app/admin_login.php`)
- **Login próprio do administrador** (`app/admin_login.php`); páginas exigem sessão válida (`includes/verificalogin.php`) e a conta deve ter `tipo='admin'`, verificada por consulta ao banco.
- **CRUD completo de notícias**: cadastrar, listar, editar e excluir posts, com busca/exclusão para administração (implementado em `includes/functions.php` e `app/`).

## 3. Como executar o projeto

### 3.1 Pré-requisitos
- **PHP 7.4+ ou 8.x** com a extensão **PDO_pgsql** habilitada (o projeto usa DSN `pgsql:`).
- **PostgreSQL** em um servidor acessível. No projeto original o banco é hospedado na rede local da escola (`192.168.10.62`, banco `sitehtml`); para executar fora dessa rede, é preciso ter um servidor PostgreSQL próprio.
- **Git** (para clonar) e um cliente `psql` (para importar os scripts SQL).

### Passo 1: Clonar o repositório
```bash
git clone https://github.com/mirelalisboa/projetointegrador.git
cd projetointegrador
```

### Passo 2: Instalar as dependências
O projeto é PHP puro — **não há dependências** (não usa Composer nem build). Basta ter o PHP com a extensão `pdo_pgsql` instalada.

### Passo 3: Importar o banco de dados e configurar a conexão
Execute os scripts SQL do projeto **uma vez** no banco PostgreSQL (são idempotentes, com `IF NOT EXISTS`):
```bash
psql -h <host> -U <usuario> -d <banco> -f database/usuarios.sql
psql -h <host> -U <usuario> -d <banco> -f database/noticias.sql
psql -h <host> -U <usuario> -d <banco> -f database/albuns.sql
```

As credenciais de conexão ficam em `database/connect.php` (não há variáveis de ambiente). Edite as variáveis do topo desse arquivo para apontar para o seu servidor:

| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `$host` | Endereço do servidor PostgreSQL | `192.168.10.62` |
| `$dbname` | Nome do banco de dados | `sitehtml` |
| `$user` | Usuário do banco | `sitehtml` |
| `$pass` | Senha do banco | `1234` |

### Passo 4: Executar a aplicação
Os links internos do site usam caminhos absolutos com o prefixo **`/projetointegrador/`** (ex.: `/projetointegrador/pages/noticias.php`). Por isso, o projeto precisa ser servido sob esse prefixo: depois de clonar, renomeie a pasta `projeto.integrador2` para `projetointegrador` (ou faça um link com esse nome) dentro de um diretório pai, e rode o servidor a partir desse pai:
```bash
php -S 127.0.0.1:8000 <diretório_pai_que_contém_o_projeto_como_/projetointegrador>
```

Abra no navegador: **`http://127.0.0.1:8000/projetointegrador/index.php`**

> **Observações legadas** (comportamento atual do código): em `login/`, `login.php` na verdade **cria conta** e `cadastro.php` na verdade **faz login** — os nomes aparecem trocados na navegação real do site. Também é possível validar a sintaxe de arquivos PHP alterados com `php -l arquivo.php`.

## 4. Organização dos arquivos

```text
projeto.integrador2/
├── index.php                  # página de abertura com vídeo
├── app/                       # área do usuário e do admin (autenticada)
│   ├── admin.php              # CRUD de notícias (exige tipo='admin')
│   ├── admin_login.php        # login próprio do administrador
│   ├── perfil.php             # dados da conta logada
│   ├── create.php / update.php / delete.php
│   ├── select.php / select_w.php
├── database/
│   ├── connect.php            # conexão PDO (PostgreSQL)
│   ├── usuarios.sql           # tabela de usuários + admin padrão
│   ├── noticias.sql           # tabelas do blog + seed de notícias
│   └── albuns.sql             # tabelas dos álbuns + seed
├── documentacao/
│   └── documentacao.md        # especificação original do site
├── images/                    # fotos da banda por período
│   ├── 2010-11/ | 2012-13/ | 2014-15/ | 2016-atual/
├── imagens/perfis/            # fotos de perfil dos usuários
├── includes/
│   ├── functions.php          # biblioteca central (CRUD, login, sessão)
│   ├── header.php / footer.php  # menu e rodapé compartilhados
│   └── verificalogin.php      # guarda de sessão
├── login/
│   ├── cadastro.php           # faz login (nome legado, invertido)
│   ├── login.php              # cria conta (nome legado, invertido)
│   ├── verificauser.php       # guarda de sessão
│   ├── atualiza_perfil.php / logout.php
├── pages/                     # páginas públicas do blog
│   ├── albuns.php / albunssolos.php / integrantes.php
│   ├── linhadotempo.php / noticias.php
├── videos/                     # vídeos da banda
│   └── video.mp4/              # arquivos .mp4 (nomes legados: *.mp4.png.mp4 — não renomear)
└── README.md
```

## 5. Considerações finais

O Blog Directioner foi desenvolvido como Projeto Integrador, unindo conteúdo sobre a banda One Direction com exercícios práticos de desenvolvimento web em PHP, banco de dados relacional (PostgreSQL), sessões e autenticação. É um site legado, mantido no idioma português (pt-BR) no código e no conteúdo.

Como próximos passos, seriam naturais: centralizar o CSS hoje embutido nas páginas em um arquivo externo (`css/style.css` já é referenciado por `header.php`/`footer.php`), mover as credenciais do banco para variáveis de ambiente (hoje em `database/connect.php`) e normalizar a nomenclatura legada de `login/` — preservando a compatibilidade dos links existentes.

## Autoria

**Desenvolvido por Mirela ([github.com/mirelalisboa](https://github.com/mirelalisboa))**
Projeto Integrador desenvolvido em contexto acadêmico (SENAI).
