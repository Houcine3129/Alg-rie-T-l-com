<?php
/**
 * ============================================================
 *  ALGÉRIE TÉLÉCOM — Panel Admin
 *  Accès : http://localhost/AlgerieTelecom/admin.php
 *  Login : admin / admin123  (à changer en prod)
 * ============================================================
 */
require_once 'config.php';
session_start();

// ── Authentification ─────────────────────────────────────────
$errLogin = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $motdepasse  = trim($_POST['motdepasse']  ?? '');
    try {
        $pdo  = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE identifiant = ? LIMIT 1");
        $stmt->execute([$identifiant]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($motdepasse, $admin['motdepasse'])) {
            $_SESSION['at_admin']    = $admin['id'];
            $_SESSION['at_username'] = $admin['identifiant'];
            header('Location: admin.php');
            exit;
        }
        $errLogin = 'Identifiant ou mot de passe incorrect.';
    } catch (PDOException $e) {
        $errLogin = 'Erreur de connexion à la base de données.';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// ── Action : marquer un contact comme traité ─────────────────
if (isset($_GET['traiter']) && isset($_SESSION['at_admin'])) {
    try {
        $pdo  = getPDO();
        $stmt = $pdo->prepare("UPDATE contact SET traite=1, date_traitement=NOW() WHERE id=?");
        $stmt->execute([(int)$_GET['traiter']]);
    } catch (PDOException $e) { /* silencieux */ }
    header('Location: admin.php#contacts');
    exit;
}

// ── Récupération données (si connecté) ───────────────────────
$contacts = $clients = $services = $offres = [];
if (isset($_SESSION['at_admin'])) {
    try {
        $pdo = getPDO();
        $contacts = $pdo->query("SELECT * FROM contact    ORDER BY date_envoi DESC")->fetchAll();
        $clients  = $pdo->query("SELECT c.*, w.nom AS wilaya_nom, o.nom AS offre_nom
                                  FROM client c
                                  LEFT JOIN wilaya w ON c.wilaya_id = w.id
                                  LEFT JOIN offre  o ON c.offre_id  = o.id
                                  ORDER BY c.id DESC")->fetchAll();
        $services = $pdo->query("SELECT * FROM service ORDER BY id ASC")->fetchAll();
        $offres   = $pdo->query("SELECT * FROM offre   ORDER BY prix ASC")->fetchAll();
        // Stats
        $nbContacts   = $pdo->query("SELECT COUNT(*) FROM contact")->fetchColumn();
        $nbNonTraites = $pdo->query("SELECT COUNT(*) FROM contact WHERE traite=0")->fetchColumn();
        $nbClients    = $pdo->query("SELECT COUNT(*) FROM client")->fetchColumn();
        $nbOffres     = $pdo->query("SELECT COUNT(*) FROM offre")->fetchColumn();
    } catch (PDOException $e) {
        $erreur = "Erreur de lecture de la base : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Algérie Télécom</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* ── Admin overrides ── */
    body { background: var(--bg-base); }
    .admin-wrapper { max-width: 1200px; margin: 0 auto; padding: 100px 24px 60px; }
    .admin-header { display:flex; align-items:center; justify-content:space-between;
      margin-bottom:40px; padding-bottom:20px; border-bottom:1px solid var(--glass-border); }
    .admin-header h1 { font-size:1.6rem; color:var(--white); }
    .admin-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
      gap:20px; margin-bottom:48px; }
    .stat-card { background:var(--glass-bg); border:1px solid var(--glass-border);
      border-radius:var(--radius-md); padding:24px; text-align:center; }
    .stat-card .num { font-size:2.5rem; font-weight:800; color:var(--at-green-light); }
    .stat-card .lbl { font-size:0.8rem; color:var(--text-muted); margin-top:4px; }
    .admin-section { margin-bottom:60px; }
    .admin-section h2 { font-size:1.2rem; color:var(--white); margin-bottom:20px;
      padding-bottom:10px; border-bottom:1px solid var(--glass-border); }
    table { width:100%; border-collapse:collapse; font-size:0.85rem; color:var(--text-secondary); }
    th { text-align:left; padding:12px 14px; color:var(--white); font-size:0.78rem;
      letter-spacing:.5px; border-bottom:2px solid var(--glass-border);
      background:rgba(0,160,75,0.06); }
    td { padding:12px 14px; border-bottom:1px solid var(--glass-border); vertical-align:middle; }
    tr:hover td { background:rgba(255,255,255,0.02); }
    .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.72rem;
      font-weight:600; letter-spacing:.3px; }
    .badge-ok  { background:rgba(0,160,75,0.18);  color:var(--at-green-light); }
    .badge-non { background:rgba(244,121,32,0.18); color:#F47920; }
    .login-box { max-width:400px; margin:120px auto; background:var(--glass-bg);
      border:1px solid var(--glass-border); border-radius:var(--radius-lg); padding:40px; }
    .login-box h2 { text-align:center; margin-bottom:28px; color:var(--white); }
    .login-err { color:#ff6b6b; font-size:0.85rem; text-align:center; margin-bottom:16px; }
    .admin-nav { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:32px; }
    .admin-nav a { color:var(--at-green-light); font-size:0.85rem; text-decoration:none;
      padding:6px 14px; border:1px solid var(--glass-border); border-radius:var(--radius-sm); }
    .admin-nav a:hover { background:var(--at-green-dim); }
    .msg-preview { max-width:300px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .traiter-btn { font-size:0.75rem; padding:4px 10px; }
  </style>
</head>
<body>

<nav class="navbar">
  <a href="index.html" class="nav-brand">
    <img src="AlgerieTelecom.png" alt="Logo" class="nav-logo">
    <span class="nav-title">Algérie <span>Télécom</span></span>
  </a>
  <?php if (isset($_SESSION['at_admin'])): ?>
  <div style="color:var(--text-muted);font-size:0.85rem;padding-right:20px;">
    👤 <?= htmlspecialchars($_SESSION['at_username']) ?>
    &nbsp;·&nbsp;
    <a href="admin.php?logout=1" style="color:var(--at-green-light);">Déconnexion</a>
  </div>
  <?php endif; ?>
</nav>

<div class="admin-wrapper">

<?php if (!isset($_SESSION['at_admin'])): ?>
<!-- ── LOGIN ── -->
<div class="login-box">
  <h2>🔐 Accès Administration</h2>
  <?php if ($errLogin): ?>
    <p class="login-err"><?= htmlspecialchars($errLogin) ?></p>
  <?php endif; ?>
  <form method="POST">
    <input type="hidden" name="action" value="login">
    <div class="form-group">
      <label>Identifiant</label>
      <input type="text" name="identifiant" placeholder="admin" autocomplete="username">
    </div>
    <div class="form-group">
      <label>Mot de passe</label>
      <input type="password" name="motdepasse" placeholder="••••••••" autocomplete="current-password">
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px;">
      Connexion
    </button>
  </form>
</div>

<?php else: ?>
<!-- ── DASHBOARD ── -->
<div class="admin-header">
  <h1>🛠 Panel Administration</h1>
  <a href="admin.php?logout=1" class="btn btn-outline" style="font-size:0.85rem;padding:8px 18px;">Déconnexion</a>
</div>

<!-- Stats -->
<div class="admin-stats">
  <div class="stat-card">
    <div class="num"><?= $nbContacts   ?></div>
    <div class="lbl">Messages reçus</div>
  </div>
  <div class="stat-card">
    <div class="num" style="color:#F47920;"><?= $nbNonTraites ?></div>
    <div class="lbl">Non traités</div>
  </div>
  <div class="stat-card">
    <div class="num"><?= $nbClients    ?></div>
    <div class="lbl">Clients</div>
  </div>
  <div class="stat-card">
    <div class="num"><?= $nbOffres     ?></div>
    <div class="lbl">Offres actives</div>
  </div>
</div>

<!-- Nav interne -->
<div class="admin-nav">
  <a href="#contacts">Messages Contact</a>
  <a href="#clients">Clients</a>
  <a href="#offres">Offres</a>
  <a href="#services">Services</a>
</div>

<!-- ── CONTACTS ── -->
<div class="admin-section" id="contacts">
  <h2>📩 Messages de Contact</h2>
  <?php if (empty($contacts)): ?>
    <p style="color:var(--text-muted);">Aucun message reçu.</p>
  <?php else: ?>
  <div style="overflow-x:auto;">
  <table>
    <thead>
      <tr>
        <th>#</th><th>Nom</th><th>Email</th><th>Sujet</th>
        <th>Message</th><th>Date</th><th>Statut</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($contacts as $c): ?>
      <tr>
        <td><?= $c['id'] ?></td>
        <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
        <td><a href="mailto:<?= htmlspecialchars($c['email']) ?>" style="color:var(--at-green-light);"><?= htmlspecialchars($c['email']) ?></a></td>
        <td><?= htmlspecialchars($c['sujet']) ?></td>
        <td class="msg-preview" title="<?= htmlspecialchars($c['message']) ?>"><?= htmlspecialchars($c['message']) ?></td>
        <td><?= date('d/m/Y H:i', strtotime($c['date_envoi'])) ?></td>
        <td>
          <?php if ($c['traite']): ?>
            <span class="badge badge-ok">Traité</span>
          <?php else: ?>
            <span class="badge badge-non">En attente</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if (!$c['traite']): ?>
            <a href="admin.php?traiter=<?= $c['id'] ?>#contacts" class="btn btn-primary traiter-btn">✓ Traiter</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php endif; ?>
</div>

<!-- ── CLIENTS ── -->
<div class="admin-section" id="clients">
  <h2>👥 Clients</h2>
  <?php if (empty($clients)): ?>
    <p style="color:var(--text-muted);">Aucun client enregistré.</p>
  <?php else: ?>
  <div style="overflow-x:auto;">
  <table>
    <thead>
      <tr><th>#</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Wilaya</th><th>Offre</th><th>Inscription</th></tr>
    </thead>
    <tbody>
      <?php foreach ($clients as $cl): ?>
      <tr>
        <td><?= $cl['id'] ?></td>
        <td><?= htmlspecialchars($cl['prenom'].' '.$cl['nom']) ?></td>
        <td><?= htmlspecialchars($cl['email']) ?></td>
        <td><?= htmlspecialchars($cl['telephone'] ?? '—') ?></td>
        <td><?= htmlspecialchars($cl['wilaya_nom'] ?? '—') ?></td>
        <td><span class="badge badge-ok"><?= htmlspecialchars($cl['offre_nom'] ?? '—') ?></span></td>
        <td><?= isset($cl['date_creation']) ? date('d/m/Y', strtotime($cl['date_creation'])) : '—' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php endif; ?>
</div>

<!-- ── OFFRES ── -->
<div class="admin-section" id="offres">
  <h2>💼 Offres</h2>
  <?php if (empty($offres)): ?>
    <p style="color:var(--text-muted);">Aucune offre.</p>
  <?php else: ?>
  <table>
    <thead>
      <tr><th>#</th><th>Nom</th><th>Description</th><th>Prix (DA/mois)</th><th>Débit</th></tr>
    </thead>
    <tbody>
      <?php foreach ($offres as $o): ?>
      <tr>
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['nom']) ?></td>
        <td><?= htmlspecialchars($o['description'] ?? '—') ?></td>
        <td style="color:var(--at-green-light);font-weight:700;"><?= number_format($o['prix'], 0, ',', ' ') ?> DA</td>
        <td><?= htmlspecialchars($o['debit'] ?? '—') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<!-- ── SERVICES ── -->
<div class="admin-section" id="services">
  <h2>⚙️ Services</h2>
  <?php if (empty($services)): ?>
    <p style="color:var(--text-muted);">Aucun service.</p>
  <?php else: ?>
  <table>
    <thead>
      <tr><th>#</th><th>Nom</th><th>Description</th><th>Catégorie</th></tr>
    </thead>
    <tbody>
      <?php foreach ($services as $s): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['nom']) ?></td>
        <td><?= htmlspecialchars($s['description'] ?? '—') ?></td>
        <td><?= htmlspecialchars($s['categorie'] ?? '—') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php endif; ?>
</div>

<script src="main.js"></script>
</body>
</html>
