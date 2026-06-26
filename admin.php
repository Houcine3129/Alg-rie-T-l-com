<?php
/**
 * ============================================================
 *  ALGÉRIE TÉLÉCOM — Panel Admin v2
 *  Fonctionnalités :
 *    1. Authentification sécurisée (session PHP)
 *    2. Liste des messages de contact (tri date DESC)
 *    3. Détail complet d'un message sélectionné
 *    4. Suppression unitaire ou en lot des messages
 *    5. Déconnexion sécurisée
 * ============================================================
 */
declare(strict_types=1);
require_once __DIR__ . '/php/config.php';

session_start();

// ── CSRF helpers ─────────────────────────────────────────────
function genCSRF(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function checkCSRF(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('CSRF validation échouée.');
    }
}

// ── Déconnexion ───────────────────────────────────────────────
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: admin.php');
    exit;
}

// ── Authentification ──────────────────────────────────────────
$errLogin = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $motdepasse  = trim($_POST['motdepasse']  ?? '');
    try {
        $pdo  = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE identifiant = ? LIMIT 1");
        $stmt->execute([$identifiant]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($motdepasse, $admin['motdepasse'])) {
            session_regenerate_id(true);
            $_SESSION['at_admin']    = (int)$admin['id'];
            $_SESSION['at_username'] = $admin['identifiant'];
            genCSRF();
            header('Location: admin.php');
            exit;
        }
        $errLogin = 'Identifiant ou mot de passe incorrect.';
    } catch (PDOException $e) {
        $errLogin = 'Erreur de connexion à la base de données.';
    }
}

