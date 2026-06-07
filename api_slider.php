<?php
require_once __DIR__ . '/config.php';

if (!function_exists('jsonResponse')) {
    function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('getPDO')) {
    function getPDO(): PDO
    {
        $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
        $db = defined('DB_NAME') ? DB_NAME : 'algerie_telecom';
        $user = defined('DB_USER') ? DB_USER : 'root';
        $pass = defined('DB_PASS') ? DB_PASS : '';
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}

try {
    $pdo = getPDO();

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM slider_image WHERE id = ?");
        $stmt->execute([(int)$_GET['id']]);
        $row = $stmt->fetch();
        jsonResponse($row ?: [], $row ? 200 : 404);
    }

    $stmt = $pdo->query("SELECT * FROM slider_image ORDER BY ordre ASC");
    jsonResponse(['images' => $stmt->fetchAll()]);

} catch (PDOException $e) {
    error_log('[AT API slider] '.$e->getMessage());
    jsonResponse(['error' => 'Erreur serveur'], 500);
}
