<?php
require_once __DIR__ . '/../includes/helpers.php';
excluir_registro('produtos', (int)($_POST['id'] ?? 0), 'produtos/index.php', 'Produto');
