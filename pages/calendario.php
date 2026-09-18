<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Calendário de Eventos</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/locales-all.global.min.js"></script>

<style>
    body {
        font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        margin: 0;
        padding: 24px;
        background: #f4f5f7;
    }
    h1 {
        font-size: 20px;
        margin-bottom: 16px;
        color: #222;
    }
    #calendar-container {
        max-width: 1000px;
        margin: 0 auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 20px;
    }

    /* --- Modal --- */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-overlay.aberto { display: flex; }
    .modal {
        background: #fff;
        border-radius: 10px;
        padding: 24px;
        width: 100%;
        max-width: 380px;
    }
    .modal h2 { margin-top: 0; font-size: 18px; }
    .modal label { display: block; margin-top: 12px; font-size: 13px; color: #555; }
    .modal input, .modal textarea {
        width: 100%;
        padding: 8px;
        margin-top: 4px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 14px;
    }
    .modal-botoes { margin-top: 20px; display: flex; justify-content: space-between; gap: 8px; }
    .btn { border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; font-size: 14px; }
    .btn-salvar { background: #3788d8; color: #fff; }
    .btn-excluir { background: #e74c3c; color: #fff; }
    .btn-cancelar { background: #eee; color: #333; }
</style>
</head>
<body>

<h1>📅 Calendário de Eventos</h1>

<div id="calendar-container">
    <div id="calendar"></div>
</div>

<!-- Modal de criação/edição -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <h2 id="modalTitulo">Novo evento</h2>
        <input type="hidden" id="eventoId">

        <label>Título</label>
        <input type="text" id="campoTitulo" placeholder="Ex: Reunião com cliente">

        <label>Descrição</label>
        <textarea id="campoDescricao" rows="2"></textarea>

        <label>Início</label>
        <input type="datetime-local" id="campoInicio">

        <label>Fim (opcional)</label>
        <input type="datetime-local" id="campoFim">

        <label>Cor</label>
        <input type="color" id="campoCor" value="#3788d8">

        <div class="modal-botoes">
            <button class="btn btn-excluir" id="btnExcluir" style="display:none">Excluir</button>
            <div>
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

function abrirModal({ id = '', titulo = '', descricao = '', inicio = '', fim = '', cor = '#3788d8' }) {
    document.getElementById('eventoId').value = id;
    document.getElementById('campoTitulo').value = titulo;
    document.getElementById('campoDescricao').value = descricao;
    document.getElementById('campoInicio').value = inicio;
    document.getElementById('campoFim').value = fim;
    document.getElementById('campoCor').value = cor;
    document.getElementById('modalTitulo').textContent = id ? 'Editar evento' : 'Novo evento';
    btnExcluir.style.display = id ? 'inline-block' : 'none';
    modalOverlay.classList.add('aberto');
}

function fecharModal() {
    modalOverlay.classList.remove('aberto');
}

btnCancelar.addEventListener('click', fecharModal);

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        initialView: 'dayGridMonth',
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
                cor: ev.backgroundColor || '#3788d8'
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
