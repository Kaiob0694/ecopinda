/* ================================================================
   MURAL - JAVASCRIPT
================================================================ */

const modal = document.getElementById('uploadModal');
const fotoInput = document.getElementById('fotoInput');
const uploadArea = document.getElementById('uploadArea');
const formUpload = document.getElementById('formUpload');
const descricao = document.getElementById('descricao');
const charCount = document.getElementById('charCount');
const previewContainer = document.getElementById('previewContainer');
const fotoPreview = document.getElementById('fotoPreview');

/* ================================================================
   ABRIR/FECHAR MODAL
================================================================ */

function abrirUpload() {
    modal.classList.add('ativo');
    fotoInput.value = '';
    previewContainer.style.display = 'none';
    formUpload.reset();
    charCount.textContent = '0/500';
}

function fecharUpload() {
    modal.classList.remove('ativo');
}

// Fechar ao clicar fora do modal
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        fecharUpload();
    }
});

/* ================================================================
   UPLOAD AREA - DRAG AND DROP
================================================================ */

uploadArea.addEventListener('click', () => {
    fotoInput.click();
});

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = '#ff6b6b';
    uploadArea.style.background = 'rgba(255, 107, 107, 0.15)';
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.style.borderColor = 'rgba(255, 107, 107, 0.4)';
    uploadArea.style.background = 'rgba(255, 107, 107, 0.05)';
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = 'rgba(255, 107, 107, 0.4)';
    uploadArea.style.background = 'rgba(255, 107, 107, 0.05)';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fotoInput.files = files;
        processarFoto();
    }
});

/* ================================================================
   INPUT DE FOTO
================================================================ */

fotoInput.addEventListener('change', processarFoto);

function processarFoto() {
    const file = fotoInput.files[0];
    
    if (!file) {
        previewContainer.style.display = 'none';
        return;
    }

    // Validar tipo
    const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!tiposPermitidos.includes(file.type)) {
        alert('Tipo de arquivo não permitido. Use JPG, PNG, WEBP ou GIF');
        fotoInput.value = '';
        previewContainer.style.display = 'none';
        return;
    }

    // Validar tamanho (5MB)
    const tamanhoMaximo = 5 * 1024 * 1024;
    if (file.size > tamanhoMaximo) {
        alert('Arquivo muito grande. Máximo 5MB');
        fotoInput.value = '';
        previewContainer.style.display = 'none';
        return;
    }

    // Mostrar preview
    const reader = new FileReader();
    reader.onload = (e) => {
        fotoPreview.src = e.target.result;
        previewContainer.style.display = 'flex';
    };
    reader.readAsDataURL(file);
}

function limparFoto() {
    fotoInput.value = '';
    previewContainer.style.display = 'none';
}

/* ================================================================
   DESCRIÇÃO - CONTADOR DE CARACTERES
================================================================ */

descricao.addEventListener('input', () => {
    const total = descricao.value.length;
    charCount.textContent = `${total}/500`;
});

/* ================================================================
   ENVIAR FORMULÁRIO
================================================================ */

formUpload.addEventListener('submit', async (e) => {
    e.preventDefault();

    const file = fotoInput.files[0];
    if (!file) {
        alert('Selecione uma foto');
        return;
    }

    // Desabilitar botão
    const btnEnviar = formUpload.querySelector('.botao-enviar');
    btnEnviar.disabled = true;
    const textOriginal = btnEnviar.textContent;
    btnEnviar.textContent = 'Enviando...';

    // Criar FormData
    const formData = new FormData();
    formData.append('foto', file);
    formData.append('descricao', descricao.value);

    try {
        const response = await fetch('upload.php', {
            method: 'POST',
            body: formData
        });

        const resultado = await response.json();

        if (resultado.sucesso) {
            // Sucesso
            fecharUpload();
            window.location.href = 'read.php?sucesso=1';
        } else {
            alert('Erro: ' + resultado.mensagem);
            btnEnviar.disabled = false;
            btnEnviar.textContent = textOriginal;
        }

    } catch (erro) {
        console.error('Erro:', erro);
        alert('Erro ao enviar foto: ' + erro.message);
        btnEnviar.disabled = false;
        btnEnviar.textContent = textOriginal;
    }
});

/* ================================================================
   FECHAR MODAL COM ESC
================================================================ */

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('ativo')) {
        fecharUpload();
    }
});