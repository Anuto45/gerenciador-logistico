<?php
require_once __DIR__ . '/../includes/helpers.php';
// frete_produtos é removido em cascata (ON DELETE CASCADE)
excluir_registro('fretes', (int)($_POST['id'] ?? 0), 'fretes/index.php', 'Frete');
