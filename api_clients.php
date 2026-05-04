<?php
/**
 * ============================================================
 *  API — Table `client`
 *  POST /api_clients.php         → créer un client
 *  GET  /api_clients.php?email=  → vérifier si email existe
 * ============================================================
 */
require_once 'config.php';

function nettoyer(string $v): string {
    return htmlspecialchars(strip_tags(trim($v)), ENT_QUOTES, 'UTF-8');
}

try {
    $pdo = getPDO();

    // ── GET : liste tous les clients OU vérification email ───
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Vérification email
        if (isset($_GET['email'])) {
            if (empty($_GET['email'])) {
                jsonResponse(['error' => 'Paramètre email manquant'], 400);
            }
            $stmt = $pdo->prepare("SELECT id FROM client WHERE email = ?");
            $stmt->execute([nettoyer($_GET['email'])]);
            jsonResponse(['exists' => (bool)$stmt->fetch()]);
        }

        // Liste tous les clients
        $stmt = $pdo->query("SELECT c.*, w.nom AS wilaya_nom, o.nom AS offre_nom
                              FROM client c
                              LEFT JOIN wilaya w ON c.wilaya_id = w.id
                              LEFT JOIN offre  o ON c.offre_id  = o.id
                              ORDER BY c.id DESC");
        jsonResponse(['clients' => $stmt->fetchAll()]);
    }

    // ── POST : inscription client ─────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $prenom    = nettoyer($body['prenom']    ?? '');
        $nom       = nettoyer($body['nom']       ?? '');
        $email     = nettoyer($body['email']     ?? '');
        $telephone = nettoyer($body['telephone'] ?? '');
        $adresse   = nettoyer($body['adresse']   ?? '');
        $wilaya_id = (int)($body['wilaya_id']    ?? 0);
        $offre_id  = (int)($body['offre_id']     ?? 0);

        // Validations basiques
        $erreurs = [];
        if (strlen($prenom) < 2)                          $erreurs[] = 'Prénom invalide';
        if (strlen($nom)    < 2)                          $erreurs[] = 'Nom invalide';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))   $erreurs[] = 'Email invalide';
        if ($wilaya_id < 1)                               $erreurs[] = 'Wilaya requise';
        if ($offre_id  < 1)                               $erreurs[] = 'Offre requise';

        if (!empty($erreurs)) {
            jsonResponse(['errors' => $erreurs], 422);
        }

        // Vérifier doublon email
        $chk = $pdo->prepare("SELECT id FROM client WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) {
            jsonResponse(['error' => 'Cet email est déjà enregistré'], 409);
        }

        $stmt = $pdo->prepare("
            INSERT INTO client
                (prenom, nom, email, telephone, adresse, wilaya_id, offre_id, date_creation)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$prenom, $nom, $email, $telephone, $adresse, $wilaya_id, $offre_id]);

        jsonResponse([
            'success' => true,
            'message' => 'Client créé avec succès',
            'id'      => (int)$pdo->lastInsertId(),
        ], 201);
    }

    jsonResponse(['error' => 'Méthode non supportée'], 405);

} catch (PDOException $e) {
    error_log('[AT API clients] '.$e->getMessage());
    jsonResponse(['error' => 'Erreur serveur'], 500);
}