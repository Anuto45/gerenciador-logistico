<?php
require_once __DIR__ . '/../includes/helpers.php';
excluir_registro('transportadores', (int)($_POST['id'] ?? 0), 'transportadores/index.php', 'Transportador');
