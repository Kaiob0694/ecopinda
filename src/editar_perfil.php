<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include("../src/conexao.php");

$id = $_SESSION['usuario_id'];
$erros = [];
$sucesso = false;

// Busca os dados atuais do usuário
$sql = "SELECT nome, cpf, email, telefone, cep, foto FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$usuario) {
    header("Location: login.php");
    exit;
}

// Valores exibidos no formulário (começam com os dados atuais)
$nome     = $usuario['nome'];
$cpf      = $usuario['cpf'];
$email    = $usuario['email'];
$telefone = $usuario['telefone'];
$cep      = $usuario['cep'];
$foto     = $usuario['foto'];

// Pasta onde as fotos de perfil ficam salvas
$pastaUploads = __DIR__ . '/../assets/uploads/perfil/';
if (!is_dir($pastaUploads)) {
    mkdir($pastaUploads, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome         = trim($_POST['nome'] ?? '');
    $cpf          = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $telefone     = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
    $cep          = preg_replace('/\D/', '', $_POST['cep'] ?? '');
    $senha_nova   = trim($_POST['senha_nova'] ?? '');
    $senha_confirma = trim($_POST['senha_confirma'] ?? '');

    // Validações básicas
    if ($nome === '') {
        $erros[] = "O nome não pode ficar em branco.";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Informe um e-mail válido.";
    }

    if ($cpf !== '' && strlen($cpf) !== 11) {
        $erros[] = "O CPF deve conter 11 números.";
    }

    if ($cep !== '' && strlen($cep) !== 8) {
        $erros[] = "O CEP deve conter 8 números.";
    }

    if ($senha_nova !== '' && $senha_nova !== $senha_confirma) {
        $erros[] = "A confirmação de senha não confere.";
    }

    // ---------- FOTO DE PERFIL ----------
    $foto_nova = null; // nome do novo arquivo, se houver upload válido
    $enviou_foto = isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE;

    if ($enviou_foto) {

        $arquivo = $_FILES['foto'];

        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            $erros[] = "Ocorreu um erro ao enviar a foto. Tente novamente.";
        } elseif ($arquivo['size'] > 3 * 1024 * 1024) {
            $erros[] = "A foto deve ter no máximo 3MB.";
        } else {
            $extensoesPermitidas = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

            $tipoReal = mime_content_type($arquivo['tmp_name']);
            $imagemValida = getimagesize($arquivo['tmp_name']) !== false;

            if (!$imagemValida || !isset($extensoesPermitidas[$extensao]) || $tipoReal !== $extensoesPermitidas[$extensao]) {
                $erros[] = "Formato de imagem inválido. Use JPG, PNG ou WEBP.";
            } else {
                $foto_nova = 'user_' . $id . '_' . time() . '.' . $extensao;
            }
        }
    }

    // Garante que e-mail e CPF não pertencem a outro usuário
    if (empty($erros)) {
        $sqlCheck = "SELECT id FROM usuarios WHERE (email = ? OR (cpf = ? AND cpf <> '')) AND id != ?";
        $stmtCheck = mysqli_prepare($conexao, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "ssi", $email, $cpf, $id);
        mysqli_stmt_execute($stmtCheck);
        $resCheck = mysqli_stmt_get_result($stmtCheck);

        if (mysqli_fetch_assoc($resCheck)) {
            $erros[] = "Este e-mail ou CPF já está cadastrado em outra conta.";
        }
        mysqli_stmt_close($stmtCheck);
    }

    if (empty($erros)) {

        // Move o arquivo da foto para a pasta definitiva (só depois de garantir que não há erros)
        if ($foto_nova !== null) {
            move_uploaded_file($arquivo['tmp_name'], $pastaUploads . $foto_nova);
        }

        $foto_final = $foto_nova !== null ? $foto_nova : $foto;

        if ($senha_nova !== '') {
            $sqlUpdate = "UPDATE usuarios SET nome = ?, cpf = ?, email = ?, telefone = ?, cep = ?, foto = ?, senha = ? WHERE id = ?";
            $stmtUpdate = mysqli_prepare($conexao, $sqlUpdate);
            mysqli_stmt_bind_param(
                $stmtUpdate,
                "sssssssi",
                $nome,
                $cpf,
                $email,
                $telefone,
                $cep,
                $foto_final,
                $senha_nova,
                $id
            );
        } else {
            $sqlUpdate = "UPDATE usuarios SET nome = ?, cpf = ?, email = ?, telefone = ?, cep = ?, foto = ? WHERE id = ?";
            $stmtUpdate = mysqli_prepare($conexao, $sqlUpdate);
            mysqli_stmt_bind_param(
                $stmtUpdate,
                "ssssssi",
                $nome,
                $cpf,
                $email,
                $telefone,
                $cep,
                $foto_final,
                $id
            );
        }

        if (mysqli_stmt_execute($stmtUpdate)) {

            // Remove a foto antiga do disco se uma nova foi enviada
            if ($foto_nova !== null && !empty($foto) && file_exists($pastaUploads . $foto)) {
                unlink($pastaUploads . $foto);
            }

            $_SESSION['usuario_nome']  = $nome;
            $_SESSION['usuario_email'] = $email;
            $_SESSION['perfil_sucesso'] = "Perfil atualizado com sucesso!";
            mysqli_stmt_close($stmtUpdate);
            header("Location: ../pages/profile.php");
            exit;
        } else {
            $erros[] = "Erro ao atualizar o perfil: " . mysqli_error($conexao);
            mysqli_stmt_close($stmtUpdate);
        }
    }
}

