# Blog Directioner 🎤 - Fan Blog da Banda One Direction

O Blog Directioner é um site desenvolvido em PHP puro (sem framework e sem Composer) que funciona como um blog sobre a banda One Direction. Ele reúne páginas públicas com conteúdo sobre a banda (integrantes, álbuns, carreiras solos e linha do tempo, da época do *X Factor* até a fase atual) e uma área de blog interativa, na qual usuários cadastrados podem publicar notícias, comentar, curtir e favoritar. Há ainda uma área exclusiva de administração para gerenciar o conteúdo.

O projeto foi criado como **Projeto Integrador** (contexto acadêmico, SENAI) e é mantido em um servidor acessível apenas na rede local da escola. O destino são os fãs da banda ("directioners"): o site combina um fan site informativo (com fotos e vídeos organizados por período da banda) com recursos de comunidade típicos de um blog. A documentação da proposta original está em `documentacao/documentacao.md`.

Sistema: Blog Directioner
Contexto: Projeto Integrador (SENAI)

---
# 1 Introdução
## 1.1 Propósito
Este documento descreve os requisitos funcionais, não funcionais e as regras de negócio do Blog Directioner, voltado aos fãs da banda One Direction.

## 1.2 Escopo
O sistema oferece conteúdo público aberto a qualquer visitante. Usuários cadastrados podem criar conta, fazer login, publicar notícias, comentar, curtir e favoritar. O administrador possui login próprio e gerencia as notícias do blog (cadastrar, listar, editar e excluir).


# 2 Descrição

## 2.1 Funções Principais
O sistema permite que o **visitante**:

- Assista ao vídeo de apresentação da banda
- Conheça os integrantes
- Veja os álbuns da banda e os álbuns solos, com comentários por álbum
- Acompanhe a linha do tempo da banda
- Leia as notícias do blog
- Veja a galeria de fotos e os vídeos da banda

O sistema permite que o **usuário **:

- Crie uma conta
- Faça login e logout
- Veja o próprio perfil (nome, e-mail e foto)
- Atualize os dados e a foto do perfil
- Exclua a própria conta
- Publique notícias no blog
- Comente notícias
- Curta e descurta notícias
- Favorite e desfavorite notícias

O sistema permite que o **administrador**:

- Faça login próprio na área administrativa
- Cadastre, liste, edite e exclua notícias
- Busque notícias para administração

## 2.2 Galeria de mídia
- Fotos da banda organizadas por período em `images/` (2010-11, 2012-13, 2014-15 e 2016-atual)
- Fotos de perfil dos usuários em `imagens/perfis/`
- Vídeos da banda em `videos/video.mp4/` (os arquivos têm nomes legados, como `videogotta.mp4.png.mp4`, e **não devem ser renomeados**)

## 2.3 Classes de usuários

| **TIPO DE USER** | **DESCRIÇÃO** | **PERMISSÕES** |
| --- | --- | --- |
| Visitante | Pessoa sem login | Não Tem Acesso As Páginas Do Site. |
| Usuário cadastrado | Pessoa com conta criada e logada em uma sessão | Consegue, publicar, comentar, curtir e favoritar notícias e gerenciar o próprio perfil |
| Administrador | Conta com `tipo='admin'` | Gerencia as notícias do blog (CRUD completo) pela área administrativa |

## Tecnologias Utilizadas

- **PHP:** linguagem principal do projeto, em PHP puro (sem framework), responsável pelas páginas, sessões de login e toda a lógica do site
- **PostgreSQL:** banco de dados que armazena usuários, notícias, comentários, curtidas, favoritos e álbuns; acessado via **PDO** (DSN `pgsql:`), com a conexão centralizada em `database/connect.php`
- **HTML5 e CSS3:** estrutura e estilização das páginas (o CSS fica embutido nas próprias páginas PHP, além do estilo vindo de `includes/header.php` e `includes/footer.php`)
- **Prepared statements + `password_hash()`:** padrões de segurança da aplicação (proteção contra SQL injection e hash de senhas), aplicados em `includes/functions.php` e na pasta `login/`
- **Git e GitHub:** controle de versão e armazenamento do código-fonte


# 3 Requisitos e Regras de Negócio

## 3.1 Definições e acrônimos
| **TERMO** | **DEFINIÇÕES** |
| --- | --- |
| CRUD | Sigla representativa para Create, Read, Update and Delete |
| PDO | PHP Data Objects, interface de acesso ao banco de dados |
| RF | Requisito Funcional |
| RNF | Requisito Não Funcional |
| RN | Regra de Negócio |
| Directioner | Fã da banda One Direction |

