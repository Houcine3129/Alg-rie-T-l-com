<?php
/**
 * ============================================================
 *  API — Table `service`
 *  GET  /api_services.php        → liste tous les services
 *  GET  /api_services.php?id=3   → un seul service
 * ============================================================
 */
require_once 'config.php';

try {
    $pdo = getPDO();

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM service WHERE id = ?");
        $stmt->execute([(int)$_GET['id']]);
        $row = $stmt->fetch();
        jsonResponse($row ?: [], $row ? 200 : 404);
    }

    $stmt = $pdo->query("SELECT * FROM service ORDER BY id ASC");
    jsonResponse(['services' => $stmt->fetchAll()]);

} catch (PDOException $e) {
    error_log('[AT API services] '.$e->getMessage());
    jsonResponse(['error' => 'Erreur serveur'], 500);
}