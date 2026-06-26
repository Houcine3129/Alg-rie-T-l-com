<?php
require_once __DIR__ . '/php/config.php';
session_start();

// Ensure a getPDO() function is available. If config.php defines it, keep that.
if (!function_exists('getPDO')) {
    function getPDO(): PDO
    {
        if (defined('DB_DSN')) {
            $user = defined('DB_USER') ? DB_USER : null;
            $pass = defined('DB_PASS') ? DB_PASS : null;
            $opts = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            return new PDO(DB_DSN, $user, $pass, $opts);
        }
        throw new RuntimeException("Database connection not configured. Define getPDO() or DB_DSN in config.php");
    }
}

if (!isset($_SESSION['at_admin'])) {
    header('Location: admin.php');
    exit;
}

try {
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT id, prenom, nom, email, telephone, wilaya_id, date_creation FROM client ORDER BY id DESC");
    $clients = $stmt->fetchAll();
    $totalClients = count($clients);
} catch (PDOException $e) {
    die('Erreur base de données : ' . htmlspecialchars($e->getMessage()));
}

function h(mixed $v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — Clients</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .adm-wrap { max-width: 1240px; margin: 0 auto; padding: calc(var(--nav-h) + 40px) 24px 80px; min-height: 100vh; }
        .adm-page-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 40px; padding-bottom: 24px; border-bottom: 1px solid var(--border); }
        .adm-page-header h1 { font-size: clamp(1.4rem, 3vw, 1.9rem); color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 12px; }
        .adm-page-header h1 span.adm-dot { width: 10px; height: 10px; background: var(--at-green); border-radius: 50%; display: inline-block; box-shadow: 0 0 0 4px var(--at-green-dim); }
        .adm-stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 24px 20px 20px; text-align: center; transition: box-shadow var(--t), transform var(--t); display: inline-block; min-width: 200px; margin-bottom: 32px; }
        .adm-stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .adm-stat-card .num { font-family: 'Syne', sans-serif; font-size: 2.4rem; font-weight: 800; line-height: 1; color: var(--at-green-light); }
        .adm-stat-card .lbl { font-size: 0.78rem; color: var(--text-muted); margin-top: 6px; text-transform: uppercase; letter-spacing: .5px; }
        .adm-section { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-lg); overflow: hidden; }
        .adm-section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--border); background: var(--bg-subtle); }
        .adm-section-header h2 { margin: 0; font-size: 1rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
        .adm-section-header h2 svg { width: 18px; height: 18px; fill: var(--at-green); }
        .adm-table-wrap { overflow-x: auto; }
        table.adm-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; color: var(--text-secondary); }
        .adm-table th { padding: 12px 18px; text-align: left; font-size: 0.72rem; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border); white-space: nowrap; background: var(--bg-subtle); }
        .adm-table td { padding: 14px 18px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .adm-table tr:last-child td { border-bottom: none; }
        .adm-table tr:hover td { background: var(--bg-hover); }
        .adm-table td.date-cell { white-space: nowrap; color: var(--text-muted); font-size: 0.8rem; }
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; letter-spacing: .3px; white-space: nowrap; }
        .badge-new { background: rgba(0,160,75,0.14); color: var(--at-green-light); }
        .adm-empty { text-align: center; padding: 64px 24px; color: var(--text-muted); }
        .adm-empty svg { width: 56px; height: 56px; fill: var(--border); margin-bottom: 16px; }
        .adm-empty p { font-size: 0.95rem; margin: 0; }
        .adm-link-back { display: inline-flex; align-items: center; gap: 6px; color: var(--at-green); text-decoration: none; font-size: 0.9rem; font-weight: 500; }
        .adm-link-back:hover { text-decoration: underline; }
    </style>
</head>
<body>
<script>if(localStorage.getItem('at-theme')==='dark')document.body.classList.add('dark-mode');</script>

