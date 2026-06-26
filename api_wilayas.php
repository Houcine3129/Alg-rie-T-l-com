<?php
/**
 * ============================================================
 *  API — Table `wilaya`
 *  GET  /api_wilayas.php         → toutes les wilayas
 *  GET  /api_wilayas.php?id=31   → une wilaya précise
 * ============================================================
 */
require_once 'config.php';

try {
    $pdo = getPDO();

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM wilaya WHERE id = ?");
        $stmt->execute([(int)$_GET['id']]);
        $row = $stmt->fetch();
        jsonResponse($row ?: [], $row ? 200 : 404);
    }

    $stmt = $pdo->query("SELECT * FROM wilaya ORDER BY code ASC");
    jsonResponse(['wilayas' => $stmt->fetchAll()]);

} catch (PDOException $e) {
    error_log('[AT API wilayas] '.$e->getMessage());
    jsonResponse(['error' => 'Erreur serveur'], 500);
}