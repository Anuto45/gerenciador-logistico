<?php
$pagina = 'produtos';
$titulo = 'Produtos';
require __DIR__ . '/../includes/header.php';
$produtos = consulta('SELECT * FROM produtos ORDER BY nome')->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Produtos</h1>
    <a class="btn btn-primary" href="form.php"><i class="bi bi-plus-lg"></i> Novo produto</a>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
    <thead><tr><th>#</th><th>Nome</th><th>Descrição</th><th class="text-end">Peso (kg)</th><th class="text-end">Valor</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($produtos as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= e($p['nome']) ?></td>
            <td><?= e($p['descricao']) ?></td>
            <td class="text-end"><?= number_format($p['peso_kg'], 3, ',', '.') ?></td>
            <td class="text-end"><?= moeda($p['valor']) ?></td>
            <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-primary" href="form.php?id=<?= $p['id'] ?>"><i class="bi bi-pencil"></i></a>
                <form class="d-inline" method="post" action="excluir.php" onsubmit="return confirm('Excluir este produto?')">
                    <?= csrf_campo() ?><input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$produtos): ?><tr><td colspan="6" class="text-center text-muted py-4">Nenhum produto cadastrado.</td></tr><?php endif; ?>
    </tbody>
</table></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