// ── Requêtes POST nécessitant une session ─────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['at_admin'])) {
    checkCSRF();

    // Suppression en lot (checkboxes)
    if (($_POST['action'] ?? '') === 'delete_bulk') {
        $ids = array_filter(array_map('intval', $_POST['ids'] ?? []));
        if (!empty($ids)) {
            try {
                $pdo = getPDO();
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $pdo->prepare("DELETE FROM contact WHERE id IN ($placeholders)");
                $stmt->execute($ids);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => count($ids) . ' message(s) supprimé(s).'];
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erreur lors de la suppression.'];
            }
        }
        header('Location: admin.php');
        exit;
    }

    // Suppression unitaire
    if (($_POST['action'] ?? '') === 'delete_one') {
        $id = (int)($_POST['contact_id'] ?? 0);
        if ($id > 0) {
            try {
                $pdo = getPDO();
                $pdo->prepare("DELETE FROM contact WHERE id = ?")->execute([$id]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Message supprimé.'];
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erreur lors de la suppression.'];
            }
        }
        header('Location: admin.php');
        exit;
    }

    // Marquer traité
    if (($_POST['action'] ?? '') === 'traiter') {
        $id = (int)($_POST['contact_id'] ?? 0);
        if ($id > 0) {
            try {
                $pdo = getPDO();
                $pdo->prepare("UPDATE contact SET traite=1, date_traitement=NOW() WHERE id=?")->execute([$id]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Message marqué comme traité.'];
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erreur lors de la mise à jour.'];
            }
        }
        header('Location: admin.php' . (isset($_GET['detail']) ? '?detail=' . $id : ''));
        exit;
    }
}

// ── Chargement données (si connecté) ─────────────────────────
$contacts   = [];
$stats      = [];
$detailMsg  = null;
$flash      = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (isset($_SESSION['at_admin'])) {
    $csrf = genCSRF();
    try {
        $pdo = getPDO();

        // Détail d'un message
        if (isset($_GET['detail'])) {
            $stmt = $pdo->prepare("SELECT * FROM contact WHERE id = ?");
            $stmt->execute([(int)$_GET['detail']]);
            $detailMsg = $stmt->fetch() ?: null;
        }

        // Liste messages
        $contacts = $pdo->query(
            "SELECT * FROM contact ORDER BY date_envoi DESC"
        )->fetchAll();

        // Stats dashboard
        $stats = [
            'total'      => (int)$pdo->query("SELECT COUNT(*) FROM contact")->fetchColumn(),
            'non_traite' => (int)$pdo->query("SELECT COUNT(*) FROM contact WHERE traite=0")->fetchColumn(),
            'traite'     => (int)$pdo->query("SELECT COUNT(*) FROM contact WHERE traite=1")->fetchColumn(),
            'clients'    => (int)$pdo->query("SELECT COUNT(*) FROM client")->fetchColumn(),
        ];

    } catch (PDOException $e) {
        $dbError = 'Erreur base de données : ' . htmlspecialchars($e->getMessage());
    }
}

// ── Helpers ───────────────────────────────────────────────────
function h(mixed $v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }

function sujetLabel(string $s): array {
    return match($s) {
        'souscription' => ['Souscription',  'badge-blue'],
        'resiliation'  => ['Résiliation',   'badge-red'],
        'incident'     => ['Incident',      'badge-orange'],
        'facturation'  => ['Facturation',   'badge-yellow'],
        'entreprise'   => ['Entreprise',    'badge-purple'],
        default        => ['Autre',         'badge-gray'],
    };
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administration — Algérie Télécom</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* ════════════════════════════════════════════════════════
       ADMIN PANEL — styles dédiés
    ════════════════════════════════════════════════════════ */

    /* ── Layout ── */
    .adm-wrap {
      max-width: 1240px;
      margin: 0 auto;
      padding: calc(var(--nav-h) + 40px) 24px 80px;
      min-height: 100vh;
    }

    /* ── Page header ── */
    .adm-page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 40px;
      padding-bottom: 24px;
      border-bottom: 1px solid var(--border);
    }
    .adm-page-header h1 {
      font-size: clamp(1.4rem, 3vw, 1.9rem);
      color: var(--text-primary);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .adm-page-header h1 span.adm-dot {
      width: 10px; height: 10px;
      background: var(--at-green);
      border-radius: 50%;
      display: inline-block;
      box-shadow: 0 0 0 4px var(--at-green-dim);
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%,100% { box-shadow: 0 0 0 4px var(--at-green-dim); }
      50%      { box-shadow: 0 0 0 8px transparent; }
    }
    .adm-user-chip {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.875rem;
      color: var(--text-muted);
    }
    .adm-user-chip strong {
      color: var(--text-primary);
    }
    .adm-user-chip a {
      color: var(--at-green);
      text-decoration: none;
      font-weight: 500;
    }

    /* ── Flash message ── */
    .adm-flash {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 20px;
      border-radius: var(--r-md);
      margin-bottom: 28px;
      font-size: 0.9rem;
      font-weight: 500;
      animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
      from { opacity:0; transform:translateY(-8px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .adm-flash.success {
      background: rgba(0,160,75,0.12);
      border: 1px solid rgba(0,160,75,0.3);
      color: var(--at-green-light);
    }
    .adm-flash.error {
      background: rgba(220,60,60,0.1);
      border: 1px solid rgba(220,60,60,0.25);
      color: #e05050;
    }
    .adm-flash svg { width: 18px; height: 18px; flex-shrink: 0; }

    /* ── Stats row ── */
    .adm-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 18px;
      margin-bottom: 44px;
    }
    .adm-stat-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--r-lg);
      padding: 24px 20px 20px;
      text-align: center;
      transition: box-shadow var(--t), transform var(--t);
    }
    .adm-stat-card:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }
    .adm-stat-card .num {
      font-family: 'Syne', sans-serif;
      font-size: 2.4rem;
      font-weight: 800;
      line-height: 1;
      color: var(--at-green-light);
    }
    .adm-stat-card.warn .num { color: #f59e0b; }
    .adm-stat-card.muted .num { color: var(--text-muted); }
    .adm-stat-card .lbl {
      font-size: 0.78rem;
      color: var(--text-muted);
      margin-top: 6px;
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .adm-stat-card .mini-bar {
      height: 3px;
      border-radius: 2px;
      background: var(--at-green-dim);
      margin-top: 14px;
      overflow: hidden;
    }
    .adm-stat-card .mini-bar span {
      display: block;
      height: 100%;
      background: var(--at-green);
      border-radius: 2px;
    }

    /* ── Section card ── */
    .adm-section {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--r-lg);
      overflow: hidden;
    }
    .adm-section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      background: var(--bg-subtle);
    }
    .adm-section-header h2 {
      margin: 0;
      font-size: 1rem;
      font-weight: 700;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .adm-section-header h2 svg {
      width: 18px; height: 18px;
      fill: var(--at-green);
    }

    /* ── Toolbar (select all + bulk delete) ── */
    .adm-toolbar {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .adm-toolbar label {
      font-size: 0.85rem;
      color: var(--text-secondary);
      cursor: pointer;
      user-select: none;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .adm-toolbar label input[type="checkbox"] { accent-color: var(--at-green); }
    #btn-delete-selected {
      display: none;
    }
    #btn-delete-selected.visible {
      display: inline-flex;
    }

    /* ── Table ── */
    .adm-table-wrap { overflow-x: auto; }
    table.adm-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.875rem;
      color: var(--text-secondary);
    }
    .adm-table th {
      padding: 12px 18px;
      text-align: left;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: .6px;
      text-transform: uppercase;
      color: var(--text-muted);
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
      background: var(--bg-subtle);
    }
    .adm-table th.check-col { width: 44px; }
    .adm-table td {
      padding: 14px 18px;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }
    .adm-table tr:last-child td { border-bottom: none; }
    .adm-table tr.row-detail-active td { background: var(--at-green-dim); }
    .adm-table tr:hover td {
      background: var(--bg-hover);
    }
    .adm-table input[type="checkbox"] { accent-color: var(--at-green); width:16px; height:16px; }
    .adm-table td.msg-cell {
      max-width: 260px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      color: var(--text-muted);
      font-size: 0.82rem;
    }
    .adm-table td.date-cell {
      white-space: nowrap;
      color: var(--text-muted);
      font-size: 0.8rem;
    }
    .adm-table td .nom-link {
      color: var(--text-primary);
      font-weight: 600;
      text-decoration: none;
      transition: color var(--t);
    }
    .adm-table td .nom-link:hover { color: var(--at-green); }
    .adm-table td .email-link {
      color: var(--at-green);
      text-decoration: none;
      font-size: 0.82rem;
    }
    .adm-table td .email-link:hover { text-decoration: underline; }

    /* ── Badges ── */
    .badge {
      display: inline-flex;
      align-items: center;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: .3px;
      white-space: nowrap;
    }
    .badge-blue   { background:rgba(0,87,183,.14);  color:#4a9eff; }
    .badge-red    { background:rgba(220,60,60,.14);  color:#e05050; }
    .badge-orange { background:rgba(244,121,32,.14); color:#f47920; }
    .badge-yellow { background:rgba(245,158,11,.14); color:#f59e0b; }
    .badge-purple { background:rgba(139,92,246,.14); color:#9b7cf7; }
    .badge-gray   { background:rgba(120,120,120,.14);color:var(--text-muted); }
    .badge-ok     { background:rgba(0,160,75,.14);   color:var(--at-green-light); }
    .badge-pending{ background:rgba(244,121,32,.14); color:#f47920; }

    /* ── Action buttons inline ── */
    .adm-actions { display:flex; align-items:center; gap:6px; }
    .adm-btn-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px; height: 32px;
      border-radius: var(--r-sm);
      border: 1px solid var(--border);
      background: transparent;
      cursor: pointer;
      transition: background var(--t), border-color var(--t);
      color: var(--text-muted);
      text-decoration: none;
    }
    .adm-btn-icon svg { width:15px; height:15px; fill:currentColor; }
    .adm-btn-icon:hover { background: var(--bg-hover); border-color: var(--border-active); color: var(--text-primary); }
    .adm-btn-icon.danger:hover { background: rgba(220,60,60,.12); border-color: rgba(220,60,60,.3); color: #e05050; }
    .adm-btn-icon.view-btn { color: var(--at-green); border-color: rgba(0,160,75,.25); }
    .adm-btn-icon.view-btn:hover { background: var(--at-green-dim); }

    /* ── Detail panel ── */
    .adm-detail-panel {
      margin-top: 32px;
    }
    .adm-detail-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      padding: 24px 28px;
      background: var(--bg-subtle);
      border-bottom: 1px solid var(--border);
    }
    .adm-detail-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text-primary);
      margin: 0 0 4px;
    }
    .adm-detail-meta {
      font-size: 0.82rem;
      color: var(--text-muted);
    }
    .adm-detail-body { padding: 28px; }
    .adm-detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 28px;
    }
    .adm-detail-field label {
      display: block;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .5px;
      color: var(--text-muted);
      margin-bottom: 4px;
    }
    .adm-detail-field .val {
      color: var(--text-primary);
      font-size: 0.92rem;
    }
    .adm-detail-field .val a {
      color: var(--at-green);
      text-decoration: none;
    }
    .adm-detail-message {
      background: var(--bg-subtle);
      border: 1px solid var(--border);
      border-radius: var(--r-md);
      padding: 20px;
      color: var(--text-secondary);
      line-height: 1.8;
      white-space: pre-wrap;
      word-break: break-word;
      font-size: 0.9rem;
      margin-bottom: 24px;
    }
    .adm-detail-actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    /* ── Login page ── */
    .adm-login-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: calc(100vh - var(--nav-h));
      padding: 40px 20px;
    }
    .adm-login-box {
      width: 100%;
      max-width: 420px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--r-xl);
      padding: 48px 40px;
      box-shadow: var(--shadow-lg);
    }
    .adm-login-logo {
      text-align: center;
      margin-bottom: 32px;
    }
    .adm-login-logo img { height: 50px; }
    .adm-login-logo h2 {
      font-size: 1.4rem;
      margin: 12px 0 4px;
      color: var(--text-primary);
    }
    .adm-login-logo p {
      font-size: 0.82rem;
      color: var(--text-muted);
      margin: 0;
    }
    .adm-login-err {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 12px 16px;
      background: rgba(220,60,60,.1);
      border: 1px solid rgba(220,60,60,.25);
      border-radius: var(--r-md);
      color: #e05050;
      font-size: 0.875rem;
      margin-bottom: 20px;
    }
    .adm-login-err svg { width:16px; height:16px; fill: #e05050; flex-shrink:0; }

    /* reuse existing form-group from style.css */
    .adm-login-box .form-group { margin-bottom: 18px; }
    .adm-login-box .form-group label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text-secondary);
      margin-bottom: 6px;
    }

    /* ── Responsive ── */
    @media(max-width:640px) {
      .adm-table th:nth-child(5),
      .adm-table td:nth-child(5),
      .adm-table th:nth-child(6),
      .adm-table td:nth-child(6) { display: none; }
      .adm-detail-header { padding: 16px; }
      .adm-detail-body   { padding: 16px; }
      .adm-login-box     { padding: 32px 24px; }
    }

    /* ── Empty state ── */
    .adm-empty {
      text-align: center;
      padding: 64px 24px;
      color: var(--text-muted);
    }
    .adm-empty svg {
      width: 56px; height: 56px;
      fill: var(--border);
      margin-bottom: 16px;
    }
    .adm-empty p { font-size: 0.95rem; margin: 0; }

    /* ── Confirm modal ── */
    .adm-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.5);
      z-index: 9000;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .adm-overlay.show { display: flex; }
    .adm-modal {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--r-lg);
      padding: 36px 32px;
      max-width: 420px;
      width: 100%;
      box-shadow: var(--shadow-lg);
      animation: slideIn 0.2s ease;
    }
    .adm-modal h3 { margin: 0 0 12px; color: var(--text-primary); font-size: 1.1rem; }
    .adm-modal p  { color: var(--text-muted); font-size: 0.9rem; margin: 0 0 28px; }
    .adm-modal .modal-actions { display:flex; gap:10px; justify-content:flex-end; }
  </style>
