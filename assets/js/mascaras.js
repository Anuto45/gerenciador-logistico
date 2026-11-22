// Máscaras simples de entrada (CEP, CNPJ, telefone) aplicadas por data-mascara="..."
(function () {
    function somenteDigitos(v) { return v.replace(/\D/g, ''); }

    var mascaras = {
        cep: function (v) {
            v = somenteDigitos(v).slice(0, 8);
            return v.length > 5 ? v.slice(0, 5) + '-' + v.slice(5) : v;
        },
        cnpj: function (v) {
            v = somenteDigitos(v).slice(0, 14);
            return v.replace(/^(\d{2})(\d)/, '$1.$2')
                    .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                    .replace(/\.(\d{3})(\d)/, '.$1/$2')
                    .replace(/(\d{4})(\d)/, '$1-$2');
        },
        telefone: function (v) {
            v = somenteDigitos(v).slice(0, 11);
            if (v.length > 10) return v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            if (v.length > 6)  return v.replace(/^(\d{2})(\d{4})(\d*)$/, '($1) $2-$3');
            if (v.length > 2)  return v.replace(/^(\d{2})(\d*)$/, '($1) $2');
            return v;
        },
        placa: function (v) { return v.toUpperCase().replace(/[^A-Z0-9-]/g, '').slice(0, 8); }
    };

    document.querySelectorAll('[data-mascara]').forEach(function (campo) {
        var fn = mascaras[campo.dataset.mascara];
        if (!fn) return;
        campo.addEventListener('input', function () { campo.value = fn(campo.value); });
    });
})();
