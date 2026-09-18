<?php
session_start();
$ehMaster = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'master';

// Se o seu projeto usa includes de header/footer nas outras páginas,
// inclua-os aqui no mesmo padrão, por exemplo:
// require __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Eventos | Pinda Eco</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- FullCalendar via jsdelivr -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales-all.global.min.js"></script>

<!-- ajuste o caminho se o style.css do site ficar em outro lugar -->
<link rel="stylesheet" href="/style.css">
<link rel="stylesheet" href="/assets/css/calendario.css">
</head>
<body>

<div class="eventos-container">
    <div class="eventos-conteudo">

        <!-- FILTROS -->
        <div class="filtros-barra">
            <div class="filtro-busca">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="filtroBusca" placeholder="Buscar eventos...">
            </div>

            <div class="filtro-select">
                <i class="fa-solid fa-filter"></i>
                <select id="filtroCategoria">
                    <option value="">Todas</option>
                </select>
                <i class="fa-solid fa-chevron-down"></i>
            </div>

            <div class="filtro-select">
                <i class="fa-solid fa-globe"></i>
                <select id="filtroFormato">
                    <option value="">Todas</option>
                    <option value="Presencial">Presencial</option>
                    <option value="Online">Online</option>
                </select>
                <i class="fa-solid fa-chevron-down"></i>
            </div>

            <div class="filtro-select">
                <i class="fa-solid fa-location-dot"></i>
                <select id="filtroLocal">
                    <option value="">Todas</option>
                </select>
                <i class="fa-solid fa-chevron-down"></i>
            </div>

            <button class="filtro-passados" id="btnPassados">Passados</button>
        </div>

        <!-- CABEÇALHO -->
        <div class="eventos-topo">
            <div class="eventos-topo-esquerda">
                <h1 class="eventos-titulo">Próximos eventos</h1>
                <span class="eventos-contador" id="eventosContador">0 encontrados</span>
            </div>

            <div class="eventos-acoes-topo">
                <div class="eventos-toggle">
                    <button id="btnVistaLista" class="ativo"><i class="fa-solid fa-table-cells"></i> Lista</button>
                    <button id="btnVistaCalendario"><i class="fa-solid fa-calendar-days"></i> Calendário</button>
                </div>

                <?php if ($ehMaster): ?>
                <button class="eventos-botao-novo" id="btnNovoEvento">
                    <i class="fa-solid fa-plus"></i> Novo evento
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- VISTA LISTA -->
        <div class="vista-lista" id="vistaLista">
            <div class="eventos-grid" id="eventosGrid">
                <!-- cards inseridos via JS -->
            </div>
        </div>

        <!-- VISTA CALENDÁRIO -->
        <div class="vista-calendario" id="vistaCalendario">
            <div class="calendario-card">
                <div id="calendar"></div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL DE VISUALIZAÇÃO (todos os usuários) -->
<div class="modal-overlay" id="modalVisualizarOverlay">
    <div class="modal modal-visualizar">
        <img class="evento-view-imagem" id="viewImagem" src="" alt="" style="display:none">
        <h2 id="viewTitulo">Evento</h2>
        <div class="evento-view-meta">
            <span><i class="fa-solid fa-calendar"></i> <span id="viewData"></span></span>
            <span><i class="fa-solid fa-clock"></i> <span id="viewHora"></span></span>
            <span id="viewLocalWrap"><i class="fa-solid fa-location-dot"></i> <span id="viewLocal"></span></span>
        </div>
        <p id="viewDescricao"></p>
        <div class="modal-botoes">
            <div></div>
            <div class="modal-botoes-direita">
                <button class="btn btn-cancelar" id="btnFecharVisualizar">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php if ($ehMaster): ?>
