<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
=========================================================
CONFIGURAÇÕES
=========================================================
*/

$ehMaster = isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'master';

$baseUrl = 'https://pindaeco.rf.gd';

/*
=========================================================
HEADER / HEAD
=========================================================
*/

include "../../includes/header.php";
include "../../includes/head.php";

?>

<!-- Font Awesome -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales-all.global.min.js"></script>

<!-- CSS do calendário -->
<link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/calendario.css">


<!-- =====================================================
     BANNER DE EVENTOS
====================================================== -->

<div
    class="eventos-banner"
    style="background-image: url('<?= $baseUrl ?>/assets/img/banner-eventos.jpg');"
>

    <div class="eventos-banner-overlay"></div>

    <div class="eventos-banner-conteudo">

        <span class="eventos-banner-tag">
            <i class="fa-solid fa-calendar-days"></i>
            Agenda Pinda Eco Tour
        </span>

        <h1 class="eventos-banner-titulo">
            Viva a natureza em Pindamonhangaba
        </h1>

        <p class="eventos-banner-texto">
            Confira mutirões, shows e passeios ecológicos.
            Fique por dentro de tudo o que vai rolar na cidade.
        </p>

    </div>

</div>


<!-- =====================================================
     CONTAINER PRINCIPAL
====================================================== -->

<div class="eventos-container">

    <div class="eventos-conteudo">


        <!-- =================================================
             FILTROS
        ================================================== -->

        <div class="filtros-barra">

            <!-- BUSCA -->

            <div class="filtro-busca">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    id="filtroBusca"
                    placeholder="Buscar eventos..."
                >

            </div>


            <!-- CATEGORIA -->

            <div class="filtro-select">

                <i class="fa-solid fa-filter"></i>

                <select id="filtroCategoria">

                    <option value="">Todas</option>

                </select>

                <i class="fa-solid fa-chevron-down"></i>

            </div>


            <!-- FORMATO -->

            <div class="filtro-select">

                <i class="fa-solid fa-globe"></i>

                <select id="filtroFormato">

                    <option value="">Todas</option>

                    <option value="Presencial">
                        Presencial
                    </option>

                    <option value="Online">
                        Online
                    </option>

                </select>

                <i class="fa-solid fa-chevron-down"></i>

            </div>


            <!-- LOCAL -->

            <div class="filtro-select">

                <i class="fa-solid fa-location-dot"></i>

                <select id="filtroLocal">

                    <option value="">Todas</option>

                </select>

                <i class="fa-solid fa-chevron-down"></i>

            </div>


            <!-- EVENTOS PASSADOS -->

            <button
                type="button"
                class="filtro-passados"
                id="btnPassados"
            >
                Passados
            </button>

        </div>



        <!-- =================================================
             CABEÇALHO
        ================================================== -->

        <div class="eventos-topo">

            <div class="eventos-topo-esquerda">

                <h1 class="eventos-titulo">
                    Próximos eventos
                </h1>

                <span
                    class="eventos-contador"
                    id="eventosContador"
                >
                    0 encontrados
                </span>

            </div>


            <div class="eventos-acoes-topo">


                <!-- ALTERNAR VISUALIZAÇÃO -->

                <div class="eventos-toggle">

                    <button
                        type="button"
                        id="btnVistaLista"
                        class="ativo"
                    >
                        <i class="fa-solid fa-table-cells"></i>
                        Lista
                    </button>


                    <button
                        type="button"
                        id="btnVistaCalendario"
                    >
                        <i class="fa-solid fa-calendar-days"></i>
                        Calendário
                    </button>

                </div>


                <!-- NOVO EVENTO -->

            <?php if ($ehMaster): ?> 
                <a href="create.php" class="eventos-botao-novo"> 
                    <i class="fa-solid fa-plus"></i> Novo evento 
                </a> 
            <?php endif; ?>


            </div>

        </div>



        <!-- =================================================
             VISTA LISTA
        ================================================== -->

        <div
            class="vista-lista"
            id="vistaLista"
        >

            <div
                class="eventos-grid"
                id="eventosGrid"
            >

                <!--
                    Os eventos são inseridos
                    automaticamente pelo JavaScript
                -->

            </div>

        </div>



        <!-- =================================================
             VISTA CALENDÁRIO
        ================================================== -->

        <div
            class="vista-calendario"
            id="vistaCalendario"
        >

            <div class="calendario-card">

                <div id="calendar"></div>

            </div>

        </div>


    </div>