// Iniciais do nome para o avatar (ex: "Kaio Silva" -> "KS")
function iniciais($nome) {
    $partes = preg_split('/\s+/', trim($nome));
    $iniciais = mb_substr($partes[0], 0, 1);
    if (count($partes) > 1) {
        $iniciais .= mb_substr(end($partes), 0, 1);
    }
    return $iniciais;
}

// Formata CPF/telefone/CEP para exibição no formulário
function formatarCpf($v) {
    $v = preg_replace('/\D/', '', $v ?? '');
    if (strlen($v) !== 11) return $v;
    return substr($v,0,3).'.'.substr($v,3,3).'.'.substr($v,6,3).'-'.substr($v,9,2);
}

function formatarTelefone($v) {
    $v = preg_replace('/\D/', '', $v ?? '');
    if (strlen($v) === 11) {
        return '('.substr($v,0,2).') '.substr($v,2,5).'-'.substr($v,7,4);
    }
    if (strlen($v) === 10) {
        return '('.substr($v,0,2).') '.substr($v,2,4).'-'.substr($v,6,4);
    }
    return $v;
}
?>

<?php include '../includes/head.php'; ?>

<link rel="stylesheet" href="../assets/css/profile.css">

<?php include '../includes/header.php'; ?>

<div class="profile-page">

<img src="../assets/img2/pindamonhangaba_cidade.jpeg" class="bg-photo" alt="fundo">
<div class="bg-overlay"></div>

<div class="cloud c1"></div>
<div class="cloud c2"></div>
<div class="cloud c3"></div>

<main class="profile-container">

    <div class="profile-card profile-card-form">

        <div class="edit-hero">
            <div class="avatar-upload-wrap">
                <div class="profile-avatar profile-avatar-lg">
                    <img id="preview-foto" src="<?= $foto ? '../assets/uploads/perfil/' . htmlspecialchars($foto) : '' ?>" style="<?= $foto ? '' : 'display:none;' ?>" alt="Foto de perfil">
                    <span id="preview-iniciais" style="<?= $foto ? 'display:none;' : '' ?>"><?= htmlspecialchars(iniciais($nome)) ?></span>
                </div>
                <label for="foto" class="avatar-camera-btn" title="Alterar foto">
                    <i class="fa-solid fa-camera"></i>
                </label>
                <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp" style="display:none;">
            </div>
            <h1>Editar Perfil</h1>
            <p><i class="fa-solid fa-leaf"></i> Atualize seus dados</p>
            <span class="foto-upload-info">JPG, PNG ou WEBP — máx. 3MB</span>
        </div>

        <?php if (!empty($erros)): ?>
            <div class="form-alerta form-alerta-erro">
                <ul>
                    <?php foreach ($erros as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="profile-form" enctype="multipart/form-data" novalidate>

            <div class="form-group">
                <label for="nome"><i class="fa-solid fa-user"></i> Nome</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="cpf"><i class="fa-solid fa-id-card"></i> CPF</label>
                    <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00" value="<?= htmlspecialchars(formatarCpf($cpf)) ?>">
                </div>
                <div class="form-group">
                    <label for="telefone"><i class="fa-solid fa-phone"></i> Telefone</label>
                    <input type="text" id="telefone" name="telefone" maxlength="15" placeholder="(00) 00000-0000" value="<?= htmlspecialchars(formatarTelefone($telefone)) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email"><i class="fa-solid fa-envelope"></i> E-mail</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <div class="form-group">
                <label for="cep"><i class="fa-solid fa-map-pin"></i> CEP</label>
                <input type="text" id="cep" name="cep" maxlength="9" placeholder="00000-000" value="<?= htmlspecialchars($cep) ?>">
            </div>

            <div class="form-divisor">
                <span>Alterar senha (opcional)</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="senha_nova"><i class="fa-solid fa-lock"></i> Nova senha</label>
                    <input type="password" id="senha_nova" name="senha_nova" placeholder="Deixe em branco" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="senha_confirma"><i class="fa-solid fa-lock"></i> Confirmar</label>
                    <input type="password" id="senha_confirma" name="senha_confirma" placeholder="Repita a senha" autocomplete="new-password">
                </div>
            </div>

            <div class="profile-buttons">
                <button type="submit" class="profile-btn profile-btn-edit">
                    <i class="fa-solid fa-check"></i> Salvar Alterações
                </button>
                <a href="../pages/profile.php" class="profile-btn profile-btn-exit">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </a>
            </div>

        </form>

    </div>

</main>

</div>

<script>
    const inputFoto = document.getElementById('foto');
    const previewFoto = document.getElementById('preview-foto');
    const previewIniciais = document.getElementById('preview-iniciais');

    inputFoto.addEventListener('change', function () {
        const arquivo = this.files[0];
        if (!arquivo) return;

        const leitor = new FileReader();
        leitor.onload = function (e) {
            previewFoto.src = e.target.result;
            previewFoto.style.display = 'block';
            previewIniciais.style.display = 'none';
        };
        leitor.readAsDataURL(arquivo);
    });
</script>

<?php include '../includes/footer.php'; ?>