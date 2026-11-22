<?php
require_once __DIR__ . '/../includes/helpers.php';

$transportadores = consulta('SELECT id, nome FROM transportadores ORDER BY nome')->fetchAll();
$enderecos = consulta('SELECT id, apelido, cidade, uf FROM enderecos ORDER BY apelido')->fetchAll();
$produtos = consulta('SELECT id, nome, peso_kg, valor FROM produtos ORDER BY nome')->fetchAll();

$f = ['transportador_id' => '', 'origem_id' => '', 'destino_id' => '', 'data_frete' => date('Y-m-d'), 'observacao' => ''];
$itens = [['produto_id' => '', 'quantidade' => 1]];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigir_post_csrf();
    $f = [
        'transportador_id' => (int)($_POST['transportador_id'] ?? 0),
        'origem_id' => (int)($_POST['origem_id'] ?? 0),
        'destino_id' => (int)($_POST['destino_id'] ?? 0),
        'data_frete' => $_POST['data_frete'] ?? '',
        'observacao' => trim($_POST['observacao'] ?? ''),
    ];

    // Campos dinâmicos: produto_id[] e quantidade[] (mesmo produto repetido é somado)
    $itens = [];
    foreach (($_POST['produto_id'] ?? []) as $i => $pid) {
        $pid = (int)$pid;
        $qtd = (int)($_POST['quantidade'][$i] ?? 0);
        if ($pid <= 0) continue;
        if ($qtd < 1) { $erros[] = 'A quantidade deve ser no mínimo 1.'; continue; }
        $itens[$pid] = ($itens[$pid] ?? 0) + $qtd;
    }
    $lista = [];
    foreach ($itens as $pid => $qtd) { $lista[] = ['produto_id' => $pid, 'quantidade' => $qtd]; }
    $itens = $lista ?: [['produto_id' => '', 'quantidade' => 1]];

    if (!$f['transportador_id']) $erros[] = 'Selecione o transportador.';
    if (!$f['origem_id']) $erros[] = 'Selecione o endereço de origem.';
    if (!$f['destino_id']) $erros[] = 'Selecione o endereço de destino.';
    if ($f['origem_id'] && $f['origem_id'] === $f['destino_id']) $erros[] = 'Origem e destino devem ser diferentes.';
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $f['data_frete'])) $erros[] = 'Data inválida.';
    if (!$lista) $erros[] = 'Adicione pelo menos um produto ao frete.';

    if (!$erros) {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $valor = 0; $peso = 0;
            $info = [];
            foreach ($lista as $it) {
                $p = consulta('SELECT valor, peso_kg FROM produtos WHERE id = ?', [$it['produto_id']])->fetch();
                if (!$p) throw new RuntimeException('Produto inexistente.');
                $valor += $p['valor'] * $it['quantidade'];
                $peso += $p['peso_kg'] * $it['quantidade'];
            }
            consulta('INSERT INTO fretes (transportador_id, origem_id, destino_id, data_frete, observacao, valor_total, peso_total) VALUES (?,?,?,?,?,?,?)',
                [$f['transportador_id'], $f['origem_id'], $f['destino_id'], $f['data_frete'], $f['observacao'], $valor, $peso]);
            $freteId = (int)$pdo->lastInsertId();
            foreach ($lista as $it) {
                consulta('INSERT INTO frete_produtos (frete_id, produto_id, quantidade) VALUES (?,?,?)',
                    [$freteId, $it['produto_id'], $it['quantidade']]);
            }
            $pdo->commit();
            flash('success', "Frete #$freteId gerado com sucesso.");
            redirecionar("fretes/ver.php?id=$freteId");
        } catch (Throwable $ex) {
            $pdo->rollBack();
            $erros[] = 'Não foi possível salvar o frete: ' . $ex->getMessage();
        }
    }
}

$pagina = 'fretes';
$titulo = 'Novo frete';
$scripts = ['frete.js'];
require __DIR__ . '/../includes/header.php';