</head>
<body>
<script>if(localStorage.getItem('at-theme')==='dark')document.body.classList.add('dark-mode');</script>

<!-- ── Navigation ── -->
<nav class="navbar">
  <a href="index.html" class="nav-brand">
    <img src="AlgerieTelecom.png" alt="Logo Algérie Télécom" class="nav-logo">
    <span class="nav-title">Algérie <span>Télécom</span></span>
  </a>
  <?php if (isset($_SESSION['at_admin'])): ?>
  <div style="display:flex;align-items:center;gap:16px;padding-right:8px;">
    <span style="font-size:0.82rem;color:var(--text-muted);">
      Panel Admin
    </span>
    <a href="admin.php?logout=1" class="btn btn-outline" style="font-size:0.78rem;padding:6px 14px;">
      <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
      Déconnexion
    </a>
  </div>
  <?php endif; ?>
  <button id="theme-toggle-btn" class="theme-toggle" aria-label="Basculer thème" title="Changer de thème">
    <div class="theme-toggle-track">
      <div class="theme-toggle-thumb">
        <svg class="theme-toggle-icon icon-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/></svg>
        <svg class="theme-toggle-icon icon-sun" viewBox="0 0 24 24"><path d="M12 4a8 8 0 1 0 0 16A8 8 0 0 0 12 4zm0-2a1 1 0 0 1 1 1v1a1 1 0 0 1-2 0V3a1 1 0 0 1 1-1zm0 18a1 1 0 0 1 1 1v1a1 1 0 0 1-2 0v-1a1 1 0 0 1 1-1zm9-9h1a1 1 0 0 1 0 2h-1a1 1 0 0 1 0-2zM3 12H2a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm15.364-6.364.707-.707a1 1 0 1 1 1.414 1.414l-.707.707a1 1 0 0 1-1.414-1.414zM4.929 18.364l-.707.707a1 1 0 0 1-1.414-1.414l.707-.707a1 1 0 0 1 1.414 1.414zM18.364 19.07l.707.707a1 1 0 0 1-1.414 1.414l-.707-.707a1 1 0 0 1 1.414-1.414zM5.636 5.636 4.929 4.93a1 1 0 0 1 1.414-1.414l.707.707A1 1 0 1 1 5.636 5.636z"/></svg>
      </div>
    </div>
  </button>