## 3.2 Requisitos Funcionais (RF)
- **RF-001 (Cadastro de Conta):** Permite ao visitante criar uma conta de usuário.
- **RF-002 (Login e Logout):** Permite ao usuário entrar e sair do sistema por meio de sessão.
- **RF-003 (Perfil):** Exibe nome, e-mail e foto da conta logada, com atalhos para atualizar dados e foto, sair da conta e excluir a conta.
- **RF-004 (Publicar Notícia):** Permite ao usuário logado publicar notícias no blog.
- **RF-005 (Comentários):** Permite ao usuário logado comentar notícias.
- **RF-006 (Curtir):** Permite curtir e descurtir uma notícia.
- **RF-007 (Favoritar):** Permite favoritar e desfavoritar uma notícia.
- **RF-008 (Conteúdo Público):** Falta De Disponibilidade Para Aqueles Que Não Possuem Login.
- **RF-009 (Comentários por Álbum):** Permite comentar nos álbuns da banda e dos integrantes.
- **RF-010 (Login do Administrador):** Disponibiliza login próprio para o administrador em `app/admin_login.php`.
- **RF-011 (CRUD de Notícias):** Permite ao administrador cadastrar, listar, editar e excluir notícias, com busca e exclusão para administração.
- **RF-012 (Menu e Rodapé):** Disponibiliza menu de navegação e rodapé compartilhados entre as páginas (`includes/header.php` e `includes/footer.php`).

## 3.3 Requisitos Não-Funcionais (RNF)
- **RNF-001 (Segurança de Senhas):** As senhas devem ser armazenadas com `password_hash()`.
- **RNF-002 (Proteção contra SQL Injection):** O acesso ao banco deve utilizar prepared statements via PDO.
- **RNF-003 (Controle de Sessão):** As páginas restritas devem exigir sessão válida (`includes/verificalogin.php`).
- **RNF-004 (Compatibilidade):** O sistema requer PHP 7.4+ ou 8.x com a extensão PDO_pgsql habilitada.
- **RNF-005 (Idioma):** Código e conteúdo são mantidos em português (pt-BR).

## 3.4 Regras de Negócio (RN)
- **RN-001 (Acesso Administrativo):** Só acessa a área administrativa quem possui conta com `tipo='admin'`, verificado por consulta ao banco.
- **RN-002 (Curtidas e Favoritos):** Curtidas e favoritos funcionam como alternância (toggle) por par notícia + usuário, registrados nas tabelas `curtidas` e `favoritos`.
- **RN-003 (Ações Restritas):** Publicar, comentar, curtir e favoritar exigem usuário logado.
- **RN-004 (Prefixo de Rotas):** Os links internos usam caminhos absolutos com o prefixo `/projetointegrador/` (ex.: `/projetointegrador/pages/noticias.php`); por isso, o projeto precisa ser servido sob esse prefixo.

- Você pode acessar a **Documentação completa:** 👉 `documentacao/documentacao.md`

---
# 4 Estrutura do projeto

```text
projeto.integrador/
├── index.php                  # página de abertura com vídeo
├── app/                       # área do usuário e do admin (autenticada)
│   ├── admin.php              # CRUD de notícias (exige tipo='admin')
│   ├── admin_login.php        # login próprio do administrador
│   ├── perfil.php             # dados da conta logada
│   ├── create.php / update.php / delete.php
│   └── select.php / select_w.php
├── database/
│   ├── connect.php            # conexão PDO (PostgreSQL)
│   ├── usuarios.sql           # tabela de usuários + admin padrão
│   ├── noticias.sql           # tabelas do blog + de notícias
│   └── albuns.sql             # tabelas dos álbuns
├── documentacao/
│   └── documentacao.md        # especificação original do site
├── images/                    # fotos da banda por período
│   └── 2010-11/ | 2012-13/ | 2014-15/ | 2016-atual/
├── imagens/perfis/            # fotos de perfil dos usuários
├── includes/
│   ├── functions.php          # biblioteca central (CRUD, login, sessão)
│   ├── header.php / footer.php  # menu e rodapé compartilhados
│   └── verificalogin.php      # guarda de sessão
├── login/
│   ├── cadastro.php           # faz login (nome legado, invertido)
│   ├── login.php              # cria conta (nome legado, invertido)
│   ├── verificauser.php       # guarda de sessão
│   └── atualiza_perfil.php / logout.php
├── pages/                     # páginas públicas do blog
│   ├── albuns.php / albunssolos.php / integrantes.php
│   └── linhadotempo.php / noticias.php
├── videos/                    # vídeos da banda
│   └── video.mp4/             # arquivos .mp4 (nomes legados: *.mp4.png.mp4 — não renomear)
└── README.md
```

