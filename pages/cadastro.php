<?php
session_start();

// Se já estiver logado, manda direto pro perfil
if (!empty($_SESSION['usuario_id'])) {
    header('Location: profile.php');
    exit;
}

$erros = $_SESSION['cadastro_erros'] ?? [];
unset($_SESSION['cadastro_erros']);

$dados_anteriores = $_SESSION['cadastro_dados'] ?? [];
unset($_SESSION['cadastro_dados']);

$nome     = $dados_anteriores['nome'] ?? '';
$cpf      = $dados_anteriores['cpf'] ?? '';
$email    = $dados_anteriores['email'] ?? '';
$telefone = $dados_anteriores['telefone'] ?? '';
$cep      = $dados_anteriores['cep'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Criar Conta</title>
<style>
  :root{
    --dusk-top:#3a3f7a;
    --dusk-mid:#8c6f9e;
    --dusk-glow:#e6a6b0;
    --glass-fill:rgba(255,255,255,0.10);
    --glass-border:rgba(255,255,255,0.35);
    --text:#f5f2f7;
    --text-dim:rgba(245,242,247,0.75);
    --laranja:#ff5100;
    --verde:#1f8a3d;
  }

  *{ box-sizing:border-box; }

  html,body{
    height:100%;
    margin:0;
    font-family:'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  }

  body{
    position:relative;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow-x:hidden;
    padding:40px 16px;
    background:linear-gradient(180deg, var(--dusk-top) 0%, var(--dusk-mid) 45%, var(--dusk-glow) 78%, #f3c9a8 100%);
  }

  .bg-photo{
    position:fixed;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:0;
  }

  .bg-overlay{
    position:fixed;
    inset:0;
    z-index:1;
    background:linear-gradient(180deg, rgba(20,15,40,0.15) 0%, rgba(15,10,30,0.35) 100%);
  }

  .cloud{
    position:fixed;
    z-index:2;
    border-radius:50%;
    background:rgba(255,255,255,0.18);
    filter:blur(18px);
    animation:drift 40s linear infinite;
  }
  .cloud.c1{ width:420px; height:120px; top:8%; left:-10%; animation-duration:55s; }
  .cloud.c2{ width:300px; height:90px;  top:18%; left:40%; opacity:.7; animation-duration:70s; animation-delay:-20s; }
  .cloud.c3{ width:500px; height:140px; top:2%;  left:60%; opacity:.5; animation-duration:90s; animation-delay:-40s; }

  @keyframes drift{
    from{ transform:translateX(0); }
    to{ transform:translateX(140vw); }
  }

  .card{
    position:relative;
    z-index:5;
    width:min(92vw, 440px);
    padding:44px 36px 36px;
    background:var(--glass-fill);
    border:1px solid var(--glass-border);
    border-radius:18px;
    backdrop-filter:blur(18px) saturate(140%);
    -webkit-backdrop-filter:blur(18px) saturate(140%);
    box-shadow:0 20px 60px rgba(10,8,30,0.45);
    text-align:center;
  }

  .card .logo{
    display:block;
    max-width:240px;
    max-height:150px;
    width:auto;
    height:auto;
    margin:0 auto 20px;
    filter:drop-shadow(0 2px 12px rgba(0,0,0,0.35));
  }

  .card .logo-fallback{
    display:none;
    margin:0 0 20px;
    font-size:24px;
    font-weight:700;
    letter-spacing:4px;
    color:var(--text);
    text-shadow:0 2px 12px rgba(0,0,0,0.35);
  }

  .card h2{
    margin:0 0 24px;
    color:var(--text);
    font-size:15px;
    font-weight:600;
    letter-spacing:2px;
    text-transform:uppercase;
    opacity:.85;
  }

  form{
    text-align:left;
  }

  .linha-dupla{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
  }

  .field{
    margin-bottom:14px;
    text-align:left;
  }

  .field label{
    display:block;
    margin-bottom:5px;
    color:var(--text-dim);
    font-size:12px;
    font-weight:600;
    letter-spacing:.03em;
  }

  .field input{
    width:100%;
    padding:12px 15px;
    background:rgba(255,255,255,0.08);
    border:1px solid var(--glass-border);
    border-radius:10px;
    color:var(--text);
    font-size:14px;
    outline:none;
    transition:background .2s ease, border-color .2s ease;
  }

  .field input::placeholder{ color:var(--text-dim); }

  .field input:focus{
    background:rgba(255,255,255,0.16);
    border-color:rgba(255,255,255,0.6);
  }

  .divisor{
    display:flex;
    align-items:center;
    gap:10px;
    margin:18px 0 14px;
    color:var(--text-dim);
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:.05em;
  }

  .divisor::before,
  .divisor::after{
    content:"";
    flex:1;
    height:1px;
    background:rgba(255,255,255,0.25);
  }

  .btn-cadastro{
    width:100%;
    margin-top:8px;
    padding:14px;
    border:none;
    border-radius:10px;
    background:rgba(255,255,255,0.85);
    color:#2a2440;
    font-size:15px;
    font-weight:600;
    letter-spacing:1px;
    cursor:pointer;
    transition:transform .15s ease, background .2s ease;
  }

  .btn-cadastro:hover{ background:#fff; transform:translateY(-1px); }
  .btn-cadastro:active{ transform:translateY(0); }

  .erro{
    margin:0 0 18px;
    padding:12px 14px;
    border-radius:8px;
    background:rgba(200,60,60,0.25);
    border:1px solid rgba(255,120,120,0.5);
    color:#ffe1e1;
    font-size:13px;
    text-align:left;
  }

  .erro ul{
    margin:0;
    padding-left:18px;
  }

  .voltar-login{
    display:block;
    margin-top:22px;
    text-decoration:none;
    color:var(--text);
    font-weight:600;
    font-size:13px;
    opacity:.9;
    transition:opacity .2s ease;
  }

  .voltar-login:hover{ opacity:1; text-decoration:underline; }

  @media (max-width:480px){
    .linha-dupla{
      grid-template-columns:1fr;
      gap:0;
    }
  }

  @media (prefers-reduced-motion: reduce){
    .cloud{ animation:none; }
  }
</style>
</head>
<body>

  <img src="../assets/img/fundo.png" alt="" class="bg-photo" onerror="this.style.display='none'">
  <div class="bg-overlay"></div>

  <div class="cloud c1"></div>
  <div class="cloud c2"></div>
  <div class="cloud c3"></div>

  <div class="card">
    <img src="../assets/img/logo_login.png" alt="Logo" class="logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
    <h2 class="logo-fallback">PINDA ECO</h2>
    <h2>Criar Conta</h2>

    <?php if (!empty($erros)): ?>
      <div class="erro">
        <ul>
          <?php foreach ($erros as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="../src/cadastrar.php" method="POST" novalidate>

      <div class="field">
        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" placeholder="Seu nome completo" value="<?= htmlspecialchars($nome) ?>" required autofocus>
      </div>

      <div class="linha-dupla">
        <div class="field">
          <label for="cpf">CPF</label>
          <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" value="<?= htmlspecialchars($cpf) ?>">
        </div>
        <div class="field">
          <label for="telefone">Telefone</label>
          <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" maxlength="15" value="<?= htmlspecialchars($telefone) ?>">
        </div>
      </div>

      <div class="field">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" value="<?= htmlspecialchars($email) ?>" required>
      </div>

      <div class="field">
        <label for="cep">CEP</label>
        <input type="text" id="cep" name="cep" placeholder="00000-000" maxlength="9" value="<?= htmlspecialchars($cep) ?>">
      </div>

      <div class="divisor">Senha</div>

      <div class="linha-dupla">
        <div class="field">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required>
        </div>
        <div class="field">
          <label for="senha_confirma">Confirmar senha</label>
          <input type="password" id="senha_confirma" name="senha_confirma" placeholder="Repita a senha" required>
        </div>
      </div>

      <div class="field">
        <label for="codigo_admin">Código de administrador (opcional)</label>
        <input type="password" id="codigo_admin" name="codigo_admin" placeholder="Deixe em branco se não tiver" autocomplete="off">
      </div>

      <button type="submit" class="btn-cadastro">Criar Conta</button>
    </form>

    <a href="login.php" class="voltar-login">Já tem uma conta? Entrar</a>
  </div>

</body>
</html>