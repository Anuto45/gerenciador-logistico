<?php
require_once __DIR__ . '/../includes/helpers.php';
excluir_registro('enderecos', (int)($_POST['id'] ?? 0), 'enderecos/index.php', 'Endereço');