</div>



<!-- =====================================================
     MODAL DE VISUALIZAÇÃO
     
     Disponível para todos os usuários
====================================================== -->

<div
    class="modal-overlay"
    id="modalVisualizarOverlay"
>

    <div class="modal modal-visualizar">


        <img
            class="evento-view-imagem"
            id="viewImagem"
            src=""
            alt=""
            style="display:none"
        >


        <h2 id="viewTitulo">
            Evento
        </h2>


        <div class="evento-view-meta">

            <span>

                <i class="fa-solid fa-calendar"></i>

                <span id="viewData"></span>

            </span>


            <span>

                <i class="fa-solid fa-clock"></i>

                <span id="viewHora"></span>

            </span>


            <span id="viewLocalWrap">

                <i class="fa-solid fa-location-dot"></i>

                <span id="viewLocal"></span>

            </span>

        </div>


        <p id="viewDescricao"></p>


        <div class="modal-botoes">

            <div></div>

            <div class="modal-botoes-direita">

                <button
                    type="button"
                    class="btn btn-cancelar"
                    id="btnFecharVisualizar"
                >
                    Fechar
                </button>

            </div>

        </div>

    </div>

</div>



<?php if ($ehMaster): ?>


<!-- =====================================================
     MODAL DE EDIÇÃO
     
     Apenas para usuário master
     (a criação agora acontece em create.php)
====================================================== -->

<div
    class="modal-overlay"
    id="modalOverlay"
>

    <div class="modal">


        <h2 id="modalTitulo">
            Editar evento
        </h2>


        <input
            type="hidden"
            id="eventoId"
        >


        <!-- TÍTULO -->

        <div class="campo">

            <label>

                <i class="fa-solid fa-heading"></i>

                Título

            </label>

            <input
                type="text"
                id="campoTitulo"
                placeholder="Ex: Mutirão de plantio"
            >

        </div>


        <!-- DESCRIÇÃO -->

        <div class="campo">

            <label>

                <i class="fa-solid fa-align-left"></i>

                Descrição

            </label>

            <textarea
                id="campoDescricao"
                rows="3"
                placeholder="Detalhes do evento"
            ></textarea>

        </div>


        <!-- IMAGEM -->

        <div class="campo">

            <label>

                <i class="fa-solid fa-image"></i>

                URL da imagem

            </label>

            <input
                type="text"
                id="campoImagem"
                placeholder="https://..."
            >

        </div>


        <!-- LOCAL -->

        <div class="campo">

            <label>

                <i class="fa-solid fa-location-dot"></i>

                Local

            </label>

            <input
                type="text"
                id="campoLocal"
                placeholder="Ex: Shopping Pátio Pinda"
            >

        </div>


        <!-- CATEGORIA / FORMATO -->

        <div class="campo-linha">


            <div class="campo">

                <label>

                    <i class="fa-solid fa-tag"></i>

                    Categoria

                </label>

                <input
                    type="text"
                    id="campoCategoria"
                    placeholder="Ex: Evento, Show"
                >

            </div>


            <div class="campo">

                <label>

                    <i class="fa-solid fa-globe"></i>

                    Formato

                </label>

                <select id="campoFormatoInput">

                    <option value="Presencial">
                        Presencial
                    </option>

                    <option value="Online">
                        Online
                    </option>

                </select>

            </div>


        </div>


        <!-- INÍCIO / FIM -->

        <div class="campo-linha">


            <div class="campo">

                <label>

                    <i class="fa-solid fa-clock"></i>

                    Início

                </label>

                <input
                    type="datetime-local"
                    id="campoInicio"
                >

            </div>


            <div class="campo">

                <label>

                    <i class="fa-solid fa-clock"></i>

                    Fim

                </label>

                <input
                    type="datetime-local"
                    id="campoFim"
                >

            </div>


        </div>


        <!-- COR / GRATUITO -->

        <div class="campo-linha">


            <div class="campo">

                <label>

                    <i class="fa-solid fa-palette"></i>

                    Cor

                </label>

                <input
                    type="color"
                    id="campoCor"
                    value="#ff7a1a"
                >

            </div>


            <div class="campo">

                <label>

                    <i class="fa-solid fa-ticket"></i>

                    Gratuito?

                </label>

                <select id="campoGratuito">

                    <option value="0">
                        Não
                    </option>

                    <option value="1">
                        Sim
                    </option>

                </select>

            </div>


        </div>


        <!-- BOTÕES -->

        <div class="modal-botoes">


            <button
                type="button"
                class="btn btn-excluir"
                id="btnExcluir"
                style="display:none"
            >

                <i class="fa-solid fa-trash"></i>

                Excluir

            </button>


            <div class="modal-botoes-direita">

                <button
                    type="button"
                    class="btn btn-cancelar"
                    id="btnCancelar"
                >
                    Cancelar
                </button>


                <button
                    type="button"
                    class="btn btn-salvar"
                    id="btnSalvar"
                >
                    Salvar
                </button>

            </div>


        </div>


    </div>