</nav>

<?php if (!isset($_SESSION['at_admin'])): ?>
<!-- ════════════════════════════════════════════════════════
     PAGE DE CONNEXION
════════════════════════════════════════════════════════ -->
<div class="adm-login-wrap">
  <div class="adm-login-box">
    <div class="adm-login-logo">
      <img src="AlgerieTelecom.png" alt="Logo">
      <h2>Espace Administration</h2>
      <p>Connectez-vous pour accéder au tableau de bord</p>
    </div>

    <?php if ($errLogin): ?>
    <div class="adm-login-err">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
      <?= h($errLogin) ?>
    </div>
    <?php endif; ?>

    <form method="POST" autocomplete="on">
      <input type="hidden" name="action" value="login">
      <div class="form-group">
        <label for="identifiant">Identifiant</label>
        <input id="identifiant" type="text" name="identifiant" placeholder="admin"
               autocomplete="username" required autofocus>
      </div>
      <div class="form-group">
        <label for="motdepasse">Mot de passe</label>
        <input id="motdepasse" type="password" name="motdepasse" placeholder="••••••••"
               autocomplete="current-password" required>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px;">
        <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
        Se connecter
      </button>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ════════════════════════════════════════════════════════
     DASHBOARD ADMIN
════════════════════════════════════════════════════════ -->
<div class="adm-wrap">

  <!-- En-tête page -->
  <div class="adm-page-header">
    <h1>
      <span class="adm-dot"></span>
      Tableau de bord
    </h1>
    <div class="adm-user-chip">
      <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:var(--at-green);"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
      Connecté en tant que <strong><?= h($_SESSION['at_username']) ?></strong>
      &nbsp;·&nbsp;
      <a href="admin.php?logout=1">Déconnexion</a>
    </div>
  </div>

  <?php if (isset($dbError)): ?>
  <div class="adm-flash error">
    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
    <?= $dbError ?>
  </div>
  <?php endif; ?>

  <?php if ($flash): ?>
  <div class="adm-flash <?= $flash['type'] ?>">
    <?php if($flash['type']==='success'): ?>
    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
    <?php else: ?>
    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
    <?php endif; ?>
    <?= h($flash['msg']) ?>
  </div>
  <?php endif; ?>

  <!-- Stats -->
  <?php
    $pct = $stats['total'] > 0 ? round(($stats['traite'] / $stats['total']) * 100) : 0;
  ?>
  <div class="adm-stats">
    <div class="adm-stat-card">
      <div class="num"><?= $stats['total'] ?></div>
      <div class="lbl">Messages reçus</div>
      <div class="mini-bar"><span style="width:100%"></span></div>
    </div>
    <div class="adm-stat-card warn">
      <div class="num"><?= $stats['non_traite'] ?></div>
      <div class="lbl">En attente</div>
      <div class="mini-bar"><span style="width:<?= $stats['total']>0?round($stats['non_traite']/$stats['total']*100):0 ?>%;background:#f59e0b"></span></div>
    </div>
    <div class="adm-stat-card">
      <div class="num"><?= $stats['traite'] ?></div>
      <div class="lbl">Traités</div>
      <div class="mini-bar"><span style="width:<?= $pct ?>%"></span></div>
    </div>
    <div class="adm-stat-card muted">
      <div class="num"><?= $stats['clients'] ?></div>
      <div class="lbl">Clients</div>
      <div class="mini-bar"><span style="width:60%;background:var(--text-muted)"></span></div>
    </div>
  </div>

  <!-- ── DÉTAIL MESSAGE ── -->
  <?php if ($detailMsg): ?>
  <?php [$slbl, $sbadge] = sujetLabel($detailMsg['sujet']); ?>
  <div class="adm-section adm-detail-panel">
    <div class="adm-section-header">
      <h2>
        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        Détail du message #<?= h($detailMsg['id']) ?>
      </h2>
      <a href="admin.php" class="adm-btn-icon" title="Fermer le détail" style="width:auto;padding:0 12px;gap:6px;">
        <svg viewBox="0 0 24 24"><path d="M19 11H7.83l4.88-4.88c.39-.39.39-1.03 0-1.42-.39-.39-1.02-.39-1.41 0l-6.59 6.59c-.39.39-.39 1.02 0 1.41l6.59 6.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L7.83 13H19c.55 0 1-.45 1-1s-.45-1-1-1z"/></svg>
        Retour
      </a>
    </div>
    <div class="adm-detail-body">
      <div class="adm-detail-grid">
        <div class="adm-detail-field">
          <label>Prénom</label>
          <div class="val"><?= h($detailMsg['prenom']) ?></div>
        </div>
        <div class="adm-detail-field">
          <label>Nom</label>
          <div class="val"><?= h($detailMsg['nom']) ?></div>
        </div>
        <div class="adm-detail-field">
          <label>Adresse e-mail</label>
          <div class="val"><a href="mailto:<?= h($detailMsg['email']) ?>"><?= h($detailMsg['email']) ?></a></div>
        </div>
        <div class="adm-detail-field">
          <label>Téléphone</label>
          <div class="val"><?= $detailMsg['telephone'] ? h($detailMsg['telephone']) : '<span style="color:var(--text-muted)">—</span>' ?></div>
        </div>
        <div class="adm-detail-field">
          <label>Objet</label>
          <div class="val"><span class="badge <?= $sbadge ?>"><?= $slbl ?></span></div>
        </div>
        <div class="adm-detail-field">
          <label>Date d'envoi</label>
          <div class="val"><?= date('d/m/Y à H:i', strtotime($detailMsg['date_envoi'])) ?></div>
        </div>
        <div class="adm-detail-field">
          <label>Statut</label>
          <div class="val">
            <?php if ($detailMsg['traite']): ?>
              <span class="badge badge-ok">✓ Traité</span>
              <?php if ($detailMsg['date_traitement']): ?>
                <span style="font-size:0.78rem;color:var(--text-muted);margin-left:8px;">
                  le <?= date('d/m/Y', strtotime($detailMsg['date_traitement'])) ?>
                </span>
              <?php endif; ?>
            <?php else: ?>
              <span class="badge badge-pending">En attente</span>
            <?php endif; ?>
          </div>
        </div>
        <div class="adm-detail-field">
          <label>IP expéditeur</label>
          <div class="val" style="font-size:0.82rem;color:var(--text-muted);"><?= h($detailMsg['ip_expediteur'] ?? '—') ?></div>
        </div>
      </div>

      <label style="display:block;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:10px;">Contenu du message</label>
      <div class="adm-detail-message"><?= h($detailMsg['message']) ?></div>

      <div class="adm-detail-actions">
        <?php if (!$detailMsg['traite']): ?>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
          <input type="hidden" name="action" value="traiter">
          <input type="hidden" name="contact_id" value="<?= (int)$detailMsg['id'] ?>">
          <button type="submit" class="btn btn-primary" style="font-size:0.875rem;padding:10px 20px;">
            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Marquer comme traité
          </button>
        </form>
        <?php endif; ?>
        <a href="mailto:<?= h($detailMsg['email']) ?>" class="btn btn-outline" style="font-size:0.875rem;padding:10px 20px;">
          <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
          Répondre par e-mail
        </a>
        <button type="button" class="btn btn-outline" style="font-size:0.875rem;padding:10px 20px;border-color:rgba(220,60,60,.3);color:#e05050;"
                onclick="confirmDelete(<?= (int)$detailMsg['id'] ?>, '<?= h($detailMsg['prenom'].' '.$detailMsg['nom']) ?>')">
          <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
          Supprimer ce message
        </button>
      </div>
    </div>
  </div>
  <div style="margin-top:32px;"></div>
  <?php endif; ?>

  <!-- ── LISTE MESSAGES ── -->
  <div class="adm-section">
    <div class="adm-section-header">
      <h2>
        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        Messages de contact
        <?php if($stats['non_traite']>0): ?>
          <span class="badge badge-pending" style="font-size:0.68rem;"><?= $stats['non_traite'] ?> en attente</span>
        <?php endif; ?>
      </h2>
      <div class="adm-toolbar">
        <form method="POST" id="bulk-form">
          <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
          <input type="hidden" name="action" value="delete_bulk">
          <label>
            <input type="checkbox" id="select-all"> Tout sélectionner
          </label>
          <button type="button" id="btn-delete-selected" class="btn btn-outline"
                  style="font-size:0.78rem;padding:7px 14px;border-color:rgba(220,60,60,.3);color:#e05050;"
                  onclick="confirmBulkDelete()">
            <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:currentColor;"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
            Supprimer la sélection
          </button>
        </form>
      </div>
    </div>

    <?php if (empty($contacts)): ?>
    <div class="adm-empty">
      <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
      <p>Aucun message reçu pour le moment.</p>
    </div>
    <?php else: ?>
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th class="check-col">
              <!-- les cases restent dans le formulaire bulk -->
            </th>
            <th>#</th>
            <th>Expéditeur</th>
            <th>E-mail</th>
            <th>Sujet</th>
            <th>Aperçu</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($contacts as $c): ?>
          <?php [$slbl, $sbadge] = sujetLabel($c['sujet']); ?>
          <tr class="<?= (isset($_GET['detail']) && (int)$_GET['detail']===$c['id']) ? 'row-detail-active' : '' ?>">
            <td>
              <!-- Checkbox inside the bulk form above via JS; we keep it here for layout -->
              <input type="checkbox" class="row-check" form="bulk-form" name="ids[]" value="<?= (int)$c['id'] ?>">
            </td>
            <td style="color:var(--text-muted);font-size:0.8rem;"><?= (int)$c['id'] ?></td>
            <td>
              <a href="admin.php?detail=<?= (int)$c['id'] ?>" class="nom-link">
                <?= h($c['prenom'].' '.$c['nom']) ?>
              </a>
            </td>
            <td>
              <a href="mailto:<?= h($c['email']) ?>" class="email-link">
                <?= h($c['email']) ?>
              </a>
            </td>
            <td><span class="badge <?= $sbadge ?>"><?= $slbl ?></span></td>
            <td class="msg-cell"><?= h($c['message']) ?></td>
            <td class="date-cell"><?= date('d/m/Y', strtotime($c['date_envoi'])) ?><br>
              <span style="font-size:0.75rem;"><?= date('H:i', strtotime($c['date_envoi'])) ?></span>
            </td>
            <td>
              <?php if ($c['traite']): ?>
                <span class="badge badge-ok">Traité</span>
              <?php else: ?>
                <span class="badge badge-pending">En attente</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="adm-actions">
                <a href="admin.php?detail=<?= (int)$c['id'] ?>" class="adm-btn-icon view-btn" title="Voir le détail">
                  <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                </a>
                <?php if (!$c['traite']): ?>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Marquer ce message comme traité ?')">
                  <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
                  <input type="hidden" name="action" value="traiter">
                  <input type="hidden" name="contact_id" value="<?= (int)$c['id'] ?>">
                  <button type="submit" class="adm-btn-icon" title="Marquer traité">
                    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                  </button>
                </form>
                <?php endif; ?>
                <button type="button" class="adm-btn-icon danger" title="Supprimer"
                        onclick="confirmDelete(<?= (int)$c['id'] ?>, '<?= h($c['prenom'].' '.$c['nom']) ?>')">
                  <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

