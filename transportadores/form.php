<?php
require_once __DIR__ . '/../includes/helpers.php';

$campos = ['nome', 'cnpj', 'telefone', 'placa_veiculo'];
$id = (int)($_GET['id'] ?? 0);
$t = array_fill_keys($campos, '');
if ($id) {
    $t = consulta('SELECT * FROM transportadores WHERE id = ?', [$id])->fetch();
    if (!$t) { flash('danger', 'Transportador não encontrado.'); redirecionar('transportadores/index.php'); }
}

$erros = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigir_post_csrf();
    foreach ($campos as $c) { $t[$c] = trim($_POST[$c] ?? ''); }
    $t['placa_veiculo'] = strtoupper($t['placa_veiculo']);

    if ($t['nome'] === '') $erros[] = 'Informe o nome do transportador.';
    // Sem validação de autenticidade (conforme o roteiro): apenas o formato.
    if (!preg_match('/^\d{2}\.?\d{3}\.?\d{3}\/?\d{4}-?\d{2}$/', $t['cnpj'])) $erros[] = 'CNPJ em formato inválido.';

    if (!$erros) {
        $valores = array_map(fn($c) => $t[$c], $campos);
        if ($id) {
            consulta('UPDATE transportadores SET nome=?, cnpj=?, telefone=?, placa_veiculo=? WHERE id=?', [...$valores, $id]);
        } else {
            consulta('INSERT INTO transportadores (nome, cnpj, telefone, placa_veiculo) VALUES (?,?,?,?)', $valores);
        }
        flash('success', 'Transportador salvo com sucesso.');
        redirecionar('transportadores/index.php');
    }
}

$pagina = 'transportadores';
$titulo = $id ? 'Editar transportador' : 'Novo transportador';
require __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3"><?= e($titulo) ?></h1>
<?php foreach ($erros as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" class="card"><div class="card-body row g-3">
    <?= csrf_campo() ?>
    <div class="col-md-8">
        <label class="form-label">Nome / Razão social *</label>
        <input class="form-control" name="nome" maxlength="120" required value="<?= e($t['nome']) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">CNPJ *</label>
        <input class="form-control" name="cnpj" data-mascara="cnpj" placeholder="00.000.000/0000-00" required value="<?= e($t['cnpj']) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Telefone</label>
        <input class="form-control" name="telefone" data-mascara="telefone" value="<?= e($t['telefone']) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Placa do veículo</label>
        <input class="form-control" name="placa_veiculo" data-mascara="placa" value="<?= e($t['placa_veiculo']) ?>">
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Salvar</button>
        <a class="btn btn-outline-secondary" href="index.php">Cancelar</a>
    </div>
</div></form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