</div>


<?php endif; ?>



<script>

/*
=========================================================
CONFIGURAÇÕES
=========================================================
*/

const API_URL = '<?= $baseUrl ?>/api/events.php';

const EH_MASTER =
    <?php echo $ehMaster ? 'true' : 'false'; ?>;


/*
=========================================================
VARIÁVEIS
=========================================================
*/

let TODOS_EVENTOS = [];

let mostrandoPassados = false;

let vistaAtual = 'lista';

let calendarInstance = null;


/*
=========================================================
ELEMENTOS
=========================================================
*/

const grid =
    document.getElementById('eventosGrid');

const contador =
    document.getElementById('eventosContador');

const filtroBusca =
    document.getElementById('filtroBusca');

const filtroCategoria =
    document.getElementById('filtroCategoria');

const filtroFormato =
    document.getElementById('filtroFormato');

const filtroLocal =
    document.getElementById('filtroLocal');

const btnPassados =
    document.getElementById('btnPassados');



/*
=========================================================
CARREGAR EVENTOS
=========================================================
*/

function carregarEventos() {

    fetch(API_URL)

        .then(response => {

            if (!response.ok) {
                throw new Error('Erro HTTP');
            }

            return response.json();

        })

        .then(dados => {

            TODOS_EVENTOS =
                Array.isArray(dados.eventos)
                    ? dados.eventos
                    : [];

            popularFiltros();

            renderizarLista();


            if (calendarInstance) {

                calendarInstance.refetchEvents();

            }

        })

        .catch(erro => {

            console.error(
                'Erro ao carregar eventos:',
                erro
            );

            grid.innerHTML = `

                <div class="eventos-vazio">

                    <h3>
                        Não foi possível carregar os eventos
                    </h3>

                    <p>
                        Tente novamente em instantes.
                    </p>

                </div>

            `;

        });

}



/*
=========================================================
POPULAR FILTROS
=========================================================
*/

function popularFiltros() {

    const categorias = [
        ...new Set(
            TODOS_EVENTOS
                .map(evento => evento.categoria)
                .filter(Boolean)
        )
    ];


    const locais = [
        ...new Set(
            TODOS_EVENTOS
                .map(evento => evento.local)
                .filter(Boolean)
        )
    ];


    filtroCategoria.innerHTML =
        '<option value="">Todas</option>' +

        categorias
            .map(categoria =>
                `<option value="${escapeHtml(categoria)}">
                    ${escapeHtml(categoria)}
                </option>`
            )
            .join('');


    filtroLocal.innerHTML =
        '<option value="">Todas</option>' +

        locais
            .map(local =>
                `<option value="${escapeHtml(local)}">
                    ${escapeHtml(local)}
                </option>`
            )
            .join('');

}



