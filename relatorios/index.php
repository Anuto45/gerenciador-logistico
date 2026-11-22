<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/consulta_dia.php';

$data = $_GET['data'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) { $data = date('Y-m-d'); }
$linhas = fretes_do_dia($data);
$totalValor = array_sum(array_column($linhas, 'valor_total'));

$pagina = 'relatorios';
$titulo = 'Relatório diário';
require __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3">Relatório de fretes do dia</h1>
<form method="get" class="card mb-3 no-print"><div class="card-body d-flex gap-2 flex-wrap align-items-end">
    <div>
        <label class="form-label">Data</label>
        <input type="date" class="form-control" name="data" value="<?= e($data) ?>">
    </div>
    <button class="btn btn-primary"><i class="bi bi-search"></i> Consultar</button>
    <a class="btn btn-success <?= $linhas ? '' : 'disabled' ?>" href="csv.php?data=<?= e($data) ?>"><i class="bi bi-download"></i> Baixar relatório (CSV)</a>
    <button type="button" class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
</div></form>

<p class="mb-2"><strong><?= count($linhas) ?></strong> linha(s) em <?= data_br($data) ?> &mdash; total da carga: <strong><?= moeda($totalValor) ?></strong></p>
<div class="card"><div class="table-responsive">
<table class="table table-sm align-middle mb-0">
    <thead><tr><th>Frete</th><th>Transportador</th><th>Origem</th><th>Destino</th><th>Produto</th><th class="text-center">Qtd</th><th class="text-end">Subtotal</th></tr></thead>
    <tbody>
    <?php foreach ($linhas as $l): ?>
        <tr>
            <td>#<?= $l['frete_id'] ?></td>
            <td><?= e($l['transportador']) ?></td>
            <td><?= e($l['origem']) ?></td>
            <td><?= e($l['destino']) ?></td>
            <td><?= e($l['produto']) ?></td>
            <td class="text-center"><?= $l['quantidade'] ?></td>
            <td class="text-end"><?= moeda($l['valor_unit'] * $l['quantidade']) ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$linhas): ?><tr><td colspan="7" class="text-center text-muted py-4">Nenhum frete nesta data.</td></tr><?php endif; ?>
    </tbody>
</table></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