$opcoesProdutos = function ($selecionado) use ($produtos) {
    $html = '<option value="">Selecione o produto...</option>';
    foreach ($produtos as $p) {
        $sel = (string)$selecionado === (string)$p['id'] ? ' selected' : '';
        $html .= '<option value="' . $p['id'] . '" data-peso="' . $p['peso_kg'] . '" data-valor="' . $p['valor'] . '"' . $sel . '>'
              . e($p['nome']) . ' (' . moeda($p['valor']) . ')</option>';
    }
    return $html;
};
?>
<h1 class="h3 mb-3">Novo frete</h1>
<?php foreach (array_unique($erros) as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>

<?php if (!$transportadores || count($enderecos) < 2 || !$produtos): ?>
    <div class="alert alert-warning">
        Para gerar um frete, cadastre antes pelo menos: 1 transportador, 2 endereços (origem e destino) e 1 produto.
    </div>
<?php endif; ?>

<form method="post" class="card"><div class="card-body row g-3">
    <?= csrf_campo() ?>
    <div class="col-md-4">
        <label class="form-label">Transportador *</label>
        <select class="form-select" name="transportador_id" required>
            <option value="">Selecione...</option>
            <?php foreach ($transportadores as $t): ?>
                <option value="<?= $t['id'] ?>" <?= (int)$f['transportador_id'] === (int)$t['id'] ? 'selected' : '' ?>><?= e($t['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Origem *</label>
        <select class="form-select" name="origem_id" required>
            <option value="">Selecione...</option>
            <?php foreach ($enderecos as $a): ?>
                <option value="<?= $a['id'] ?>" <?= (int)$f['origem_id'] === (int)$a['id'] ? 'selected' : '' ?>><?= e($a['apelido']) ?> &ndash; <?= e($a['cidade']) ?>/<?= e($a['uf']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Destino *</label>
        <select class="form-select" name="destino_id" required>
            <option value="">Selecione...</option>
            <?php foreach ($enderecos as $a): ?>
                <option value="<?= $a['id'] ?>" <?= (int)$f['destino_id'] === (int)$a['id'] ? 'selected' : '' ?>><?= e($a['apelido']) ?> &ndash; <?= e($a['cidade']) ?>/<?= e($a['uf']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Data do frete *</label>
        <input type="date" class="form-control" name="data_frete" required value="<?= e($f['data_frete']) ?>">
    </div>
    <div class="col-md-9">
        <label class="form-label">Observação</label>
        <input class="form-control" name="observacao" maxlength="255" value="<?= e($f['observacao']) ?>">
    </div>

    <div class="col-12"><hr class="my-1"><h2 class="h5">Produtos transportados</h2></div>
    <div class="col-12" id="lista-produtos">
        <?php foreach ($itens as $it): ?>
            <div class="linha-produto row g-2 mb-2 align-items-end">
                <div class="col-md-7">
                    <label class="form-label small">Produto</label>
                    <select class="form-select" name="produto_id[]"><?= $opcoesProdutos($it['produto_id']) ?></select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Quantidade</label>
                    <input type="number" min="1" class="form-control" name="quantidade[]" value="<?= (int)$it['quantidade'] ?>">
                </div>
                <div class="col-6 col-md-2 text-end">
                    <button type="button" class="btn btn-outline-danger btn-remover"><i class="bi bi-x-lg"></i> Remover</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="col-12">
        <button type="button" class="btn btn-outline-primary" id="btn-adicionar"><i class="bi bi-plus-lg"></i> Adicionar produto</button>
    </div>

    <div class="col-12">
        <div class="alert alert-secondary mb-0 d-flex justify-content-between flex-wrap">
            <span>Peso total: <strong id="total-peso">0,000 kg</strong></span>
            <span>Valor total da carga: <strong id="total-valor">R$ 0,00</strong></span>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-primary"><i class="bi bi-truck"></i> Gerar frete</button>
        <a class="btn btn-outline-secondary" href="index.php">Cancelar</a>
    </div>
</div></form>

<!-- Modelo usado pelo JavaScript para criar novas linhas de produto -->
<template id="modelo-produto">
    <div class="linha-produto row g-2 mb-2 align-items-end">
        <div class="col-md-7">
            <label class="form-label small">Produto</label>
            <select class="form-select" name="produto_id[]"><?= $opcoesProdutos('') ?></select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label small">Quantidade</label>
            <input type="number" min="1" class="form-control" name="quantidade[]" value="1">
        </div>
        <div class="col-6 col-md-2 text-end">
            <button type="button" class="btn btn-outline-danger btn-remover"><i class="bi bi-x-lg"></i> Remover</button>
        </div>
    </div>
</template>
<?php require __DIR__ . '/../includes/footer.php'; ?>
