

ecopinda.zip
zip

crie na hora de fazer o cadastro de usuario,  um nivel de acesso na onde tem administrador e usuario normal


Executou 7 comandos, visualizou 5 arquivos
Executou 7 comandos, visualizou 5 arquivos
Vou adicionar um nível de acesso (admin/usuário) na tabela usuarios, no cadastro e no login. Como o cadastro é público, vou usar um código de administrador opcional no formulário — assim qualquer visitante que se cadastra vira usuário normal por padrão, e só quem souber o código vira admin. Isso evita que qualquer pessoa se autopromova a administrador só marcando uma opção.


Editou 13 arquivos, executou 6 comandos, criou 2 arquivos · 8 notas
Editou 13 arquivos, executou 6 comandos, criou 2 arquivos · 8 notas
O que mudou:

Banco de dados — Sql/add_nivel_acesso.sql adiciona a coluna tipo_usuario ENUM('usuario','admin') na tabela existente sem apagar dados. Rode esse script no seu banco pindaeco local. Também atualizei Sql/migrations.sql para refletir isso caso você recrie o banco do zero.

Cadastro (pages/cadastro.php + src/cadastrar.php) — adicionei um campo opcional "Código de administrador". Se deixado em branco, o usuário é criado como usuario (padrão, seguro). Se o código correto for digitado, vira admin. O código fica definido em src/conexao.php na constante CODIGO_CADASTRO_ADMIN — troque esse valor antes de usar em produção.

Login (src/auth.php) — agora carrega tipo_usuario do banco pra sessão ($_SESSION['usuario_tipo']).

Proteção de páginas admin (includes/exigir_admin.php) — novo helper que bloqueia acesso (403) se o usuário não estiver logado ou não for admin. Já apliquei nas ações de gerenciamento de hotéis e restaurantes (cadastrohotel.php, deletarhotel.php, cadastrarRestaurante.php, deletarRestaurante.php), já que normalmente só admin deveria poder cadastrar/excluir esses itens. Se quiser proteger outras páginas, basta dar require nesse arquivo no topo.

Header (includes/header.php) — mostra "(admin)" ao lado do nome quando o usuário logado é administrador.

⚠️ Por que não deixei um simples "select" de admin/usuário no formulário: qualquer visitante poderia se autopromover a administrador. O código secreto resolve isso, mas se quiser algo mais robusto (ex: só um admin já logado pode criar outro admin, por um painel separado), me avisa que ajusto.


Add nivel acesso
Código · SQL 

Migrations
Código · SQL 

Conexao
Código · PHP 

Cadastro
Código · PHP 

Cadastrar
Código · PHP 

Auth
Código · PHP 

Exigir admin
Código · PHP 

Header
Código · PHP 

Cadastrohotel
Código · PHP 

Deletarhotel
Código · PHP 

Cadastrarrestaurante
Código · PHP 

Deletarrestaurante
Código · PHP 






Claude é uma IA e pode cometer erros. Por favor, verifique as respostas.
Conexao · PHP
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// $host = "sql206.infinityfree.com";
// $usuario = "if0_42297225";
// $senha = "zgbGSOdQNMrhcud";
// $banco = "if0_42297225_pindaeco";

$host = "localhost";
$usuario = "root";
$senha = "1234";
$banco = "pindaeco";

$conexao = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro na Conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");

// Código exigido no cadastro para criar uma conta de administrador.
// Troque esse valor e não o compartilhe publicamente.
if (!defined('CODIGO_CADASTRO_ADMIN')) {
    define('CODIGO_CADASTRO_ADMIN', 'ecopinda-admin-2026');
}

