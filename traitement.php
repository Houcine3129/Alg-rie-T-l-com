<?php
/**
 * ============================================================
 *  ALGÉRIE TÉLÉCOM — Traitement formulaire de contact
 * ============================================================
 */
declare(strict_types=1);
require_once 'php/config.php';
session_start();

define('REDIRECT_SUCCES', 'contact.html?statut=succes');
define('REDIRECT_ERREUR',  'contact.html?statut=erreur');

// Vérifier que c'est bien un POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

// Honeypot anti-spam
if (!empty($_POST['hp_site'])) {
    header('Location: ' . REDIRECT_SUCCES);
    exit;
}

function nettoyer(string $v): string {
    return htmlspecialchars(strip_tags(trim($v)), ENT_QUOTES, 'UTF-8');
}

// ── Récupération des champs ─────────────────────────────────
$prenom    = nettoyer($_POST['prenom']    ?? '');
$nom       = nettoyer($_POST['nom']       ?? '');
$email     = nettoyer($_POST['email']     ?? '');
$telephone = nettoyer($_POST['telephone'] ?? '');
$sujet     = nettoyer($_POST['sujet']     ?? '');
$message   = nettoyer($_POST['message']   ?? '');

// ── Validation ─────────────────────────────────────────────
$erreurs = [];

if (strlen($prenom) < 2 || strlen($prenom) > 60)
    $erreurs[] = 'Prénom invalide';

if (strlen($nom) < 2 || strlen($nom) > 60)
    $erreurs[] = 'Nom invalide';

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $erreurs[] = 'Email invalide';

if ($telephone !== '' && !preg_match('/^[\d\s+\-]{7,20}$/', $telephone))
    $erreurs[] = 'Téléphone invalide';

if (!in_array($sujet, ['souscription','resiliation','incident','facturation','entreprise','autre'], true))
    $erreurs[] = 'Sujet invalide';

if (strlen($message) < 20 || strlen($message) > 2000)
    $erreurs[] = 'Message invalide (20 à 2000 caractères)';

if (!empty($erreurs)) {
    header('Location: ' . REDIRECT_ERREUR . '&code=validation');
    exit;
}

// ── Insertion en base ──────────────────────────────────────
try {
    $pdo  = getPDO();
    $stmt = $pdo->prepare("
        INSERT INTO contact (prenom, nom, email, telephone, sujet, message, date_envoi, ip_expediteur)
        VALUES (:prenom, :nom, :email, :telephone, :sujet, :message, NOW(), :ip)
    ");
    $stmt->execute([
        ':prenom'    => $prenom,
        ':nom'       => $nom,
        ':email'     => $email,
        ':telephone' => $telephone ?: null,
        ':sujet'     => $sujet,
        ':message'   => $message,
        ':ip'        => $_SERVER['REMOTE_ADDR'] ?? 'inconnue',
    ]);

    header('Location: ' . REDIRECT_SUCCES);
    exit;

} catch (PDOException $e) {
    error_log('[AT Contact] ' . $e->getMessage());
    header('Location: ' . REDIRECT_ERREUR . '&code=db');
    exit;
}
