<?php
/**
 * ============================================================
 *  API — Table `offres`
 *  GET  /api_offres.php          → toutes les offres
 *  GET  /api_offres.php?id=2     → une offre précise
 * ============================================================
 */
require_once __DIR__ . '/config.php';

if (!function_exists('jsonResponse')) {
    function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

if (!function_exists('getPDO')) {
    function getPDO(): PDO
    {
        $dbHost = defined('DB_HOST') ? DB_HOST : (getenv('DB_HOST') ?: '127.0.0.1');
        $dbName = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: 'algerie_telecom');
        $dbUser = defined('DB_USER') ? DB_USER : (getenv('DB_USER') ?: 'root');
        $dbPass = defined('DB_PASS') ? DB_PASS : (getenv('DB_PASS') ?: '');
        $charset = defined('DB_CHARSET') ? DB_CHARSET : (getenv('DB_CHARSET') ?: 'utf8mb4');

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $dbHost, $dbName, $charset);
        return new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}

try {
    $pdo = getPDO();

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM offres WHERE id = ?");
        $stmt->execute([(int)$_GET['id']]);
        $row = $stmt->fetch();
        jsonResponse($row ?: [], $row ? 200 : 404);
    }

    $stmt = $pdo->query("SELECT * FROM offres ORDER BY prix ASC");
    jsonResponse(['offres' => $stmt->fetchAll()]);

} catch (PDOException $e) {
    error_log('[AT API offres] '.$e->getMessage());
    jsonResponse(['error' => 'Erreur serveur'], 500);
}
