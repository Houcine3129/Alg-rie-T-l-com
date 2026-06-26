<?php
require_once 'php/config.php';
session_start();

$errMsg = '';
$successMsg = '';
$activeForm = 'signin';

// ── LOGOUT ──────────────────────────────────────────────
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: test.php');
    exit;
}

// ── SIGNUP ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'signup') {
    $activeForm = 'signup';
    $prenom     = trim($_POST['prenom']     ?? '');
    $nom        = trim($_POST['nom']        ?? '');
    $email      = trim($_POST['email']      ?? '');
    $telephone  = trim($_POST['telephone']  ?? '');
    $password   = $_POST['password']        ?? '';
    $wilaya_id  = (int)($_POST['wilaya_id'] ?? 0);

    if (strlen($prenom) < 2 || strlen($nom) < 2) {
        $errMsg = 'Prénom et nom requis (min 2 caractères).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errMsg = 'Email invalide.';
    } elseif (strlen($password) < 6) {
        $errMsg = 'Mot de passe trop court (min 6 caractères).';
    } elseif ($wilaya_id < 1) {
        $errMsg = 'Veuillez sélectionner une wilaya.';
    } else {
        try {
            $pdo = getPDO();
            $chk = $pdo->prepare("SELECT id FROM client WHERE email = ?");
            $chk->execute([$email]);
            if ($chk->fetch()) {
                $errMsg = 'Cet email est déjà inscrit. <a href="?login=1" style="color:var(--at-green-light);">Connectez-vous</a>';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO client (prenom, nom, email, motdepasse, telephone, wilaya_id, date_creation) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$prenom, $nom, $email, $hash, $telephone, $wilaya_id]);
                $successMsg = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                $activeForm = 'signin';
            }
        } catch (PDOException $e) {
            $errMsg = 'Erreur serveur. Veuillez réessayer.';
        }
    }
}

// ── LOGIN ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $activeForm = 'signin';
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errMsg = 'Email invalide.';
    } elseif (empty($password)) {
        $errMsg = 'Mot de passe requis.';
    } else {
        try {
            $pdo = getPDO();
            $stmt = $pdo->prepare("SELECT id, prenom, nom, email, telephone, motdepasse FROM client WHERE email = ?");
            $stmt->execute([$email]);
            $client = $stmt->fetch();

            if (!$client || !password_verify($password, $client['motdepasse'])) {
                $errMsg = 'Email ou mot de passe incorrect.';
            } else {
                session_regenerate_id(true);
                $_SESSION['at_client'] = [
                    'id'        => (int)$client['id'],
                    'prenom'    => $client['prenom'],
                    'nom'       => $client['nom'],
                    'email'     => $client['email'],
                    'telephone' => $client['telephone'],
                ];
                header('Location: test.php');
                exit;
            }
        } catch (PDOException $e) {
            $errMsg = 'Erreur serveur. Veuillez réessayer.';
        }
    }
}

