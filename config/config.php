<?php
/**
 * Configuração do sistema.
 * Padrão: MySQL do XAMPP (usuário root, sem senha, banco "logistica").
 * Para testes sem MySQL, defina a variável de ambiente DB_DRIVER=sqlite.
 */
return [
    'driver' => getenv('DB_DRIVER') ?: 'mysql',
    'host'   => '127.0.0.1',
    'porta'  => 3307, // MySQL do XAMPP (porta alterada porque a 3306 estava ocupada). Padrão do XAMPP: 3306
    'banco'  => 'logistica',
    'usuario' => 'root',
    'senha'  => '',
    'sqlite_arquivo' => __DIR__ . '/../database/teste.sqlite',
];
