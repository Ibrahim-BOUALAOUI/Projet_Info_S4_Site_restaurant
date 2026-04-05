<?php
session_start();
$json  = file_get_contents("../Folder_Data/utilisateur.json");
$users = json_decode($json, true);

$json_commandes  = file_get_contents("../Folder_Data/commandes.json");
$commandes = json_decode($json_commandes, true);

if (isset($_COOKIE['last_connection'])) {
    $last_connection = $_COOKIE['last_connection'];
} else {
    $last_connection = 'N/A';
}

/* ── Traitement du formulaire utilisateurs ── */
if (isset($_POST['action'])) {

    if ($_POST['action'] == 'delete' && isset($_POST['user_delete'])) {
        $ids_a_supprimer = $_POST['user_delete'];
        $users = array_filter($users, function($user) use ($ids_a_supprimer) {
            return !in_array($user['id'], $ids_a_supprimer);
        });
    }

    if ($_POST['action'] == 'update' && isset($_POST['permission'])) {
        foreach ($users as &$user) {
            if (isset($_POST['permission'][$user['id']])) {
                $user['permission'] = $_POST['permission'][$user['id']];
            }
        }
    }

    file_put_contents("../Folder_Data/utilisateur.json",
        json_encode(array_values($users), JSON_PRETTY_PRINT));
}

