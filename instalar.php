<?php
/**
 * Instalador: cria o banco "logistica" e as tabelas no MySQL do XAMPP.
 * Acesse http://localhost/gerenciador-logistico/instalar.php
 * Use ?exemplo=1 para também inserir dados de exemplo.
 */
$cfg = require __DIR__ . '/config/config.php';
$opcoes = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

function executar_script(PDO $pdo, string $arquivo): void
{
    foreach (array_filter(array_map('trim', explode(';', file_get_contents($arquivo)))) as $sql) {
        // remove comentários de linha
        $sql = trim(preg_replace('/^--.*$/m', '', $sql));
        if ($sql !== '') {
            $pdo->exec($sql);
        }
    }
}

try {
    if ($cfg['driver'] === 'sqlite') {
        $pdo = new PDO('sqlite:' . $cfg['sqlite_arquivo'], null, null, $opcoes);
        executar_script($pdo, __DIR__ . '/database/schema_sqlite.sql');
    } else {
        $pdo = new PDO("mysql:host={$cfg['host']};port={$cfg['porta']};charset=utf8mb4", $cfg['usuario'], $cfg['senha'], $opcoes);
        executar_script($pdo, __DIR__ . '/database/schema.sql');
        $pdo->exec("USE {$cfg['banco']}");
    }
    if (isset($_GET['exemplo'])) {
        $total = $pdo->query('SELECT COUNT(*) FROM produtos')->fetchColumn();
        if ($total == 0) {
            executar_script($pdo, __DIR__ . '/database/dados_exemplo.sql');
        }
    }
    $mensagem = 'Banco e tabelas criados com sucesso!';
    $ok = true;
} catch (PDOException $e) {
    $mensagem = 'Erro: ' . $e->getMessage() . ' — verifique se o MySQL está iniciado no XAMPP.';
    $ok = false;
}
?>
<!DOCTYPE html>
<html lang="pt-br"><head><meta charset="UTF-8"><title>Instalação</title>
<link href="assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-5">
    <div class="alert alert-<?= $ok ? 'success' : 'danger' ?>"><?= htmlspecialchars($mensagem) ?></div>
    <?php if ($ok): ?>
        <a class="btn btn-primary" href="index.php">Abrir o sistema</a>
        <a class="btn btn-outline-secondary" href="instalar.php?exemplo=1">Inserir dados de exemplo</a>
    <?php endif; ?>
</body></html>