<!-- MODAL DE CRIAÇÃO/EDIÇÃO (apenas master) -->
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
            <textarea id="campoDescricao" rows="2" placeholder="Detalhes do evento"></textarea>
        </div>

        <div class="campo">
            <label><i class="fa-solid fa-image"></i> URL da imagem</label>
            <input type="text" id="campoImagem" placeholder="https://...">
        </div>

        <div class="campo">
            <label><i class="fa-solid fa-location-dot"></i> Local</label>
            <input type="text" id="campoLocal" placeholder="Ex: Shopping Pátio Pinda">
        </div>

        <div class="campo-linha">
            <div class="campo">
                <label><i class="fa-solid fa-tag"></i> Categoria</label>
                <input type="text" id="campoCategoria" placeholder="Ex: Evento, Show">
            </div>
            <div class="campo">
                <label><i class="fa-solid fa-globe"></i> Formato</label>
                <select id="campoFormatoInput">
                    <option value="Presencial">Presencial</option>
                    <option value="Online">Online</option>
                </select>
            </div>
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

        <div class="campo-linha">
            <div class="campo">
                <label><i class="fa-solid fa-palette"></i> Cor</label>
                <input type="color" id="campoCor" value="#ff7a1a">
            </div>
            <div class="campo">
                <label><i class="fa-solid fa-ticket"></i> Gratuito?</label>
                <select id="campoGratuito">
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
                </select>
            </div>
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
<?php endif; ?>

<script>
const API_URL = '/api/events.php';
const EH_MASTER = <?php echo $ehMaster ? 'true' : 'false'; ?>;

let TODOS_EVENTOS = [];
let mostrandoPassados = false;
let vistaAtual = 'lista';
let calendarInstance = null;

const grid = document.getElementById('eventosGrid');
const contador = document.getElementById('eventosContador');
const filtroBusca = document.getElementById('filtroBusca');
const filtroCategoria = document.getElementById('filtroCategoria');
const filtroFormato = document.getElementById('filtroFormato');
const filtroLocal = document.getElementById('filtroLocal');
const btnPassados = document.getElementById('btnPassados');

// --- Carregar eventos ---
function carregarEventos() {
    fetch(API_URL)
        .then(r => r.json())
        .then(dados => {
            TODOS_EVENTOS = dados.eventos || [];
            popularFiltros();
            renderizarLista();
            if (calendarInstance) calendarInstance.refetchEvents();
        })
        .catch(() => {
            grid.innerHTML = '<div class="eventos-vazio"><h3>Não foi possível carregar os eventos</h3><p>Tente novamente em instantes.</p></div>';
        });
}

function popularFiltros() {
    const categorias = [...new Set(TODOS_EVENTOS.map(e => e.categoria).filter(Boolean))];
    const locais = [...new Set(TODOS_EVENTOS.map(e => e.local).filter(Boolean))];

    filtroCategoria.innerHTML = '<option value="">Todas</option>' +
        categorias.map(c => `<option value="${c}">${c}</option>`).join('');

    filtroLocal.innerHTML = '<option value="">Todas</option>' +
        locais.map(l => `<option value="${l}">${l}</option>`).join('');
}

function eventosFiltrados() {
    const agora = new Date();
    const busca = filtroBusca.value.trim().toLowerCase();
    const categoria = filtroCategoria.value;
    const formato = filtroFormato.value;
    const local = filtroLocal.value;

    return TODOS_EVENTOS.filter(ev => {
        const dataEvento = new Date(ev.start);
        const ehPassado = dataEvento < agora;

        if (mostrandoPassados !== ehPassado) return false;
        if (busca && !(`${ev.title} ${ev.extendedProps.descricao || ''}`.toLowerCase().includes(busca))) return false;
        if (categoria && ev.categoria !== categoria) return false;
        if (formato && ev.formato !== formato) return false;
        if (local && ev.local !== local) return false;

        return true;
    }).sort((a, b) => new Date(a.start) - new Date(b.start));
}

function formatarDataBR(dataStr) {
    const d = new Date(dataStr);
    return d.toLocaleDateString('pt-br', { day: '2-digit', month: 'long' });
}

function formatarHoraBR(dataStr) {
    const d = new Date(dataStr);
    return d.toLocaleTimeString('pt-br', { hour: '2-digit', minute: '2-digit' });
}