/* ── Traitement du statut commande ── */
if (isset($_POST['action']) && $_POST['action'] == 'update_commande' && isset($_POST['commande_id'])) {
    foreach ($commandes as &$cmd) {
        if ($cmd['id'] == $_POST['commande_id']) {
            $cmd['statut'] = $_POST['nouveau_statut'];
            break;
        }
    }
    unset($cmd);
    file_put_contents("../Folder_Data/commandes.json",
        json_encode(array_values($commandes), JSON_PRETTY_PRINT));
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* ── Filtre commandes par client ── */
$filtre_client = $_GET['client'] ?? '';
$commandes_filtrees = $filtre_client
    ? array_filter($commandes, fn($c) => $c['client'] === $filtre_client)
    : $commandes;

/* ── Stats rapides ── */
$total_commandes   = count($commandes);
$total_utilisateurs = count($users);
$ca_total = array_sum(array_map(function($c) {
    return array_sum(array_column($c['articles'], 'prix'));
}, $commandes));
$en_cours = count(array_filter($commandes, fn($c) => $c['statut'] !== 'livree'));

/* ── Couleurs statuts ── */
function statut_class(string $s): string {
    return match($s) {
        'livree'                 => 'statut-livre',
        'en cours de livraison'  => 'statut-livraison',
        'a preparer'             => 'statut-preparer',
        default                  => 'statut-autre',
    };
}
function statut_label(string $s): string {
    return match($s) {
        'livree'                => '✅ Livrée',
        'en cours de livraison' => '🛵 En livraison',
        'a preparer'            => '👨‍🍳 À préparer',
        default                 => ucfirst($s),
    };
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../Folder CSS/Admin.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — Le 129</title>
</head>
<body>

<header>
    <a href="index.php">
        <img src="../Folder img/129.png" alt="Logo" width="180">
    </a>
    <nav class="admin-nav">
        <a href="#section-stats"     class="nav-link">📊 Stats</a>
        <a href="#section-users"     class="nav-link">👥 Utilisateurs</a>
        <a href="#section-commandes" class="nav-link">🛒 Commandes</a>
    </nav>
    <span class="admin-badge">⚙️ Administration</span>
</header>

<div class="admin-container">

    
    <section id="section-stats" class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon">🛒</span>
            <span class="stat-value"><?= $total_commandes ?></span>
            <span class="stat-label">Commandes totales</span>
        </div>
        <div class="stat-card">
            <span class="stat-icon">⏳</span>
            <span class="stat-value"><?= $en_cours ?></span>
            <span class="stat-label">En cours</span>
        </div>
        <div class="stat-card">
            <span class="stat-icon">👥</span>
            <span class="stat-value"><?= $total_utilisateurs ?></span>
            <span class="stat-label">Utilisateurs</span>
        </div>
        <div class="stat-card stat-card-gold">
            <span class="stat-icon">💰</span>
            <span class="stat-value"><?= number_format($ca_total, 2, ',', ' ') ?> €</span>
            <span class="stat-label">CA total</span>
        </div>
    </section>

    
    <section id="section-users" class="admin-section">
        <h2 class="section-title">👥 Gestion des utilisateurs</h2>

        <form method="post">
            <div class="table-actions">
                <div class="action-left">
                    <button class="delete-btn" type="submit" name="action" value="delete">
                        🗑️ Supprimer la sélection
                    </button>
                </div>
                <div class="action-right">
                    <span><?= $total_utilisateurs ?> utilisateur(s)</span>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <caption>Liste des utilisateurs</caption>
                    <thead>
                        <tr>
                            <th class="col-checkbox">Sélection</th>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Dernière connexion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="col-checkbox">
                                <input type="checkbox" name="user_delete[]"
                                    value="<?= $user['id'] ?>" id="user<?= $user['id'] ?>">
                                <label for="user<?= $user['id'] ?>"></label>
                            </td>
                            <td><strong><?= htmlspecialchars(substr($user['id'], 0, 8)) ?>…</strong></td>
                            <td><?= htmlspecialchars(($user['name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <select name="permission[<?= $user['id'] ?>]" class="select-status">
                                    <option value="">—</option>
                                    <?php
                                    $roles = ['User' => 'Client', 'livreur' => 'Livreur',
                                              'preparateur' => 'Préparateur', 'admin' => 'Admin'];
                                    foreach ($roles as $value => $label):
                                    ?>
                                        <option value="<?= $value ?>"
                                            <?= (($user['type'] ?? $user['permission'] ?? '') == $value) ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><span class="date-badge"><?= htmlspecialchars($user['last_connection'] ?? $last_connection) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="action-buttons">
                <button class="btn-secondary" type="reset">↩️ Annuler</button>
                <button class="btn-primary" type="submit" name="action" value="update">✅ Valider les modifications</button>
            </div>
        </form>
    </section>

    
    <section id="section-commandes" class="admin-section">
        <h2 class="section-title">🛒 Gestion des commandes</h2>

        <!-- Filtre par client -->
        <form method="get" class="filter-form">
            <a href="admin.php" class="btn-secondary <?= $filtre_client ? '' : 'active' ?>">Toutes</a>
            <?php
            $clients_uniques = array_unique(array_column($commandes, 'client'));
            sort($clients_uniques);
            foreach ($clients_uniques as $c):
            ?>
                <a href="?client=<?= urlencode($c) ?>"
                   class="btn-secondary <?= $filtre_client === $c ? 'active' : '' ?>">
                    <?= htmlspecialchars($c) ?>
                </a>
            <?php endforeach; ?>
        </form>

        <div class="commandes-grid">
            <?php foreach ($commandes_filtrees as $cmd): ?>
            <div class="commande-card <?= statut_class($cmd['statut']) ?>-card">

                
                <div class="cmd-header">
                    <span class="cmd-id">#<?= $cmd['id'] ?></span>
                    <span class="cmd-date"><?= htmlspecialchars($cmd['date']) ?></span>
                </div>

                
                <div class="cmd-client">
                    <span class="cmd-label">👤 Client</span>
                    <span><?= htmlspecialchars($cmd['client']) ?></span>
                </div>
                <div class="cmd-client">
                    <span class="cmd-label">📍 Adresse</span>
                    <span><?= htmlspecialchars($cmd['adresse']) ?></span>
                </div>

                
                <div class="cmd-articles">
                    <span class="cmd-label">🧾 Articles</span>
                    <ul>
                        <?php foreach ($cmd['articles'] as $art): ?>
                            <li>
                                <span><?= htmlspecialchars($art['nom']) ?></span>
                                <span class="art-prix"><?= number_format($art['prix'], 2, ',', ' ') ?> €</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="cmd-total">
                        Total : <strong><?= number_format(array_sum(array_column($cmd['articles'], 'prix')), 2, ',', ' ') ?> €</strong>
                    </div>
                </div>

                
                <div class="cmd-footer">
                    <span class="statut-badge <?= statut_class($cmd['statut']) ?>">
                        <?= statut_label($cmd['statut']) ?>
                    </span>
                    <form method="post" class="statut-form">
                        <input type="hidden" name="action" value="update_commande">
                        <input type="hidden" name="commande_id" value="<?= $cmd['id'] ?>">
                        <select name="nouveau_statut" class="select-status">
                            <?php
                            $statuts = ['a preparer' => '👨‍🍳 À préparer',
                                        'en cours de livraison' => '🛵 En livraison',
                                        'livree' => '✅ Livrée'];
                            foreach ($statuts as $val => $lbl):
                            ?>
                                <option value="<?= $val ?>" <?= $cmd['statut'] === $val ? 'selected' : '' ?>>
                                    <?= $lbl ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-update">Mettre à jour</button>
                    </form>
                </div>

            </div>
            <?php endforeach; ?>
        </div>
    </section>

</div>
</body>
</html>
