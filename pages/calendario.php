<?php
// Se o seu projeto usa includes de header/footer nas outras páginas,
// inclua-os aqui no mesmo padrão, por exemplo:
// require __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Calendário de Eventos | Pinda Eco</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/locales-all.global.min.js"></script>

<!-- ajuste o caminho se o style_header.css / style.css do site ficar em outro lugar -->
<link rel="stylesheet" href="/style.css">
<link rel="stylesheet" href="/assets/css/calendario.css">
</head>
<body class="calendario-page">

<img class="bg-photo" src="/assets/img/fundo-floresta.jpg" alt="">
<div class="bg-overlay"></div>
<div class="cloud c1"></div>
<div class="cloud c2"></div>
<div class="cloud c3"></div>

<div class="calendario-container">
    <div class="calendario-card">

        <div class="calendario-header">
            <div class="calendario-header-info">
                <div class="calendario-icone"><i class="fa-solid fa-calendar-days"></i></div>
                <div>
                    <h1>Calendário de Eventos</h1>
                    <p><i class="fa-solid fa-leaf"></i> Pinda Eco</p>
                </div>
            </div>
            <button class="btn-novo-evento" id="btnNovoEvento">
                <i class="fa-solid fa-plus"></i> Novo evento
            </button>
        </div>

        <div class="calendario-corpo">
            <div id="calendar"></div>
        </div>

    </div>
</div>

<!-- Modal de criação/edição -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <h2 id="modalTitulo">Novo evento</h2>
        <input type="hidden" id="eventoId">

        <div class="campo">
            <label><i class="fa-solid fa-heading"></i> Título</label>
            <input type="text" id="campoTitulo" placeholder="Ex: Mutirão de plantio">
        </div>

        <div class="campo">
            <label><i class="fa-solid fa-align-left"></i> Descrição</label>
            <textarea id="campoDescricao" rows="2" placeholder="Detalhes do evento (opcional)"></textarea>
        </div>

        <div class="campo-linha">
            <div class="campo">
                <label><i class="fa-solid fa-clock"></i> Início</label>
                <input type="datetime-local" id="campoInicio">
            </div>
            <div class="campo">
                <label><i class="fa-solid fa-clock"></i> Fim</label>
                <input type="datetime-local" id="campoFim">
            </div>
        </div>

        <div class="campo">
            <label><i class="fa-solid fa-palette"></i> Cor</label>
            <input type="color" id="campoCor" value="#ff7a1a">
        </div>

        <div class="modal-botoes">
            <button class="btn btn-excluir" id="btnExcluir" style="display:none">
                <i class="fa-solid fa-trash"></i> Excluir
            </button>
            <div class="modal-botoes-direita">
                <button class="btn btn-cancelar" id="btnCancelar">Cancelar</button>
                <button class="btn btn-salvar" id="btnSalvar">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
const API_URL = '/api/events.php'; // caminho absoluto, pois esta página fica em /pages

const modalOverlay = document.getElementById('modalOverlay');
const btnSalvar = document.getElementById('btnSalvar');
const btnCancelar = document.getElementById('btnCancelar');
const btnExcluir = document.getElementById('btnExcluir');
const btnNovoEvento = document.getElementById('btnNovoEvento');

function abrirModal({ id = '', titulo = '', descricao = '', inicio = '', fim = '', cor = '#ff7a1a' }) {
    document.getElementById('eventoId').value = id;
    document.getElementById('campoTitulo').value = titulo;
    document.getElementById('campoDescricao').value = descricao;
    document.getElementById('campoInicio').value = inicio;
    document.getElementById('campoFim').value = fim;
    document.getElementById('campoCor').value = cor;
    document.getElementById('modalTitulo').textContent = id ? 'Editar evento' : 'Novo evento';
    btnExcluir.style.display = id ? 'inline-flex' : 'none';
    modalOverlay.classList.add('aberto');
}

function fecharModal() {
    modalOverlay.classList.remove('aberto');
}

btnCancelar.addEventListener('click', fecharModal);
btnNovoEvento.addEventListener('click', () => abrirModal({}));

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        editable: true,        // permite arrastar e redimensionar
        selectable: true,      // permite clicar/arrastar para criar
        events: API_URL,       // carrega eventos via GET

        // Clicar em um espaço vazio -> criar evento
        select: function (info) {
            abrirModal({
                inicio: formatarParaInput(info.start),
                fim: info.end ? formatarParaInput(info.end) : ''
            });
            calendar.unselect();
        },

        // Clicar em um evento existente -> editar
        eventClick: function (info) {
            const ev = info.event;
            abrirModal({
                id: ev.id,
                titulo: ev.title,
                descricao: ev.extendedProps.descricao || '',
                inicio: formatarParaInput(ev.start),
                fim: ev.end ? formatarParaInput(ev.end) : '',
                cor: ev.backgroundColor || '#ff7a1a'
            });
        },

        // Arrastar evento para outra data/hora -> atualizar automaticamente
        eventDrop: function (info) {
            salvarMovimentacao(info.event);
        },

        // Redimensionar evento -> atualizar automaticamente
        eventResize: function (info) {
            salvarMovimentacao(info.event);
        }
    });

    calendar.render();

    function salvarMovimentacao(ev) {
        fetch(`${API_URL}?id=${ev.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                data_inicio: formatarParaMySQL(ev.start),
                data_fim: ev.end ? formatarParaMySQL(ev.end) : null
            })
        }).catch(() => alert('Erro ao mover o evento.'));
    }

    btnSalvar.addEventListener('click', function () {
        const id = document.getElementById('eventoId').value;
        const corpo = {
            titulo: document.getElementById('campoTitulo').value,
            descricao: document.getElementById('campoDescricao').value,
            data_inicio: formatarParaMySQL(document.getElementById('campoInicio').value),
            data_fim: document.getElementById('campoFim').value ? formatarParaMySQL(document.getElementById('campoFim').value) : null,
            cor: document.getElementById('campoCor').value
        };

        if (!corpo.titulo || !corpo.data_inicio) {
            alert('Preencha ao menos o título e o início.');
            return;
        }

        const url = id ? `${API_URL}?id=${id}` : API_URL;
        const metodo = id ? 'PUT' : 'POST';

        fetch(url, {
            method: metodo,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(corpo)
        })
        .then(r => r.json())
        .then(() => {
            fecharModal();
            calendar.refetchEvents();
        })
        .catch(() => alert('Erro ao salvar o evento.'));
    });

    btnExcluir.addEventListener('click', function () {
        const id = document.getElementById('eventoId').value;
        if (!id || !confirm('Tem certeza que deseja excluir este evento?')) return;

        fetch(`${API_URL}?id=${id}`, { method: 'DELETE' })
            .then(r => r.json())
            .then(() => {
                fecharModal();
                calendar.refetchEvents();
            })
            .catch(() => alert('Erro ao excluir o evento.'));
    });
});

// --- Helpers de formatação de data ---
function formatarParaInput(data) {
    const d = new Date(data);
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

function formatarParaMySQL(data) {
    const d = new Date(data);
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:00`;
}
</script>

</body>
</html>