function renderizarLista() {
    const eventos = eventosFiltrados();
    contador.textContent = `${eventos.length} encontrado${eventos.length === 1 ? '' : 's'}`;

    if (eventos.length === 0) {
        grid.innerHTML = '<div class="eventos-vazio"><h3>Nenhum evento encontrado</h3><p>Tente ajustar os filtros de busca.</p></div>';
        return;
    }

    grid.innerHTML = eventos.map(ev => {
        const imagemHtml = ev.imagem
            ? `<img class="evento-imagem" src="${ev.imagem}" alt="${ev.title}">`
            : `<div class="evento-imagem-placeholder"><i class="fa-solid fa-image"></i></div>`;

        const localHtml = ev.local
            ? `<span class="evento-local"><i class="fa-solid fa-location-dot"></i><span>${ev.local}</span></span>`
            : '<span></span>';

        const acoesMaster = EH_MASTER ? `
            <div class="evento-acoes-master">
                <button class="evento-editar" onclick="editarEvento(${ev.id})"><i class="fa-solid fa-pen"></i> Editar</button>
                <button class="evento-excluir" onclick="excluirEvento(${ev.id})"><i class="fa-solid fa-trash"></i> Excluir</button>
            </div>` : '';

        return `
        <div class="evento-card">
            <div class="evento-imagem-container">
                ${imagemHtml}
                ${ev.categoria ? `<span class="evento-badge-categoria">${ev.categoria}</span>` : ''}
                ${ev.gratuito ? `<span class="evento-badge-gratuito">Grátis</span>` : ''}
            </div>
            <div class="evento-info">
                <div class="evento-datahora">
                    <span><i class="fa-solid fa-calendar"></i> ${formatarDataBR(ev.start)}</span>
                    <span><i class="fa-solid fa-clock"></i> ${formatarHoraBR(ev.start)}</span>
                </div>
                <h3 class="evento-titulo">${ev.title}</h3>
                <p class="evento-descricao">${ev.extendedProps.descricao || ''}</p>
                <div class="evento-footer">
                    ${localHtml}
                    <button class="evento-ver-mais" onclick="visualizarEvento(${ev.id})">Ver mais <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
            ${acoesMaster}
        </div>`;
    }).join('');
}

// --- Filtros: eventos ---
[filtroBusca, filtroCategoria, filtroFormato, filtroLocal].forEach(el => {
    el.addEventListener('input', renderizarLista);
    el.addEventListener('change', renderizarLista);
});

btnPassados.addEventListener('click', () => {
    mostrandoPassados = !mostrandoPassados;
    btnPassados.classList.toggle('ativo', mostrandoPassados);
    btnPassados.textContent = mostrandoPassados ? 'Próximos' : 'Passados';
    renderizarLista();
});

// --- Alternância Lista / Calendário ---
const btnVistaLista = document.getElementById('btnVistaLista');
const btnVistaCalendario = document.getElementById('btnVistaCalendario');
const vistaLista = document.getElementById('vistaLista');
const vistaCalendario = document.getElementById('vistaCalendario');

btnVistaLista.addEventListener('click', () => alternarVista('lista'));
btnVistaCalendario.addEventListener('click', () => alternarVista('calendario'));

function alternarVista(vista) {
    vistaAtual = vista;
    btnVistaLista.classList.toggle('ativo', vista === 'lista');
    btnVistaCalendario.classList.toggle('ativo', vista === 'calendario');
    vistaLista.classList.toggle('oculta', vista !== 'lista');
    vistaCalendario.classList.toggle('ativa', vista === 'calendario');

    if (vista === 'calendario' && !calendarInstance) {
        inicializarCalendario();
    }
}

// --- Modal de visualização (todos os usuários) ---
const modalVisualizarOverlay = document.getElementById('modalVisualizarOverlay');
document.getElementById('btnFecharVisualizar').addEventListener('click', () => {
    modalVisualizarOverlay.classList.remove('aberto');
});

function visualizarEvento(id) {
    const ev = TODOS_EVENTOS.find(e => e.id === id);
    if (!ev) return;

    const imgEl = document.getElementById('viewImagem');
    if (ev.imagem) {
        imgEl.src = ev.imagem;
        imgEl.style.display = 'block';
    } else {
        imgEl.style.display = 'none';
    }

    document.getElementById('viewTitulo').textContent = ev.title;
    document.getElementById('viewData').textContent = formatarDataBR(ev.start);
    document.getElementById('viewHora').textContent = formatarHoraBR(ev.start);
    document.getElementById('viewDescricao').textContent = ev.extendedProps.descricao || '';

    const localWrap = document.getElementById('viewLocalWrap');
    if (ev.local) {
        localWrap.style.display = 'inline-flex';
        document.getElementById('viewLocal').textContent = ev.local;
    } else {
        localWrap.style.display = 'none';
    }

    modalVisualizarOverlay.classList.add('aberto');
}

<?php if ($ehMaster): ?>
// --- Modal de criação/edição (apenas master) ---
const modalOverlay = document.getElementById('modalOverlay');
const btnSalvar = document.getElementById('btnSalvar');
const btnCancelar = document.getElementById('btnCancelar');
const btnExcluir = document.getElementById('btnExcluir');
const btnNovoEvento = document.getElementById('btnNovoEvento');

