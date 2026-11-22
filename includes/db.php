<?php
/** Conexão PDO única (padrão singleton simples). */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $cfg = require __DIR__ . '/../config/config.php';
    $opcoes = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        if ($cfg['driver'] === 'sqlite') {
            $pdo = new PDO('sqlite:' . $cfg['sqlite_arquivo'], null, null, $opcoes);
            $pdo->exec('PRAGMA foreign_keys = ON');
        } else {
            $dsn = "mysql:host={$cfg['host']};port={$cfg['porta']};dbname={$cfg['banco']};charset=utf8mb4";
            $pdo = new PDO($dsn, $cfg['usuario'], $cfg['senha'], $opcoes);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        exit('<h3>Não foi possível conectar ao banco.</h3>'
            . '<p>Inicie o MySQL no XAMPP e acesse <a href="instalar.php">instalar.php</a> '
            . 'para criar o banco e as tabelas.</p>');
    }
    return $pdo;
}

/** Atalho: executa uma consulta preparada e devolve o statement. */
function consulta(string $sql, array $params = []): PDOStatement
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st;
}