---
# 5 COMO EXECUTAR A APLICAÇÃO

### Requisitos

- PHP 7.4+ ou 8.x, com a extensão `PDO_pgsql` habilitada
- PostgreSQL em um servidor acessível. No projeto original, o banco é hospedado na rede local da escola (`192.168.10.62`, banco `sitehtml`); para executar fora dessa rede, é preciso ter um servidor PostgreSQL próprio
- Git (para clonar) e um cliente `psql` (para importar os scripts SQL)
- Navegador Web

---

### 1. Obter os arquivos do projeto
Abra o Terminal, Prompt de Comando ou Git Bash e navegue até a pasta onde deseja salvar o projeto:

```bash
cd caminho/pasta_desejada
```

Clone o repositório:

```bash
git clone https://github.com/mirelalisboa/projetointegrador.git
```

Entre na pasta do projeto:

```bash
cd projetointegrador
```

### 2. Criar o usuário e o banco de dados
Junto aos arquivos do projeto está o dump do banco de dados (`sitehtml.sql`), que será usado para criar as tabelas.

Conecte-se ao PostgreSQL de acordo com o seu sistema operacional:

- **Linux/macOS:**
```bash
sudo -u postgres psql
```

- **Windows (Prompt/PowerShell):**
```bash
psql -U postgres
```

Crie o banco, o usuário e ajuste o dono do banco, conforme a tabela:

| Variável | Descrição | Exemplo |
|----------|-----------|---------|
| `$dbname` | Nome do banco de dados | `sitehtml` |
| `$user` | Usuário do banco | `sitehtml` |
| `$pass` | Senha do banco | `1234` |

```sql
CREATE DATABASE sitehtml;

CREATE USER sitehtml WITH PASSWORD '1234';

ALTER DATABASE sitehtml OWNER TO sitehtml;
```

Confira se o banco e o usuário foram criados:

```sql
\l
```

```sql
\du
```

Saia do PostgreSQL:

```sql
\q
```

### 3. Restaurar o banco de dados
Importe o arquivo de backup para criar as tabelas iniciais:

```bash
psql -U sitehtml -d sitehtml -f backup.sql
```

Quando solicitado, informe a senha do usuário `sitehtml`.

### 4. Conferir a conexão da aplicação.

- Configurar a conexão da aplicação

No arquivo database/connect.php, configure os dados do banco local. O arquivo deve ter este formato:
```bash
<?php
define('DB_HOST', 'localhost'); // adicione o IP da sua maquina no localhost
define('DB_PORT', '5432');  // porta padrão utilizada, NÃO DEXE SER ALTERADA
define('DB_NAME', 'sitehtml');  // nome do banco de dados criado
define('DB_USER', 'sitehtml');   // nome do usuário criado
define('DB_PASS', '1234');  // senha do banco de dados
?>
```
após a alteração devida SALVE o arquivo.


### 5. Iniciar a aplicação
Com o terminal aberto na pasta raiz do projeto, execute:

Quando solicitado, informe a senha do usuário sitehtml.



```bash
php -S 0.0.0.0:8000
```

### 6. Acessar o sistema
Abra o navegador e acesse:

```text
http://localhost:8000
```

> Lembre-se de que os links internos usam o prefixo `/projetointegrador/` (veja a RN-004); o projeto precisa ser servido sob esse prefixo.

Agora você está pronto para explorar o aplicativo!

---
# 6 Conclusão

O Blog Directioner foi desenvolvido como Projeto Integrador, unindo conteúdo sobre a banda One Direction com exercícios práticos de desenvolvimento web em PHP, banco de dados relacional (PostgreSQL), sessões e autenticação. É um site legado, mantido em português (pt-BR) no código e no conteúdo.

---
## Autoria
Desenvolvido por Mirela ([github.com/mirelalisboa](https://github.com/mirelalisboa))

Projeto Integrador desenvolvido em contexto acadêmico (SENAI).

©2010-forever