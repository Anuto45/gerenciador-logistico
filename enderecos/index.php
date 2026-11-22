<?php
$pagina = 'enderecos';
$titulo = 'Endereços';
require __DIR__ . '/../includes/header.php';
$lista = consulta('SELECT * FROM enderecos ORDER BY apelido')->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Endereços <small class="text-muted fs-6">(origem e destino)</small></h1>
    <a class="btn btn-primary" href="form.php"><i class="bi bi-plus-lg"></i> Novo endereço</a>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
    <thead><tr><th>#</th><th>Apelido</th><th>Endereço</th><th>Cidade/UF</th><th>CEP</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($lista as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= e($a['apelido']) ?></td>
            <td><?= e($a['logradouro']) ?>, <?= e($a['numero']) ?><?= $a['complemento'] ? ' - ' . e($a['complemento']) : '' ?> &middot; <?= e($a['bairro']) ?></td>
            <td><?= e($a['cidade']) ?>/<?= e($a['uf']) ?></td>
            <td><?= e($a['cep']) ?></td>
            <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-primary" href="form.php?id=<?= $a['id'] ?>"><i class="bi bi-pencil"></i></a>
                <form class="d-inline" method="post" action="excluir.php" onsubmit="return confirm('Excluir este endereço?')">
                    <?= csrf_campo() ?><input type="hidden" name="id" value="<?= $a['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$lista): ?><tr><td colspan="6" class="text-center text-muted py-4">Nenhum endereço cadastrado.</td></tr><?php endif; ?>
    </tbody>
</table></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
