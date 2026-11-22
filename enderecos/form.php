<?php
require_once __DIR__ . '/../includes/helpers.php';

$campos = ['apelido', 'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf'];
$id = (int)($_GET['id'] ?? 0);
$a = array_fill_keys($campos, '');
if ($id) {
    $a = consulta('SELECT * FROM enderecos WHERE id = ?', [$id])->fetch();
    if (!$a) { flash('danger', 'Endereço não encontrado.'); redirecionar('enderecos/index.php'); }
}

$erros = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigir_post_csrf();
    foreach ($campos as $c) { $a[$c] = trim($_POST[$c] ?? ''); }
    $a['uf'] = strtoupper($a['uf']);

    foreach (['apelido' => 'apelido', 'logradouro' => 'logradouro', 'numero' => 'número',
              'bairro' => 'bairro', 'cidade' => 'cidade'] as $c => $rotulo) {
        if ($a[$c] === '') $erros[] = "Informe o $rotulo.";
    }
    if (!preg_match('/^\d{5}-?\d{3}$/', $a['cep'])) $erros[] = 'CEP inválido (use 00000-000).';
    if (!in_array($a['uf'], UFS, true)) $erros[] = 'Selecione uma UF válida.';

    if (!$erros) {
        $valores = array_map(fn($c) => $a[$c], $campos);
        if ($id) {
            consulta('UPDATE enderecos SET apelido=?, cep=?, logradouro=?, numero=?, complemento=?, bairro=?, cidade=?, uf=? WHERE id=?',
                [...$valores, $id]);
        } else {
            consulta('INSERT INTO enderecos (apelido, cep, logradouro, numero, complemento, bairro, cidade, uf) VALUES (?,?,?,?,?,?,?,?)',
                $valores);
        }
        flash('success', 'Endereço salvo com sucesso.');
        redirecionar('enderecos/index.php');
    }
}

$pagina = 'enderecos';
$titulo = $id ? 'Editar endereço' : 'Novo endereço';
require __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3"><?= e($titulo) ?></h1>
<?php foreach ($erros as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" class="card"><div class="card-body row g-3">
    <?= csrf_campo() ?>
    <div class="col-md-6">
        <label class="form-label">Apelido (ex.: Matriz, Cliente X) *</label>
        <input class="form-control" name="apelido" maxlength="80" required value="<?= e($a['apelido']) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">CEP *</label>
        <input class="form-control" name="cep" data-mascara="cep" placeholder="00000-000" required value="<?= e($a['cep']) ?>">
    </div>
    <div class="col-md-9">
        <label class="form-label">Logradouro *</label>
        <input class="form-control" name="logradouro" maxlength="150" required value="<?= e($a['logradouro']) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Número *</label>
        <input class="form-control" name="numero" maxlength="10" required value="<?= e($a['numero']) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Complemento</label>
        <input class="form-control" name="complemento" maxlength="80" value="<?= e($a['complemento']) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Bairro *</label>
        <input class="form-control" name="bairro" maxlength="80" required value="<?= e($a['bairro']) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Cidade *</label>
        <input class="form-control" name="cidade" maxlength="80" required value="<?= e($a['cidade']) ?>">
    </div>
    <div class="col-md-2">
        <label class="form-label">UF *</label>
        <select class="form-select" name="uf" required>
            <option value="">--</option>
            <?php foreach (UFS as $uf): ?>
                <option <?= $a['uf'] === $uf ? 'selected' : '' ?>><?= $uf ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Salvar</button>
        <a class="btn btn-outline-secondary" href="index.php">Cancelar</a>
    </div>
</div></form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
