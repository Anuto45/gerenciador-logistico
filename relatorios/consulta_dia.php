<?php
/** Uma linha por produto de cada frete do dia (transformação feita em PHP). */
function fretes_do_dia(string $data): array
{
    return consulta(
        'SELECT f.id AS frete_id, f.data_frete, t.nome AS transportador, t.placa_veiculo,
                o.apelido AS origem, d.apelido AS destino,
                p.nome AS produto, p.valor AS valor_unit, p.peso_kg, fp.quantidade, f.valor_total
         FROM fretes f
         JOIN transportadores t ON t.id = f.transportador_id
         JOIN enderecos o ON o.id = f.origem_id
         JOIN enderecos d ON d.id = f.destino_id
         JOIN frete_produtos fp ON fp.frete_id = f.id
         JOIN produtos p ON p.id = fp.produto_id
         WHERE f.data_frete = ?
         ORDER BY f.id, p.nome', [$data])->fetchAll();
}