/*
=========================================================
ESCAPAR HTML
=========================================================
*/

function escapeHtml(valor) {

    if (valor === null || valor === undefined) {
        return '';
    }

    return String(valor)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}



/*
=========================================================
EVENTOS FILTRADOS
=========================================================
*/

function eventosFiltrados() {

    const agora = new Date();

    const busca =
        filtroBusca.value
            .trim()
            .toLowerCase();

    const categoria =
        filtroCategoria.value;

    const formato =
        filtroFormato.value;

    const local =
        filtroLocal.value;


    return TODOS_EVENTOS

        .filter(evento => {

            const dataEvento =
                new Date(evento.start);

            const ehPassado =
                dataEvento < agora;


            if (
                mostrandoPassados !==
                ehPassado
            ) {
                return false;
            }


            const textoBusca = `

                ${evento.title || ''}

                ${evento.extendedProps?.descricao || ''}

            `.toLowerCase();


            if (
                busca &&
                !textoBusca.includes(busca)
            ) {
                return false;
            }


            if (
                categoria &&
                evento.categoria !== categoria
            ) {
                return false;
            }


            if (
                formato &&
                evento.formato !== formato
            ) {
                return false;
            }


            if (
                local &&
                evento.local !== local
            ) {
                return false;
            }


            return true;

        })

        .sort(
            (a, b) =>
                new Date(a.start) -
                new Date(b.start)
        );

}



/*
=========================================================
FORMATAR DATA
=========================================================
*/

function formatarDataBR(dataStr) {

    const data = new Date(dataStr);

    return data.toLocaleDateString(
        'pt-BR',
        {
            day: '2-digit',
            month: 'long'
        }
    );

}



/*
=========================================================
FORMATAR HORA
=========================================================
*/

function formatarHoraBR(dataStr) {

    const data = new Date(dataStr);

    return data.toLocaleTimeString(
        'pt-BR',
        {
            hour: '2-digit',
            minute: '2-digit'
        }
    );

}



/*
=========================================================
RENDERIZAR LISTA
=========================================================
*/

function renderizarLista() {

    const eventos =
        eventosFiltrados();


    contador.textContent =
        `${eventos.length} encontrado${
            eventos.length === 1
                ? ''
                : 's'
        }`;


    if (eventos.length === 0) {

        grid.innerHTML = `

            <div class="eventos-vazio">

                <h3>
                    Nenhum evento encontrado
                </h3>

                <p>
                    Tente ajustar os filtros de busca.
                </p>

            </div>

        `;

        return;

    }


    grid.innerHTML = eventos.map(evento => {


        const imagemHtml =
            evento.imagem

                ? `

                    <img
                        class="evento-imagem"
                        src="${escapeHtml(evento.imagem)}"
                        alt="${escapeHtml(evento.title)}"
                        onerror="this.style.display='none'"
                    >

                `

                : `

                    <div class="evento-imagem-placeholder">

                        <i class="fa-solid fa-calendar-days"></i>

                    </div>

                `;


        const localHtml =
            evento.local

                ? `

                    <span class="evento-local">

                        <i class="fa-solid fa-location-dot"></i>

                        <span>
                            ${escapeHtml(evento.local)}
                        </span>

                    </span>

                `

                : '<span></span>';


        const acoesMaster =
            EH_MASTER

                ? `

                    <div class="evento-acoes-master">

                        <button
                            type="button"
                            class="evento-editar"
                            onclick="editarEvento(${Number(evento.id)})"
                        >

                            <i class="fa-solid fa-pen"></i>

                            Editar

                        </button>


                        <button
                            type="button"
                            class="evento-excluir"
                            onclick="excluirEvento(${Number(evento.id)})"
                        >

                            <i class="fa-solid fa-trash"></i>

                            Excluir

                        </button>

                    </div>

                `

                : '';


        return `

            <div class="evento-card">


                <div class="evento-imagem-container">

                    ${imagemHtml}


                    ${
                        evento.categoria

                            ? `

                                <span class="evento-badge-categoria">

                                    ${escapeHtml(evento.categoria)}

                                </span>

                            `

                            : ''
                    }


                    ${
                        evento.gratuito

                            ? `

                                <span class="evento-badge-gratuito">

                                    Grátis

                                </span>

                            `

                            : ''
                    }

                </div>


                <div class="evento-info">


                    <div class="evento-datahora">

                        <span>

                            <i class="fa-solid fa-calendar"></i>

                            ${formatarDataBR(evento.start)}

                        </span>


                        <span>

                            <i class="fa-solid fa-clock"></i>

                            ${formatarHoraBR(evento.start)}

                        </span>

                    </div>


                    <h3 class="evento-titulo">

                        ${escapeHtml(evento.title)}

                    </h3>


                    <p class="evento-descricao">

                        ${escapeHtml(
                            evento.extendedProps?.descricao || ''
                        )}

                    </p>


                    <div class="evento-footer">

                        ${localHtml}


                        <button
                            type="button"
                            class="evento-ver-mais"
                            onclick="visualizarEvento(${Number(evento.id)})"
                        >

                            Ver mais

                            <i class="fa-solid fa-arrow-right"></i>

                        </button>

                    </div>


                </div>


                ${acoesMaster}

            </div>

        `;

    }).join('');

}



