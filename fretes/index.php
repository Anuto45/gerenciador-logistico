<?php
$pagina = 'fretes';
$titulo = 'Fretes';
require __DIR__ . '/../includes/header.php';

$fretes = consulta(
    'SELECT f.*, t.nome AS transportador, o.apelido AS origem, d.apelido AS destino,
            (SELECT COUNT(*) FROM frete_produtos fp WHERE fp.frete_id = f.id) AS qtd_itens
     FROM fretes f
     JOIN transportadores t ON t.id = f.transportador_id
     JOIN enderecos o ON o.id = f.origem_id
     JOIN enderecos d ON d.id = f.destino_id
     ORDER BY f.data_frete DESC, f.id DESC'
)->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Fretes</h1>
    <a class="btn btn-primary" href="form.php"><i class="bi bi-plus-lg"></i> Novo frete</a>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
    <thead><tr><th>#</th><th>Data</th><th>Transportador</th><th>Origem &rarr; Destino</th><th class="text-center">Produtos</th><th class="text-end">Valor da carga</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($fretes as $f): ?>
        <tr>
            <td><?= $f['id'] ?></td>
            <td><?= data_br($f['data_frete']) ?></td>
            <td><?= e($f['transportador']) ?></td>
            <td><?= e($f['origem']) ?> <i class="bi bi-arrow-right"></i> <?= e($f['destino']) ?></td>
            <td class="text-center"><?= $f['qtd_itens'] ?></td>
            <td class="text-end"><?= moeda($f['valor_total']) ?></td>
            <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-primary" href="ver.php?id=<?= $f['id'] ?>"><i class="bi bi-eye"></i></a>
                <form class="d-inline" method="post" action="excluir.php" onsubmit="return confirm('Excluir este frete?')">
                    <?= csrf_campo() ?><input type="hidden" name="id" value="<?= $f['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$fretes): ?><tr><td colspan="7" class="text-center text-muted py-4">Nenhum frete gerado.</td></tr><?php endif; ?>
    </tbody>
</table></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