function h(mixed $v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Espace Client — Algérie Télécom</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .client-login-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: calc(100vh - var(--nav-h));
      padding: 100px 20px 60px;
    }
    .client-login-box {
      width: 100%;
      max-width: 480px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--r-xl);
      padding: 48px 40px;
      box-shadow: var(--shadow-lg);
    }
    .client-login-logo {
      text-align: center;
      margin-bottom: 28px;
    }
    .client-login-logo img { height: 48px; }
    .client-login-logo h2 {
      font-size: 1.4rem;
      margin: 12px 0 4px;
      color: var(--text-primary);
    }
    .client-login-logo p {
      font-size: 0.82rem;
      color: var(--text-muted);
      margin: 0;
    }

    /* ── Tabs ── */
    .client-tabs {
      display: flex;
      margin-bottom: 28px;
      border-radius: var(--r-md);
      overflow: hidden;
      border: 1px solid var(--border);
    }
    .client-tabs button {
      flex: 1;
      padding: 12px 16px;
      border: none;
      background: var(--bg-subtle);
      color: var(--text-muted);
      font-family: 'Syne', sans-serif;
      font-size: 0.85rem;
      font-weight: 700;
      cursor: pointer;
      transition: var(--t);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .client-tabs button.active {
      background: var(--at-green);
      color: #fff;
    }
    .client-tabs button:not(.active):hover {
      background: var(--bg-hover);
      color: var(--text-secondary);
    }

    .client-form { display: none; }
    .client-form.active { display: block; }

    .client-msg {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 14px 18px;
      border-radius: var(--r-md);
      font-size: 0.875rem;
      font-weight: 500;
      margin-bottom: 20px;
      animation: slideIn 0.3s ease;
    }
    .client-msg svg { width: 18px; height: 18px; flex-shrink: 0; }
    .client-msg.error {
      background: rgba(220,60,60,0.1);
      border: 1px solid rgba(220,60,60,0.25);
      color: #e05050;
    }
    .client-msg.success {
      background: rgba(0,160,75,0.12);
      border: 1px solid rgba(0,160,75,0.3);
      color: var(--at-green-light);
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(-8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .client-submit {
      width: 100%;
      justify-content: center;
      margin-top: 8px;
    }

    /* ── Connected dashboard ── */
    .client-dash-wrap {
      max-width: 680px;
      margin: 0 auto;
      padding: calc(var(--nav-h) + 40px) 24px 80px;
      min-height: 100vh;
    }
    .client-dash-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--r-lg);
      padding: 40px 36px;
      box-shadow: var(--shadow-md);
    }
    .client-dash-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 32px;
      padding-bottom: 20px;
      border-bottom: 1px solid var(--border);
    }
    .client-dash-header h1 {
      font-size: 1.3rem;
      color: var(--text-primary);
      margin: 0;
    }
    .client-dash-header h1 span { color: var(--at-green-light); }
    .client-info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .client-info-field label {
      display: block;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-muted);
      margin-bottom: 4px;
    }
    .client-info-field .val {
      color: var(--text-primary);
      font-size: 0.95rem;
    }
    .client-dash-footer {
      margin-top: 32px;
      padding-top: 20px;
      border-top: 1px solid var(--border);
      text-align: center;
    }

    @media (max-width: 540px) {
      .client-login-box { padding: 32px 20px; }
      .client-info-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
<script>if(localStorage.getItem('at-theme')==='dark')document.body.classList.add('dark-mode');</script>

<nav class="navbar">
  <a href="index.html" class="nav-brand">
    <img src="AlgerieTelecom.png" alt="Logo Algérie Télécom" class="nav-logo">
    <span class="nav-title">Algérie <span>Télécom</span></span>
  </a>
  <?php if (isset($_SESSION['at_client'])): ?>
  <div style="display:flex;align-items:center;gap:16px;padding-right:8px;">
    <span style="font-size:0.82rem;color:var(--text-muted);">
      Espace Client
    </span>
    <a href="test.php?logout=1" class="btn btn-outline" style="font-size:0.78rem;padding:6px 14px;">
      <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:currentColor;"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
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

<?php if (!isset($_SESSION['at_client'])): ?>

<!-- ══════════════════════════════════════════════════════
     LOGIN / SIGNUP
═══════════════════════════════════════════════════════ -->
<div class="client-login-wrap">
  <div class="client-login-box">
    <div class="client-login-logo">
      <img src="AlgerieTelecom.png" alt="Logo">
      <h2>Espace Client</h2>
      <p>Inscrivez-vous ou connectez-vous pour gérer vos services</p>
    </div>

    <?php if ($errMsg): ?>
    <div class="client-msg error">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
      <?= $errMsg ?>
    </div>
    <?php endif; ?>
    <?php if ($successMsg): ?>
    <div class="client-msg success">
      <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
      <?= $successMsg ?>
    </div>
    <?php endif; ?>

    <div class="client-tabs" role="tablist">
      <button id="tab-signin" class="<?= $activeForm === 'signin' ? 'active' : '' ?>" onclick="switchTab('signin')">Connexion</button>
      <button id="tab-signup" class="<?= $activeForm === 'signup' ? 'active' : '' ?>" onclick="switchTab('signup')">Inscription</button>
    </div>

    <!-- SIGN IN -->
    <form id="form-signin" class="client-form <?= $activeForm === 'signin' ? 'active' : '' ?>" method="POST" autocomplete="on">
      <input type="hidden" name="action" value="login">
      <div class="form-group">
        <label for="email-signin">Adresse e-mail</label>
        <input id="email-signin" type="email" name="email" placeholder="exemple@email.com" autocomplete="email" required>
      </div>
      <div class="form-group">
        <label for="password-signin">Mot de passe</label>
        <input id="password-signin" type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
      </div>
      <button type="submit" class="btn btn-primary client-submit">
        <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
        Se connecter
      </button>
    </form>

    <!-- SIGN UP -->
    <form id="form-signup" class="client-form <?= $activeForm === 'signup' ? 'active' : '' ?>" method="POST" autocomplete="on">
      <input type="hidden" name="action" value="signup">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <input id="prenom" type="text" name="prenom" placeholder="Votre prénom" autocomplete="given-name" required>
        </div>
        <div class="form-group">
          <label for="nom">Nom</label>
          <input id="nom" type="text" name="nom" placeholder="Votre nom" autocomplete="family-name" required>
        </div>
      </div>
      <div class="form-group">
        <label for="email-signup">Adresse e-mail</label>
        <input id="email-signup" type="email" name="email" placeholder="exemple@email.com" autocomplete="email" required>
      </div>
      <div class="form-group">
        <label for="telephone">Numéro de téléphone</label>
        <input id="telephone" type="tel" name="telephone" placeholder="05XX XX XX XX" autocomplete="tel">
      </div>
      <div class="form-group">
        <label for="password-signup">Mot de passe</label>
        <input id="password-signup" type="password" name="password" placeholder="Minimum 6 caractères" autocomplete="new-password" required>
      </div>
      <div class="form-group">
        <label for="wilaya_id">Wilaya</label>
        <select id="wilaya_id" name="wilaya_id" required>
          <option value="">— Sélectionnez une wilaya —</option>
          <?php
          $wilayas = getPDO()->query("SELECT id, nom FROM wilaya ORDER BY id")->fetchAll();
          foreach ($wilayas as $w): ?>
            <option value="<?= (int)$w['id'] ?>"><?= h($w['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary client-submit">
        <svg viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        Créer mon compte
      </button>
    </form>
  </div>
</div>

<?php else: $c = $_SESSION['at_client']; ?>

<!-- ══════════════════════════════════════════════════════
     CONNECTED DASHBOARD
═══════════════════════════════════════════════════════ -->
<div class="client-dash-wrap">
  <div class="client-dash-card">
    <div class="client-dash-header">
      <h1>Bienvenue, <span><?= h($c['prenom']) ?></span> 👋</h1>
      <a href="test.php?logout=1" class="btn btn-outline" style="font-size:0.8rem;padding:8px 18px;">Déconnexion</a>
    </div>
    <p style="color:var(--text-muted);margin-top:0;">Voici les informations de votre compte :</p>
    <div class="client-info-grid">
      <div class="client-info-field">
        <label>Prénom</label>
        <div class="val"><?= h($c['prenom']) ?></div>
      </div>
      <div class="client-info-field">
        <label>Nom</label>
        <div class="val"><?= h($c['nom']) ?></div>
      </div>
      <div class="client-info-field">
        <label>Adresse e-mail</label>
        <div class="val"><?= h($c['email']) ?></div>
      </div>
      <div class="client-info-field">
        <label>Téléphone</label>
        <div class="val"><?= $c['telephone'] ? h($c['telephone']) : '<span style="color:var(--text-muted)">—</span>' ?></div>
      </div>
    </div>
    <div class="client-dash-footer">
      <a href="index.html" class="btn btn-primary" style="font-size:0.85rem;padding:10px 28px;">
        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        Retour à l'accueil
      </a>
    </div>
  </div>
</div>

<?php endif; ?>

<script>
(function(){
  const btn = document.getElementById('theme-toggle-btn');
  if (btn) {
    btn.addEventListener('click', () => {
      const isDark = document.body.classList.toggle('dark-mode');
      localStorage.setItem('at-theme', isDark ? 'dark' : 'light');
    });
  }
})();

function switchTab(tab) {
  document.getElementById('tab-signin')?.classList.toggle('active', tab === 'signin');
  document.getElementById('tab-signup')?.classList.toggle('active', tab === 'signup');
  document.getElementById('form-signin')?.classList.toggle('active', tab === 'signin');
  document.getElementById('form-signup')?.classList.toggle('active', tab === 'signup');
}
</script>

</body>
</html>
