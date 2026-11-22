<?php
require_once __DIR__ . '/helpers.php';
$base = base_url();
$titulo = $titulo ?? 'Gerenciador Logístico';
$menu = [
    'inicio'          => ['Início', '/index.php'],
    'produtos'        => ['Produtos', '/produtos/index.php'],
    'enderecos'       => ['Endereços', '/enderecos/index.php'],
    'transportadores' => ['Transportadores', '/transportadores/index.php'],
    'fretes'          => ['Fretes', '/fretes/index.php'],
    'relatorios'      => ['Relatório diário', '/relatorios/index.php'],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> | Gerenciador Logístico</title>
    <link href="<?= $base ?>/assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= $base ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= $base ?>/index.php"><i class="bi bi-truck"></i> Logística</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto">
                <?php foreach ($menu as $chave => [$rotulo, $url]): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($pagina ?? '') === $chave ? 'active fw-bold' : '' ?>"
                           href="<?= $base . $url ?>"><?= $rotulo ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
    <?php foreach (pegar_flash() as $aviso): ?>
        <div class="alert alert-<?= e($aviso['tipo']) ?> alert-dismissible fade show" role="alert">
            <?= e($aviso['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
