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

    // ── POST : inscription ou connexion client ────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $action = $body['action'] ?? 'signup';

        // ── LOGIN ──────────────────────────────────────────────
        if ($action === 'login') {
            $email    = nettoyer($body['email']    ?? '');
            $password = $body['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
                jsonResponse(['error' => 'Email ou mot de passe invalide'], 422);
            }

            $stmt = $pdo->prepare("SELECT id, prenom, nom, email, telephone, motdepasse FROM client WHERE email = ?");
            $stmt->execute([$email]);
            $client = $stmt->fetch();

            if (!$client || !password_verify($password, $client['motdepasse'])) {
                jsonResponse(['error' => 'Email ou mot de passe incorrect'], 401);
            }

            jsonResponse([
                'success' => true,
                'client'  => [
                    'id'        => (int)$client['id'],
                    'prenom'    => $client['prenom'],
                    'nom'       => $client['nom'],
                    'email'     => $client['email'],
                    'telephone' => $client['telephone'],
                ],
            ]);
        }

        // ── SIGNUP ─────────────────────────────────────────────
        $prenom    = nettoyer($body['prenom']    ?? '');
        $nom       = nettoyer($body['nom']       ?? '');
        $email     = nettoyer($body['email']     ?? '');
        $telephone = nettoyer($body['telephone'] ?? '');
        $password  = $body['password']           ?? '';
        $adresse   = nettoyer($body['adresse']   ?? '');
        $wilaya_id = (int)($body['wilaya_id']    ?? 0);

        // Validations basiques
        $erreurs = [];
        if (strlen($prenom) < 2)                          $erreurs[] = 'Prénom invalide';
        if (strlen($nom)    < 2)                          $erreurs[] = 'Nom invalide';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))   $erreurs[] = 'Email invalide';
        if (strlen($password) < 6)                        $erreurs[] = 'Mot de passe (min 6 caractères)';
        if ($wilaya_id < 1)                               $erreurs[] = 'Wilaya requise';

        if (!empty($erreurs)) {
            jsonResponse(['errors' => $erreurs], 422);
        }

        // Vérifier doublon email
        $chk = $pdo->prepare("SELECT id FROM client WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) {
            jsonResponse(['error' => 'Cet email est déjà enregistré'], 409);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO client
                (prenom, nom, email, motdepasse, telephone, adresse, wilaya_id, offre_id, date_creation)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, NULL, NOW())
        ");
        $stmt->execute([$prenom, $nom, $email, $hash, $telephone, $adresse, $wilaya_id]);

        jsonResponse([
            'success' => true,
            'message' => 'Inscription réussie',
            'id'      => (int)$pdo->lastInsertId(),
        ], 201);
    }

    jsonResponse(['error' => 'Méthode non supportée'], 405);

} catch (PDOException $e) {
    error_log('[AT API clients] '.$e->getMessage());
    jsonResponse(['error' => 'Erreur serveur'], 500);
}