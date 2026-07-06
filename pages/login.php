<?php
session_start();

// Se já estiver logado, manda direto pro dashboard
if (!empty($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = $_SESSION['login_erro'] ?? '';
unset($_SESSION['login_erro']);
$email_anterior = $_SESSION['login_email'] ?? '';
unset($_SESSION['login_email']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Entrar</title>
<style>
  :root{
    --dusk-top:#3a3f7a;
    --dusk-mid:#8c6f9e;
    --dusk-glow:#e6a6b0;
    --mountain:#232849;
    --mountain-far:#463a5e;
    --glass-fill:rgba(255,255,255,0.10);
    --glass-border:rgba(255,255,255,0.35);
    --text:#f5f2f7;
    --text-dim:rgba(245,242,247,0.75);
  }

  *{ box-sizing:border-box; }

  html,body{
    height:100%;
    margin:0;
    font-family:'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  }

  body{
    position:relative;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    /* fallback em gradiente caso a imagem não carregue */
    background:linear-gradient(180deg, var(--dusk-top) 0%, var(--dusk-mid) 45%, var(--dusk-glow) 78%, #f3c9a8 100%);
  }

  .bg-photo{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:0;
  }

  /* leve véu escuro sobre a foto pra manter o texto branco legível */
  .bg-overlay{
    position:absolute;
    inset:0;
    z-index:1;
    background:linear-gradient(180deg, rgba(20,15,40,0.15) 0%, rgba(15,10,30,0.35) 100%);
  }

  /* céu com leve textura de nuvem */
  .cloud{
    position:absolute;
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
    width:min(90vw, 380px);
    padding:48px 36px 40px;
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
    max-width:280px;
    max-height:190px;
    width:auto;
    height:auto;
    margin:0 auto 30px;
    filter:drop-shadow(0 2px 12px rgba(0,0,0,0.35));
  }

  /* fallback textual caso a imagem da logo não exista */
  .card .logo-fallback{
    display:none;
    margin:0 0 30px;
    font-size:28px;
    font-weight:700;
    letter-spacing:6px;
    color:var(--text);
    text-shadow:0 2px 12px rgba(0,0,0,0.35);
  }

  .field{
    margin-bottom:16px;
    text-align:left;
  }

  .field input{
    width:100%;
    padding:13px 16px;
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

  .btn-login{
    width:100%;
    margin-top:10px;
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

  .btn-login:hover{ background:#fff; transform:translateY(-1px); }
  .btn-login:active{ transform:translateY(0); }

  .erro{
    margin:0 0 18px;
    padding:10px 12px;
    border-radius:8px;
    background:rgba(200,60,60,0.25);
    border:1px solid rgba(255,120,120,0.5);
    color:#ffe1e1;
    font-size:13px;
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
    <h1 class="logo-fallback">LOGIN</h1>

    <?php if ($erro): ?>
      <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="process_login.php" method="POST" novalidate>
      <div class="field">
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email_anterior) ?>" required autofocus>
      </div>
      <div class="field">
        <input type="password" name="senha" placeholder="Senha" required>
      </div>
      <button type="submit" class="btn-login">Entrar</button>
    </form>
  </div>

</body>
</html>