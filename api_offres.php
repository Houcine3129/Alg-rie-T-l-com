<?php
/**
 * ============================================================
 *  API — Table `offre`
 *  GET  /api_offres.php          → toutes les offres
 *  GET  /api_offres.php?id=2     → une offre précise
 * ============================================================
 */
require_once 'config.php';

try {
    $pdo = getPDO();

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM offre WHERE id = ?");
        $stmt->execute([(int)$_GET['id']]);
        $row = $stmt->fetch();
        jsonResponse($row ?: [], $row ? 200 : 404);
    }

    $stmt = $pdo->query("SELECT * FROM offre ORDER BY prix ASC");
    jsonResponse(['offres' => $stmt->fetchAll()]);

} catch (PDOException $e) {
    error_log('[AT API offres] '.$e->getMessage());
    jsonResponse(['error' => 'Erreur serveur'], 500);
}