</div><!-- /adm-wrap -->

<!-- ── Modal confirmation suppression ── -->
<div class="adm-overlay" id="confirm-modal">
  <div class="adm-modal">
    <h3>⚠️ Confirmer la suppression</h3>
    <p id="modal-msg">Cette action est irréversible.</p>
    <div class="modal-actions">
      <button type="button" class="btn btn-outline" onclick="closeModal()">Annuler</button>
      <form method="POST" id="delete-form" style="display:inline;">
        <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
        <input type="hidden" name="action" value="delete_one">
        <input type="hidden" name="contact_id" id="delete-id" value="">
        <button type="submit" class="btn btn-primary" style="background:#dc3c3c;border-color:#dc3c3c;">
          Supprimer
        </button>
      </form>
    </div>
  </div>
</div>

<!-- Modal bulk delete -->
<div class="adm-overlay" id="bulk-modal">
  <div class="adm-modal">
    <h3>⚠️ Supprimer la sélection</h3>
    <p id="bulk-modal-msg">Confirmer la suppression des messages sélectionnés ?</p>
    <div class="modal-actions">
      <button type="button" class="btn btn-outline" onclick="closeBulkModal()">Annuler</button>
      <button type="button" class="btn btn-primary" style="background:#dc3c3c;border-color:#dc3c3c;"
              onclick="document.getElementById('bulk-form').submit()">
        Supprimer
      </button>
    </div>
  </div>
