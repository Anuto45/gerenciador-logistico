<?php
$pagina = 'inicio';
$titulo = 'Início';
require __DIR__ . '/includes/header.php';

$total = fn(string $t) => (int)consulta("SELECT COUNT(*) FROM $t")->fetchColumn();
$cards = [
    ['Produtos', $total('produtos'), 'bi-box-seam', '/produtos/index.php'],
    ['Endereços', $total('enderecos'), 'bi-geo-alt', '/enderecos/index.php'],
    ['Transportadores', $total('transportadores'), 'bi-person-badge', '/transportadores/index.php'],
    ['Fretes', $total('fretes'), 'bi-truck', '/fretes/index.php'],
];
$hoje = consulta('SELECT COUNT(*) FROM fretes WHERE data_frete = ?', [date('Y-m-d')])->fetchColumn();
?>
<h1 class="h3 mb-4">Painel</h1>
<div class="row g-3 mb-4">
    <?php foreach ($cards as [$rotulo, $n, $icone, $url]): ?>
        <div class="col-6 col-lg-3">
            <a href="<?= $base . $url ?>" class="text-decoration-none">
                <div class="card card-resumo h-100"><div class="card-body">
                    <i class="bi <?= $icone ?> fs-3 text-primary"></i>
                    <div class="numero"><?= $n ?></div>
                    <div class="text-muted"><?= $rotulo ?></div>
                </div></div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<div class="card"><div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div><strong><?= (int)$hoje ?></strong> frete(s) hoje (<?= date('d/m/Y') ?>).</div>
    <div>
        <a class="btn btn-primary" href="<?= $base ?>/fretes/form.php"><i class="bi bi-plus-lg"></i> Novo frete</a>
        <a class="btn btn-outline-primary" href="<?= $base ?>/relatorios/index.php"><i class="bi bi-download"></i> Relatório do dia</a>
    </div>
</div></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
