<?php
session_start();
require_once __DIR__ . '/db.php';

/** Caminho base da aplicação (funciona em /htdocs/gerenciador-logistico ou na raiz). */
function base_url(): string
{
    $raiz = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '');
    $app  = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    return rtrim(str_replace($raiz, '', $app), '/');
}

/** Escapa HTML para evitar XSS. */
function e($valor): string
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function moeda($valor): string
{
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}

function data_br(string $data): string
{
    return date('d/m/Y', strtotime($data));
}

/** Converte "1.234,56" ou "1234.56" em float. */
function para_decimal($texto): float
{
    $t = trim((string)$texto);
    if (strpos($t, ',') !== false) {
        $t = str_replace('.', '', $t);
        $t = str_replace(',', '.', $t);
    }
    return (float)$t;
}

function redirecionar(string $caminho): void
{
    header('Location: ' . base_url() . '/' . ltrim($caminho, '/'));
    exit;
}

/** Mensagens "flash" (aparecem uma vez após o redirecionamento). */
function flash(string $tipo, string $mensagem): void
{
    $_SESSION['flash'][] = ['tipo' => $tipo, 'msg' => $mensagem];
}

function pegar_flash(): array
{
    $m = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $m;
}

/** CSRF: token de sessão para formulários POST. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function exigir_post_csrf(): void
{
    $esperado = $_SESSION['csrf'] ?? '';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $esperado === ''
        || !hash_equals($esperado, $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Requisição inválida.');
    }
}

/** Remove um registro tratando violação de chave estrangeira (item em uso). */
function excluir_registro(string $tabela, int $id, string $voltar, string $rotulo): void
{
    exigir_post_csrf();
    try {
        consulta("DELETE FROM {$tabela} WHERE id = ?", [$id]);
        flash('success', "$rotulo excluído com sucesso.");
    } catch (PDOException $e) {
        flash('danger', "Não é possível excluir: $rotulo está vinculado a algum frete.");
    }
    redirecionar($voltar);
}

const UFS = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA',
             'PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
