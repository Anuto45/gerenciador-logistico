// Campos dinâmicos do formulário de frete: adicionar/remover produtos e recalcular totais.
(function () {
    var lista = document.getElementById('lista-produtos');
    var modelo = document.getElementById('modelo-produto');
    var botaoAdicionar = document.getElementById('btn-adicionar');

    function formatarMoeda(n) {
        return n.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function recalcular() {
        var peso = 0, valor = 0;
        lista.querySelectorAll('.linha-produto').forEach(function (linha) {
            var opcao = linha.querySelector('select').selectedOptions[0];
            var qtd = parseInt(linha.querySelector('input').value, 10) || 0;
            if (opcao && opcao.value) {
                peso += parseFloat(opcao.dataset.peso) * qtd;
                valor += parseFloat(opcao.dataset.valor) * qtd;
            }
        });
        document.getElementById('total-peso').textContent =
            peso.toLocaleString('pt-BR', { minimumFractionDigits: 3, maximumFractionDigits: 3 }) + ' kg';
        document.getElementById('total-valor').textContent = formatarMoeda(valor);
    }

    botaoAdicionar.addEventListener('click', function () {
        lista.appendChild(modelo.content.cloneNode(true));
        recalcular();
    });

    // Delegação de eventos: funciona para linhas existentes e para as novas
    lista.addEventListener('click', function (ev) {
        var botao = ev.target.closest('.btn-remover');
        if (!botao) return;
        var linhas = lista.querySelectorAll('.linha-produto');
        if (linhas.length > 1) {
            botao.closest('.linha-produto').remove();
        } else {
            // mantém ao menos uma linha, apenas limpa
            linhas[0].querySelector('select').value = '';
            linhas[0].querySelector('input').value = 1;
        }
        recalcular();
    });
    lista.addEventListener('input', recalcular);
    lista.addEventListener('change', recalcular);

    recalcular();
})();