/*
=========================================================
FILTROS
=========================================================
*/

[
    filtroBusca,
    filtroCategoria,
    filtroFormato,
    filtroLocal

].forEach(elemento => {

    elemento.addEventListener(
        'input',
        renderizarLista
    );

    elemento.addEventListener(
        'change',
        renderizarLista
    );

});



/*
=========================================================
PASSADOS / PRÓXIMOS
=========================================================
*/

btnPassados.addEventListener(
    'click',
    () => {

        mostrandoPassados =
            !mostrandoPassados;


        btnPassados.classList.toggle(
            'ativo',
            mostrandoPassados
        );


        btnPassados.textContent =
            mostrandoPassados
                ? 'Próximos'
                : 'Passados';


        renderizarLista();

    }
);



/*
=========================================================
VISTAS
=========================================================
*/

const btnVistaLista =
    document.getElementById(
        'btnVistaLista'
    );

const btnVistaCalendario =
    document.getElementById(
        'btnVistaCalendario'
    );

const vistaLista =
    document.getElementById(
        'vistaLista'
    );

const vistaCalendario =
    document.getElementById(
        'vistaCalendario'
    );


btnVistaLista.addEventListener(
    'click',
    () => alternarVista('lista')
);


btnVistaCalendario.addEventListener(
    'click',
    () => alternarVista('calendario')
);



function alternarVista(vista) {

    vistaAtual = vista;


    btnVistaLista.classList.toggle(
        'ativo',
        vista === 'lista'
    );


    btnVistaCalendario.classList.toggle(
        'ativo',
        vista === 'calendario'
    );


    vistaLista.classList.toggle(
        'oculta',
        vista !== 'lista'
    );


    vistaCalendario.classList.toggle(
        'ativa',
        vista === 'calendario'
    );


    if (
        vista === 'calendario' &&
        !calendarInstance
    ) {

        inicializarCalendario();

    }


    if (
        vista === 'calendario' &&
        calendarInstance
    ) {

        setTimeout(() => {

            calendarInstance.updateSize();

        }, 100);

    }

}



/*
=========================================================
MODAL DE VISUALIZAÇÃO
=========================================================
*/

const modalVisualizarOverlay =
    document.getElementById(
        'modalVisualizarOverlay'
    );


document
    .getElementById('btnFecharVisualizar')
    .addEventListener(
        'click',
        () => {

            modalVisualizarOverlay
                .classList
                .remove('aberto');

        }
    );



