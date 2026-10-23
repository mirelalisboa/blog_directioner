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
- **Galeria de fotos** organizada por período em `images/` (2010-11, 2012-13, 2014-15, 2016-atual), fotos de perfil em `imagens/perfis/` e vídeos da banda em `videos/video.mp4/` (os arquivos têm nomes legados, ex.: `videogotta.mp4.png.mp4`.

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

```


### Passo 2:
Abra o Terminal, Prompt de Comando ou Git Bash no seu computador e navegue até a pasta onde deseja salvar o projeto:

```bash
cd caminho/pasta_desejada
```

Execute o comando git clone:

```bash
git clone https://github.com/mirelalisboa/projetointegrador.git
```
Acesse a pasta

```bash
cd /var/www/html/

```

Entre no diretório do projeto, colocando o caminho para entrar na pasta do site:

```bash
cd projetointegrador
```

Passo 3: Criar e configurar o Banco de Dados

Juntamente aos arquivos do projeto, você tem acesso ao dump do banco de dados sitehtml.sql, você usará ele neste momento. Siga as instruções abaixo:

Abra o Terminal, Prompt de Comando ou PowerShell.

Conecte-se ao PostgreSQL de acordo com seu sistema operacional:

Conecte-se ao PostgreSQL de acordo com seu sistema operacional:

Linux/macOS (Terminal):
```bash
sudo -u postgres psql
```

Windows (Prompt/PowerShell):
```bash
psql -U postgres
```


Rode os comandos abaixo para criar o usuário 'sitehtml', definir a senha '1234', criar o banco e ajustar o dono do banco:

| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `$dbname` | Nome do banco de dados | `sitehtml` |
| `$user` | Usuário do banco | `sitehtml` |
| `$pass` | Senha do banco | `1234` |


```bash
CREATE DATABASE site html;

CREATE USER sitehtml COM SENHA '1234'; 

CRIAR BANCO DE DADOS sitehtml PROPRIETÁRIO sitehtml; 

ALTER DATABASE sitehtml OWNER TO sitehtml;
```

Verifique se tudo ocorreu bem (banco de dados e usuário criados)

Verificação do banco de dados:

```bash
\l

```

- Verificação dos usuários:


```bash

\du # Lista básica

 ou

\du+ # Lista detalhada

```


Saia do terminal do PostgreSQL:

```bash


\q

```


Agora, importe o arquivo de backup para criar as tabelas iniciais:

```bash


psql -U sitehtml -d sitehtml -f backup.sql

```




Após configurar o banco de dados e a conexão, inicialize o servidor. Com o seu terminal aberto na pasta raiz do projeto, execute o comando abaixo:
php -S 0.0.0.0:8000
Deste modo, o projeto estará acessível no seu navegador através do endereço http://localhost:8000.
Agora você está pronto para explorar o aplicativo!

Os links internos do site usam caminhos absolutos com o prefixo **`/projetointegrador/`** (ex.: `/projetointegrador/pages/noticias.php`). Por isso, o projeto precisa ser servido sob esse prefixo.

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
│   ├── noticias.sql           # tabelas do blog + de notícias
│   └── albuns.sql             # tabelas dos álbuns 
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


## Autoria

©2010-forever

**Desenvolvido por Mirela ([github.com/mirelalisboa](https://github.com/mirelalisboa))**
Projeto Integrador desenvolvido em contexto acadêmico (SENAI).
