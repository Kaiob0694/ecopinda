/*
|--------------------------------------------------------------------------
| INDICADOR DESLIZANTE DO MENU
|--------------------------------------------------------------------------
|
| Move uma barrinha (.indicator) por baixo do item do menu que está
| recebendo hover, em vez de cada link ter sua própria barra fixa.
|
| Cores por item:
| - Padrão .......... var(--laranja)
| - "Cidade" ........ var(--verde)
| - Item com submenu  var(--amarelo)
|
| Você pode sobrescrever a cor de qualquer link colocando o atributo
| data-indicator-color="verde" | "amarelo" | "laranja" diretamente
| no <a> (ou no <a> do .menu-dropdown), sem precisar mexer neste arquivo.
|
*/

document.addEventListener('DOMContentLoaded', function () {

    var menu = document.querySelector('.menu');

    if (!menu) {
        return;
    }

    var indicador = menu.querySelector('.indicator');

    if (!indicador) {
        return;
    }

    // Cores disponíveis (batendo com as variáveis do :root)
    var cores = {
        laranja: 'var(--laranja)',
        verde: 'var(--verde)',
        amarelo: 'var(--amarelo)'
    };

    // Itens que recebem o indicador: links diretos do menu
    // + o link principal de itens com submenu (.menu-dropdown > a)
    var itens = menu.querySelectorAll(
        ':scope > a, .menu-dropdown > a, .menu-usuario'
    );

    var itemAtivo = null;

    function corDoItem(item) {

        if (item.dataset.indicatorColor) {
            return cores[item.dataset.indicatorColor] || cores.laranja;
        }

        if (item.closest('.menu-dropdown')) {
            return cores.amarelo;
        }

        // "Cidade" é o 2º link direto do menu (mesma regra do CSS original)
        var diretos = Array.prototype.filter.call(
            menu.children,
            function (el) {
                return el.tagName === 'A';
            }
        );

        if (diretos.indexOf(item) === 1) {
            return cores.verde;
        }

        return cores.laranja;
    }

    function moverIndicador(item) {

        var menuRect = menu.getBoundingClientRect();
        var itemRect = item.getBoundingClientRect();

        var left = itemRect.left - menuRect.left;
        var width = itemRect.width;

        indicador.style.left = left + 'px';
        indicador.style.width = width + 'px';
        indicador.style.background = corDoItem(item);

        indicador.classList.add('visivel');
    }

    itens.forEach(function (item) {

        item.addEventListener('mouseenter', function () {
            itemAtivo = item;
            moverIndicador(item);
        });
    });

    menu.addEventListener('mouseleave', function () {
        itemAtivo = null;
        indicador.classList.remove('visivel');
    });

    // Recalcula a posição se a janela for redimensionada
    // enquanto o indicador estiver visível sobre algum item.
    window.addEventListener('resize', function () {

        if (itemAtivo) {
            moverIndicador(itemAtivo);
        }
    });

});