function visualizarEvento(id) {

    const evento =
        TODOS_EVENTOS.find(
            e => Number(e.id) === Number(id)
        );


    if (!evento) {
        return;
    }


    const imagem =
        document.getElementById(
            'viewImagem'
        );


    if (evento.imagem) {

        imagem.src =
            evento.imagem;

        imagem.style.display =
            'block';

    } else {

        imagem.src = '';

        imagem.style.display =
            'none';

    }


    document.getElementById(
        'viewTitulo'
    ).textContent =
        evento.title || 'Evento';


    document.getElementById(
        'viewData'
    ).textContent =
        formatarDataBR(evento.start);


    document.getElementById(
        'viewHora'
    ).textContent =
        formatarHoraBR(evento.start);


    document.getElementById(
        'viewDescricao'
    ).textContent =
        evento.extendedProps?.descricao || '';


    const localWrap =
        document.getElementById(
            'viewLocalWrap'
        );


    if (evento.local) {

        localWrap.style.display =
            'inline-flex';


        document.getElementById(
            'viewLocal'
        ).textContent =
            evento.local;

    } else {

        localWrap.style.display =
            'none';

    }


    modalVisualizarOverlay
        .classList
        .add('aberto');

}



/*
=========================================================
FECHAR MODAL AO CLICAR FORA
=========================================================
*/

modalVisualizarOverlay.addEventListener(
    'click',
    evento => {

        if (
            evento.target ===
            modalVisualizarOverlay
        ) {

            modalVisualizarOverlay
                .classList
                .remove('aberto');

        }

    }
);



<?php if ($ehMaster): ?>


/*
=========================================================
MODAL MASTER
(criação agora fica em create.php;
este modal só edita/exclui)
=========================================================
*/

const modalOverlay =
    document.getElementById(
        'modalOverlay'
    );

const btnSalvar =
    document.getElementById(
        'btnSalvar'
    );

const btnCancelar =
    document.getElementById(
        'btnCancelar'
    );

const btnExcluir =
    document.getElementById(
        'btnExcluir'
    );



/*
=========================================================
ABRIR MODAL
=========================================================
*/

function abrirModal(evento = null) {

    document.getElementById(
        'eventoId'
    ).value =
        evento
            ? evento.id
            : '';


    document.getElementById(
        'campoTitulo'
    ).value =
        evento
            ? evento.title || ''
            : '';


    document.getElementById(
        'campoDescricao'
    ).value =
        evento
            ? evento.extendedProps?.descricao || ''
            : '';


    document.getElementById(
        'campoImagem'
    ).value =
        evento
            ? evento.imagem || ''
            : '';


    document.getElementById(
        'campoLocal'
    ).value =
        evento
            ? evento.local || ''
            : '';


    document.getElementById(
        'campoCategoria'
    ).value =
        evento
            ? evento.categoria || ''
            : 'Evento';


    document.getElementById(
        'campoFormatoInput'
    ).value =
        evento
            ? evento.formato || 'Presencial'
            : 'Presencial';


    document.getElementById(
        'campoInicio'
    ).value =
        evento
            ? formatarParaInput(evento.start)
            : '';


    document.getElementById(
        'campoFim'
    ).value =
        evento && evento.end
            ? formatarParaInput(evento.end)
            : '';


    document.getElementById(
        'campoCor'
    ).value =
        evento
            ? evento.color || '#ff7a1a'
            : '#ff7a1a';


    document.getElementById(
        'campoGratuito'
    ).value =
        evento && evento.gratuito
            ? '1'
            : '0';


    document.getElementById(
        'modalTitulo'
    ).textContent =
        evento
            ? 'Editar evento'
            : 'Novo evento';


    btnExcluir.style.display =
        evento
            ? 'inline-flex'
            : 'none';


    modalOverlay
        .classList
        .add('aberto');

}



/*
=========================================================
FECHAR MODAL
=========================================================
*/

function fecharModal() {

    modalOverlay
        .classList
        .remove('aberto');

}


btnCancelar.addEventListener(
    'click',
    fecharModal
);