<nav class="navbar">
    <a href="index.html" class="nav-brand">
        <img src="AlgerieTelecom.png" alt="Logo Algérie Télécom" class="nav-logo">
        <span class="nav-title">Algérie <span>Télécom</span></span>
    </a>
    <div style="display:flex;align-items:center;gap:16px;padding-right:8px;">
        <a href="admin.php" class="adm-link-back" style="font-size:0.82rem;">
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:currentColor;"><path d="M19 11H7.83l4.88-4.88c.39-.39.39-1.03 0-1.42-.39-.39-1.02-.39-1.41 0l-6.59 6.59c-.39.39-.39 1.02 0 1.41l6.59 6.59c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L7.83 13H19c.55 0 1-.45 1-1s-.45-1-1-1z"/></svg>
            Retour au tableau de bord
        </a>
        <a href="admin.php?logout=1" class="btn btn-outline" style="font-size:0.78rem;padding:6px 14px;">
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:currentColor;"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
            Déconnexion
        </a>
    </div>
    <button id="theme-toggle-btn" class="theme-toggle" aria-label="Basculer thème" title="Changer de thème">
        <div class="theme-toggle-track">
            <div class="theme-toggle-thumb">
                <svg class="theme-toggle-icon icon-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/></svg>
                <svg class="theme-toggle-icon icon-sun" viewBox="0 0 24 24"><path d="M12 4a8 8 0 1 0 0 16A8 8 0 0 0 12 4zm0-2a1 1 0 0 1 1 1v1a1 1 0 0 1-2 0V3a1 1 0 0 1 1-1zm0 18a1 1 0 0 1 1 1v1a1 1 0 0 1-2 0v-1a1 1 0 0 1 1-1zm9-9h1a1 1 0 0 1 0 2h-1a1 1 0 0 1 0-2zM3 12H2a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm15.364-6.364.707-.707a1 1 0 1 1 1.414 1.414l-.707.707a1 1 0 0 1-1.414-1.414zM4.929 18.364l-.707.707a1 1 0 0 1-1.414-1.414l.707-.707a1 1 0 0 1 1.414 1.414zM18.364 19.07l.707.707a1 1 0 0 1-1.414 1.414l-.707-.707a1 1 0 0 1 1.414-1.414zM5.636 5.636 4.929 4.93a1 1 0 0 1 1.414-1.414l.707.707A1 1 0 1 1 5.636 5.636z"/></svg>
            </div>
        </div>
    </button>
</nav>

<div class="adm-wrap">
    <div class="adm-page-header">
        <h1>
            <span class="adm-dot"></span>
            Nouveaux Clients
        </h1>
    </div>

    <div class="adm-stat-card">
        <div class="num"><?= $totalClients ?></div>
        <div class="lbl">Total clients inscrits</div>
    </div>

    <div class="adm-section">
        <div class="adm-section-header">
            <h2>
                <svg viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                Liste des inscrits
            </h2>
        </div>

        <?php if (empty($clients)): ?>
        <div class="adm-empty">
            <svg viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            <p>Aucun client inscrit pour le moment.</p>
        </div>
        <?php else: ?>
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Wilaya</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;font-weight:700;">#<?= (int)$client['id'] ?></td>
                        <td style="color:var(--text-primary);font-weight:600;"><?= h($client['prenom'] . ' ' . $client['nom']) ?></td>
                        <td><a href="mailto:<?= h($client['email']) ?>" style="color:var(--at-green);text-decoration:none;font-size:0.82rem;"><?= h($client['email']) ?></a></td>
                        <td style="color:var(--text-muted);font-size:0.82rem;"><?= $client['telephone'] ? h($client['telephone']) : '<span style="color:var(--text-muted)">—</span>' ?></td>
                        <td style="color:var(--text-muted);font-size:0.82rem;">
                            <?php
                            try {
                                $wstmt = $pdo->prepare("SELECT nom FROM wilaya WHERE id = ?");
                                $wstmt->execute([(int)$client['wilaya_id']]);
                                $w = $wstmt->fetchColumn();
                                echo $w ? h($w) : '<span style="color:var(--text-muted)">—</span>';
                            } catch (Exception $e) {
                                echo '<span style="color:var(--text-muted)">—</span>';
                            }
                            ?>
                        </td>
                        <td class="date-cell"><?= date('d/m/Y', strtotime($client['date_creation'])) ?><br><span style="font-size:0.75rem;"><?= date('H:i', strtotime($client['date_creation'])) ?></span></td>
                        <td><span class="badge badge-new">Nouveau</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

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
</script>
</body>
</html>