</div>

<?php endif; // isLoggedIn ?>

<script>
// ── Theme toggle ──
(function(){
  const btn = document.getElementById('theme-toggle-btn');
  if (!btn) return;
  btn.addEventListener('click', () => {
    const isDark = document.body.classList.toggle('dark-mode');
    localStorage.setItem('at-theme', isDark ? 'dark' : 'light');
  });
})();

// ── Select all checkboxes ──
const selectAll = document.getElementById('select-all');
const deleteBtnVisible = document.getElementById('btn-delete-selected');

function updateBulkBtn() {
  const checked = document.querySelectorAll('.row-check:checked');
  if (deleteBtnVisible) {
    deleteBtnVisible.classList.toggle('visible', checked.length > 0);
  }
}

if (selectAll) {
  selectAll.addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => {
      cb.checked = this.checked;
    });
    updateBulkBtn();
  });
}

document.querySelectorAll('.row-check').forEach(cb => {
  cb.addEventListener('change', updateBulkBtn);
});

// ── Confirm delete single ──
function confirmDelete(id, name) {
  document.getElementById('delete-id').value = id;
  document.getElementById('modal-msg').textContent =
    'Supprimer définitivement le message de ' + name + ' ? Cette action est irréversible.';
  document.getElementById('confirm-modal').classList.add('show');
}
function closeModal() {
  document.getElementById('confirm-modal').classList.remove('show');
}
document.getElementById('confirm-modal')?.addEventListener('click', function(e){
  if (e.target === this) closeModal();
});

// ── Confirm delete bulk ──
function confirmBulkDelete() {
  const n = document.querySelectorAll('.row-check:checked').length;
  if (n === 0) return;
  document.getElementById('bulk-modal-msg').textContent =
    'Supprimer définitivement ' + n + ' message(s) sélectionné(s) ? Cette action est irréversible.';
  document.getElementById('bulk-modal').classList.add('show');
}
function closeBulkModal() {
  document.getElementById('bulk-modal').classList.remove('show');
}
document.getElementById('bulk-modal')?.addEventListener('click', function(e){
  if (e.target === this) closeBulkModal();
});

// ── Close modal on Escape ──
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeModal(); closeBulkModal(); }
});
</script>

</body>
</html>