/*
=========================================================
EDITAR EVENTO
=========================================================
*/

function editarEvento(id) {

    const evento =
        TODOS_EVENTOS.find(
            e => Number(e.id) === Number(id)
        );


    if (evento) {

        abrirModal(evento);

    }

}



/*
=========================================================
EXCLUIR EVENTO
=========================================================
*/

function excluirEvento(id) {

    if (
        !confirm(
            'Tem certeza que deseja excluir o evento?'
        )
    ) {
        return;
    }


    fetch(
        `${API_URL}?id=${id}`,
        {
            method: 'DELETE'
        }
    )

        .then(response => {

            if (!response.ok) {
                throw new Error('Erro ao excluir');
            }

            return response.json();

        })

        .then(() => {

            fecharModal();

            carregarEventos();

        })

        .catch(erro => {

            console.error(erro);

            alert(
                'Erro ao excluir o evento.'
            );

        });

}



/*
=========================================================
SALVAR EVENTO (EDIÇÃO)
=========================================================
*/

btnSalvar.addEventListener(
    'click',
    function () {


        const id =
            document.getElementById(
                'eventoId'
            ).value;


        const corpo = {

            titulo:
                document.getElementById(
                    'campoTitulo'
                ).value.trim(),

            descricao:
                document.getElementById(
                    'campoDescricao'
                ).value.trim(),

            imagem:
                document.getElementById(
                    'campoImagem'
                ).value.trim(),

            local:
                document.getElementById(
                    'campoLocal'
                ).value.trim(),

            categoria:
                document.getElementById(
                    'campoCategoria'
                ).value.trim(),

            formato:
                document.getElementById(
                    'campoFormatoInput'
                ).value,

            data_inicio:
                formatarParaMySQL(
                    document.getElementById(
                        'campoInicio'
                    ).value
                ),

            data_fim:
                document.getElementById(
                    'campoFim'
                ).value
                    ? formatarParaMySQL(
                        document.getElementById(
                            'campoFim'
                        ).value
                    )
                    : null,

            cor:
                document.getElementById(
                    'campoCor'
                ).value,

            gratuito:
                document.getElementById(
                    'campoGratuito'
                ).value === '1'

        };


        if (
            !corpo.titulo ||
            !corpo.data_inicio
        ) {

            alert(
                'Preencha ao menos o título e o início.'
            );

            return;

        }


        const url =
            id
                ? `${API_URL}?id=${id}`
                : API_URL;


        const metodo =
            id
                ? 'PUT'
                : 'POST';


        fetch(
            url,
            {

                method: metodo,

                headers: {
                    'Content-Type':
                        'application/json'
                },

                body:
                    JSON.stringify(corpo)

            }
        )

            .then(response => {

                if (!response.ok) {
                    throw new Error(
                        'Erro ao salvar'
                    );
                }

                return response.json();

            })

            .then(() => {

                fecharModal();

                carregarEventos();

            })

            .catch(erro => {

                console.error(erro);

                alert(
                    'Erro ao salvar o evento.'
                );

            });

    }
);



/*
=========================================================
EXCLUIR PELO MODAL
=========================================================
*/

btnExcluir.addEventListener(
    'click',
    function () {

        const id =
            document.getElementById(
                'eventoId'
            ).value;


        if (
            !id ||
            !confirm(
                'Tem certeza que deseja excluir o evento?'
            )
        ) {
            return;
        }


        excluirEvento(
            Number(id)
        );

    }
);



/*
=========================================================
FECHAR MODAL CLICANDO FORA
=========================================================
*/

modalOverlay.addEventListener(
    'click',
    evento => {

        if (
            evento.target ===
            modalOverlay
        ) {

            fecharModal();

        }

    }
);


<?php endif; ?>



/*
=========================================================
FULLCALENDAR
=========================================================
*/

