<?php
require_once __DIR__ . '/../includes/helpers.php';

$id = (int)($_GET['id'] ?? 0);
$f = consulta(
    'SELECT f.*, t.nome AS transportador, t.cnpj, t.telefone, t.placa_veiculo
     FROM fretes f JOIN transportadores t ON t.id = f.transportador_id WHERE f.id = ?', [$id])->fetch();
if (!$f) { flash('danger', 'Frete não encontrado.'); redirecionar('fretes/index.php'); }

$origem = consulta('SELECT * FROM enderecos WHERE id = ?', [$f['origem_id']])->fetch();
$destino = consulta('SELECT * FROM enderecos WHERE id = ?', [$f['destino_id']])->fetch();
$itens = consulta(
    'SELECT p.nome, p.peso_kg, p.valor, fp.quantidade
     FROM frete_produtos fp JOIN produtos p ON p.id = fp.produto_id
     WHERE fp.frete_id = ? ORDER BY p.nome', [$id])->fetchAll();

$fmtEnd = fn($a) => e($a['logradouro']) . ', ' . e($a['numero']) . ($a['complemento'] ? ' - ' . e($a['complemento']) : '')
    . '<br>' . e($a['bairro']) . ' &ndash; ' . e($a['cidade']) . '/' . e($a['uf']) . '<br>CEP ' . e($a['cep']);

$pagina = 'fretes';
$titulo = "Frete #$id";
require __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Frete #<?= $f['id'] ?> <small class="text-muted fs-6"><?= data_br($f['data_frete']) ?></small></h1>
    <div class="no-print">
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
        <a class="btn btn-outline-primary" href="index.php">Voltar</a>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h2 class="h6 text-muted">TRANSPORTADOR</h2>
        <strong><?= e($f['transportador']) ?></strong><br>
        CNPJ <?= e($f['cnpj']) ?><br>
        <?= e($f['telefone']) ?><br>Placa: <?= e($f['placa_veiculo']) ?>
    </div></div></div>
    <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h2 class="h6 text-muted">ORIGEM &ndash; <?= e($origem['apelido']) ?></h2>
        <?= $fmtEnd($origem) ?>
    </div></div></div>
    <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h2 class="h6 text-muted">DESTINO &ndash; <?= e($destino['apelido']) ?></h2>
        <?= $fmtEnd($destino) ?>
    </div></div></div>
</div>
<div class="card mb-3"><div class="table-responsive">
<table class="table align-middle mb-0">
    <thead><tr><th>Produto</th><th class="text-center">Qtd</th><th class="text-end">Peso unit. (kg)</th><th class="text-end">Valor unit.</th><th class="text-end">Subtotal</th></tr></thead>
    <tbody>
    <?php foreach ($itens as $i): ?>
        <tr>
            <td><?= e($i['nome']) ?></td>
            <td class="text-center"><?= $i['quantidade'] ?></td>
            <td class="text-end"><?= number_format($i['peso_kg'], 3, ',', '.') ?></td>
            <td class="text-end"><?= moeda($i['valor']) ?></td>
            <td class="text-end"><?= moeda($i['valor'] * $i['quantidade']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot><tr class="fw-bold">
        <td colspan="2">Totais</td>
        <td class="text-end" colspan="2"><?= number_format($f['peso_total'], 3, ',', '.') ?> kg</td>
        <td class="text-end"><?= moeda($f['valor_total']) ?></td>
    </tr></tfoot>
</table></div></div>
<?php if ($f['observacao']): ?><p><strong>Observação:</strong> <?= e($f['observacao']) ?></p><?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
