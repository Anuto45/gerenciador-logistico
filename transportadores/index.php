<?php
$pagina = 'transportadores';
$titulo = 'Transportadores';
require __DIR__ . '/../includes/header.php';
$lista = consulta('SELECT * FROM transportadores ORDER BY nome')->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Transportadores</h1>
    <a class="btn btn-primary" href="form.php"><i class="bi bi-plus-lg"></i> Novo transportador</a>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
    <thead><tr><th>#</th><th>Nome</th><th>CNPJ</th><th>Telefone</th><th>Placa</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($lista as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= e($t['nome']) ?></td>
            <td><?= e($t['cnpj']) ?></td>
            <td><?= e($t['telefone']) ?></td>
            <td><?= e($t['placa_veiculo']) ?></td>
            <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-outline-primary" href="form.php?id=<?= $t['id'] ?>"><i class="bi bi-pencil"></i></a>
                <form class="d-inline" method="post" action="excluir.php" onsubmit="return confirm('Excluir este transportador?')">
                    <?= csrf_campo() ?><input type="hidden" name="id" value="<?= $t['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$lista): ?><tr><td colspan="6" class="text-center text-muted py-4">Nenhum transportador cadastrado.</td></tr><?php endif; ?>
    </tbody>
</table></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