function inicializarCalendario() {

    const calendarEl =
        document.getElementById(
            'calendar'
        );


    if (!calendarEl) {
        return;
    }


    calendarInstance =
        new FullCalendar.Calendar(
            calendarEl,
            {

                locale: 'pt-br',

                initialView:
                    'dayGridMonth',

                height: 'auto',

                contentHeight:
                    'auto',


                headerToolbar: {

                    left:
                        'prev,next today',

                    center:
                        'title',

                    right:
                        'dayGridMonth,timeGridWeek,listWeek'

                },


                buttonText: {

                    today:
                        'Hoje',

                    month:
                        'Mês',

                    week:
                        'Semana',

                    list:
                        'Lista'

                },


                editable:
                    EH_MASTER,

                selectable:
                    EH_MASTER,


                events:
                    function (
                        info,
                        successCallback
                    ) {

                        const eventos =
                            eventosFiltrados();

                        successCallback(
                            eventos
                        );

                    },


                select:
                    function (info) {

                        if (!EH_MASTER) {
                            return;
                        }


                        abrirModal({

                            start:
                                info.start,

                            end:
                                info.end

                        });


                        calendarInstance
                            .unselect();

                    },


                eventClick:
                    function (info) {

                        const id =
                            Number(
                                info.event.id
                            );


                        if (EH_MASTER) {

                            editarEvento(id);

                        } else {

                            visualizarEvento(id);

                        }

                    },


                eventDrop:
                    function (info) {

                        if (!EH_MASTER) {
                            return;
                        }

                        salvarMovimentacao(
                            info.event
                        );

                    },


                eventResize:
                    function (info) {

                        if (!EH_MASTER) {
                            return;
                        }

                        salvarMovimentacao(
                            info.event
                        );

                    },


                eventDidMount:
                    function (info) {

                        if (
                            info.event.extendedProps &&
                            info.event.extendedProps.descricao
                        ) {

                            info.el.title =
                                info.event
                                    .extendedProps
                                    .descricao;

                        }

                    }

            }
        );


    calendarInstance.render();

}



/*
=========================================================
SALVAR MOVIMENTAÇÃO
=========================================================
*/

function salvarMovimentacao(evento) {

    fetch(
        `${API_URL}?id=${evento.id}`,
        {

            method: 'PUT',

            headers: {
                'Content-Type':
                    'application/json'
            },

            body:
                JSON.stringify({

                    data_inicio:
                        formatarParaMySQL(
                            evento.start
                        ),

                    data_fim:
                        evento.end
                            ? formatarParaMySQL(
                                evento.end
                            )
                            : null

                })

        }
    )

        .then(response => {

            if (!response.ok) {
                throw new Error(
                    'Erro ao movimentar evento'
                );
            }

            return response.json();

        })

        .then(() => {

            carregarEventos();

        })

        .catch(erro => {

            console.error(erro);

            alert(
                'Erro ao mover o evento.'
            );

            if (calendarInstance) {

                calendarInstance.refetchEvents();

            }

        });

}



/*
=========================================================
FORMATAR PARA INPUT
=========================================================
*/

function formatarParaInput(data) {

    if (!data) {
        return '';
    }


    const d =
        new Date(data);


    const pad =
        numero =>
            String(numero)
                .padStart(2, '0');


    return `${d.getFullYear()}-${pad(
        d.getMonth() + 1
    )}-${pad(
        d.getDate()
    )}T${pad(
        d.getHours()
    )}:${pad(
        d.getMinutes()
    )}`;

}



/*
=========================================================
FORMATAR PARA MYSQL
=========================================================
*/

function formatarParaMySQL(data) {

    if (!data) {
        return null;
    }


    const d =
        new Date(data);


    const pad =
        numero =>
            String(numero)
                .padStart(2, '0');


    return `${d.getFullYear()}-${pad(
        d.getMonth() + 1
    )}-${pad(
        d.getDate()
    )} ${pad(
        d.getHours()
    )}:${pad(
        d.getMinutes()
    )}:00`;

}



/*
=========================================================
INICIAR
=========================================================
*/

carregarEventos();

</script>


<?php

/*
=========================================================
FOOTER
=========================================================
*/

include "../../includes/footer.php";

?>