function abrirModal(ev = null) {
    document.getElementById('eventoId').value = ev ? ev.id : '';
    document.getElementById('campoTitulo').value = ev ? ev.title : '';
    document.getElementById('campoDescricao').value = ev ? (ev.extendedProps.descricao || '') : '';
    document.getElementById('campoImagem').value = ev ? (ev.imagem || '') : '';
    document.getElementById('campoLocal').value = ev ? (ev.local || '') : '';
    document.getElementById('campoCategoria').value = ev ? (ev.categoria || '') : 'Evento';
    document.getElementById('campoFormatoInput').value = ev ? (ev.formato || 'Presencial') : 'Presencial';
    document.getElementById('campoInicio').value = ev ? formatarParaInput(ev.start) : '';
    document.getElementById('campoFim').value = ev && ev.end ? formatarParaInput(ev.end) : '';
    document.getElementById('campoCor').value = ev ? (ev.color || '#ff7a1a') : '#ff7a1a';
    document.getElementById('campoGratuito').value = ev && ev.gratuito ? '1' : '0';
    document.getElementById('modalTitulo').textContent = ev ? 'Editar evento' : 'Novo evento';
    btnExcluir.style.display = ev ? 'inline-flex' : 'none';
    modalOverlay.classList.add('aberto');
}

function fecharModal() {
    modalOverlay.classList.remove('aberto');
}

btnCancelar.addEventListener('click', fecharModal);
btnNovoEvento.addEventListener('click', () => abrirModal(null));

function editarEvento(id) {
    const ev = TODOS_EVENTOS.find(e => e.id === id);
    if (ev) abrirModal(ev);
}

function excluirEvento(id) {
    if (!confirm('Tem certeza que deseja excluir este evento?')) return;
    fetch(`${API_URL}?id=${id}`, { method: 'DELETE' })
        .then(r => r.json())
        .then(() => carregarEventos())
        .catch(() => alert('Erro ao excluir o evento.'));
}

btnSalvar.addEventListener('click', function () {
    const id = document.getElementById('eventoId').value;
    const corpo = {
        titulo: document.getElementById('campoTitulo').value,
        descricao: document.getElementById('campoDescricao').value,
        imagem: document.getElementById('campoImagem').value,
        local: document.getElementById('campoLocal').value,
        categoria: document.getElementById('campoCategoria').value,
        formato: document.getElementById('campoFormatoInput').value,
        data_inicio: formatarParaMySQL(document.getElementById('campoInicio').value),
        data_fim: document.getElementById('campoFim').value ? formatarParaMySQL(document.getElementById('campoFim').value) : null,
        cor: document.getElementById('campoCor').value,
        gratuito: document.getElementById('campoGratuito').value === '1'
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
        carregarEventos();
    })
    .catch(() => alert('Erro ao salvar o evento.'));
});

btnExcluir.addEventListener('click', function () {
    const id = document.getElementById('eventoId').value;
    if (!id || !confirm('Tem certeza que deseja excluir este evento?')) return;
    excluirEvento(Number(id));
    fecharModal();
});
<?php endif; ?>

// --- Calendário (FullCalendar) ---
function inicializarCalendario() {
    const calendarEl = document.getElementById('calendar');

    calendarInstance = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        editable: EH_MASTER,
        selectable: EH_MASTER,
        events: function (info, successCallback) {
            successCallback(TODOS_EVENTOS);
        },

        select: function (info) {
            if (!EH_MASTER) return;
            abrirModal({ start: info.start, end: info.end });
            calendarInstance.unselect();
        },

        eventClick: function (info) {
            const id = Number(info.event.id);
            if (EH_MASTER) {
                editarEvento(id);
            } else {
                visualizarEvento(id);
            }
        },

        eventDrop: function (info) {
            if (!EH_MASTER) return;
            salvarMovimentacao(info.event);
        },

        eventResize: function (info) {
            if (!EH_MASTER) return;
            salvarMovimentacao(info.event);
        }
    });

    calendarInstance.render();
}

function salvarMovimentacao(ev) {
    fetch(`${API_URL}?id=${ev.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            data_inicio: formatarParaMySQL(ev.start),
            data_fim: ev.end ? formatarParaMySQL(ev.end) : null
        })
    }).then(() => carregarEventos())
      .catch(() => alert('Erro ao mover o evento.'));
}

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

carregarEventos();
</script>

</body>
</html>