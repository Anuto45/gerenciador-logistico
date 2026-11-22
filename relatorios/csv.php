<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/consulta_dia.php';

$data = $_GET['data'] ?? '';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
    http_response_code(400);
    exit('Data inválida.');
}

$linhas = fretes_do_dia($data);

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="fretes_' . $data . '.csv"');

$saida = fopen('php://output', 'w');
fwrite($saida, "\xEF\xBB\xBF"); // BOM para o Excel reconhecer acentos
fputcsv($saida, ['Frete', 'Data', 'Transportador', 'Placa', 'Origem', 'Destino', 'Produto', 'Quantidade', 'Peso unit. (kg)', 'Valor unit. (R$)', 'Subtotal (R$)'], ';');
foreach ($linhas as $l) {
    fputcsv($saida, [
        $l['frete_id'], data_br($l['data_frete']), $l['transportador'], $l['placa_veiculo'],
        $l['origem'], $l['destino'], $l['produto'], $l['quantidade'],
        number_format($l['peso_kg'], 3, ',', ''),
        number_format($l['valor_unit'], 2, ',', ''),
        number_format($l['valor_unit'] * $l['quantidade'], 2, ',', ''),
    ], ';');
}
fclose($saida);
