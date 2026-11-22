<?php
require_once __DIR__ . '/../includes/helpers.php';

$id = (int)($_GET['id'] ?? 0);
$p = ['nome' => '', 'descricao' => '', 'peso_kg' => '', 'valor' => ''];
if ($id) {
    $p = consulta('SELECT * FROM produtos WHERE id = ?', [$id])->fetch();
    if (!$p) { flash('danger', 'Produto não encontrado.'); redirecionar('produtos/index.php'); }
}

$erros = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigir_post_csrf();
    $p = [
        'nome' => trim($_POST['nome'] ?? ''),
        'descricao' => trim($_POST['descricao'] ?? ''),
        'peso_kg' => para_decimal($_POST['peso_kg'] ?? 0),
        'valor' => para_decimal($_POST['valor'] ?? 0),
    ];
    if ($p['nome'] === '') $erros[] = 'Informe o nome do produto.';
    if ($p['peso_kg'] <= 0) $erros[] = 'O peso deve ser maior que zero.';
    if ($p['valor'] < 0) $erros[] = 'O valor não pode ser negativo.';

    if (!$erros) {
        if ($id) {
            consulta('UPDATE produtos SET nome=?, descricao=?, peso_kg=?, valor=? WHERE id=?',
                [$p['nome'], $p['descricao'], $p['peso_kg'], $p['valor'], $id]);
        } else {
            consulta('INSERT INTO produtos (nome, descricao, peso_kg, valor) VALUES (?,?,?,?)',
                [$p['nome'], $p['descricao'], $p['peso_kg'], $p['valor']]);
        }
        flash('success', 'Produto salvo com sucesso.');
        redirecionar('produtos/index.php');
    }
}

$pagina = 'produtos';
$titulo = $id ? 'Editar produto' : 'Novo produto';
require __DIR__ . '/../includes/header.php';
?>
<h1 class="h3 mb-3"><?= e($titulo) ?></h1>
<?php foreach ($erros as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" class="card"><div class="card-body row g-3">
    <?= csrf_campo() ?>
    <div class="col-md-8">
        <label class="form-label">Nome *</label>
        <input class="form-control" name="nome" maxlength="120" required value="<?= e($p['nome']) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Valor unitário (R$) *</label>
        <input class="form-control" name="valor" inputmode="decimal" required value="<?= e($p['valor']) ?>">
    </div>
    <div class="col-md-8">
        <label class="form-label">Descrição</label>
        <input class="form-control" name="descricao" maxlength="255" value="<?= e($p['descricao']) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Peso (kg) *</label>
        <input class="form-control" name="peso_kg" inputmode="decimal" required value="<?= e($p['peso_kg']) ?>">
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Salvar</button>
        <a class="btn btn-outline-secondary" href="index.php">Cancelar</a>
    </div>
</div></form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
