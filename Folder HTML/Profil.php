<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Récupération de l'utilisateur
$users = json_decode(file_get_contents('../Folder_Data/utilisateur.json'), true) ?? [];
$userActuel = null;
foreach ($users as $user) {
    if ($user['email'] === $_SESSION['email']) {
        $userActuel = $user;
        break;
    }
}

// Récupération des commandes
$commandesData = json_decode(file_get_contents('../Folder_Data/commandes.json'), true) ?? [];
$mesCommandes = [];
foreach ($commandesData as $cmd) {
    if ($cmd['client'] === $_SESSION['email']) {
        $mesCommandes[] = $cmd;
    }
}

$mesCommandes = array_reverse($mesCommandes);

// Récupération des avis déjà laissés par l'utilisateur
$avisData = file_exists('../Folder_Data/avis.json')
    ? json_decode(file_get_contents('../Folder_Data/avis.json'), true)
    : [];

if (!is_array($avisData)) {
    $avisData = [];
}

$commandesDejaNotees = [];
foreach ($avisData as $avis) {
    if (($avis['client'] ?? '') === $_SESSION['email']) {
        $commandesDejaNotees[] = (string)($avis['commande_id'] ?? '');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Le 129</title>
    <link rel="stylesheet" href="../Folder CSS/Profil.css">
    <link rel="stylesheet" id="style-sombre" href="">
    <script src="../Folder_JS/affichage.js" defer></script>
</head>

<body>
    <header class="header-profil" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 50px; border-bottom: 2px solid #F67D00;">
        <a href="index.php">
            <img src="../Folder img/129.png" alt="Logo" width="200">
        </a>
        <a href="deconnection.php" class="btn-deconnexion" style="text-decoration: none;">
            🚪 Se déconnecter
        </a>
    </header>

    <main class="rect-mid">
        <section class="informations">
            <h2 class="section-title"> Vos informations</h2>
            <div class="info-content">
                <div class="info-item"><span class="info-label">Prénom :</span> <span class="info-value"><?= htmlspecialchars($userActuel['name'] ?? 'Non renseigné') ?></span></div>
                <div class="info-item"><span class="info-label">Adresse :</span> <span class="info-value"><?= htmlspecialchars($userActuel['adress'] ?? 'Non renseignée') ?></span></div>
                <div class="info-item"><span class="info-label">Téléphone :</span> <span class="info-value"><?= htmlspecialchars($userActuel['phone'] ?? 'Non renseigné') ?></span></div>
                <div class="info-item"><span class="info-label">Email :</span> <span class="info-value"><?= htmlspecialchars($_SESSION['email']) ?></span></div>
            </div>
            <a href="modifier_profil.php"><button class="btn-modifier">✏️ Modifier</button></a>
        </section>

        <section class="commandes-section">
            <h2 class="section-title">🛍️ Vos dernières commandes</h2>
            <div class="commandes-grid">
                <?php if (empty($mesCommandes)): ?>
                    <p style='color:white;'>Vous n'avez pas encore passé de commande.</p>
                <?php endif; ?>

                <?php foreach ($mesCommandes as $cmd):
                    $totalPrix = 0;
                    foreach ($cmd['articles'] as $art) {
                        $totalPrix += $art['prix'];
                    }

                    $statut = $cmd['statut'] ?? 'en_cours';
                    $typeCmd = $cmd['type_commande'] ?? 'livraison';
                    $dejaNote = ($cmd['deja_note'] ?? false) || in_array((string)($cmd['id'] ?? ''), $commandesDejaNotees, true);

                    $couleurStatus = "#f39c12";
                    if ($statut === "livree") $couleurStatus = "#2ecc71";
                    if ($statut === "abandonnee") $couleurStatus = "#e74c3c";

                    $peutNoter = ($statut === "livree" && $typeCmd === "livraison" && !$dejaNote);
                ?>
                    <article class="commande-card">
                        <div class="commande-image"><img src="../Folder img/129.png" alt="Commande"></div>
                        <div class="commande-info">
                            <h3 class="commande-nom"><?= htmlspecialchars($cmd['articles'][0]['nom']) ?><?php if (count($cmd['articles']) > 1) echo " (+" . (count($cmd['articles']) - 1) . ")"; ?></h3>
                            <p class="commande-date"><?= htmlspecialchars($cmd['date']) ?></p>
                            <p class="commande-prix"><?= number_format($totalPrix, 2) ?> €</p>
                            <span class="commande-statut" style="background-color: <?= $couleurStatus ?>; color: white; padding: 3px 10px; border-radius: 5px; font-weight: bold; font-size: 0.8em; text-transform: uppercase;">
                                <?= htmlspecialchars($statut) ?>
                            </span>
                        </div>
                        <div class="commande-actions" style="display: flex; flex-direction: column; gap: 5px; margin-top: 10px;">
                            <a href="Menus.php"><button class="btn-recommander" style="width: 100%;">Recommander</button></a>

                            <?php if ($cmd['statut'] === "a preparer"): ?>
                                <a href="modifier_commande.php?id_cmd=<?= urlencode($cmd['id']) ?>">
                                    <button class="btn-modifier" style="background-color: #3498db; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px; font-weight: bold; width: 100%;">
                                        ✏️ Modifier
                                    </button>
                                </a>
                            <?php endif; ?>

                            <?php if ($peutNoter): ?>
                                <a href="Avis.php?commande_id=<?= urlencode($cmd['id']) ?>">
                                    <button class="btn-modifier" style="background-color: #F67D00; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px; font-weight: bold; width: 100%;">
                                        ⭐ Noter la commande
                                    </button>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="actions-section">
            <h2 class="section-title">⚡ Actions rapides</h2>
            <div class="actions-grid">
                <a href="Menus.php" class="action-card"><span class="action-icon">🍔</span><span class="action-text">Commander</span></a>
                <a href="Avis.php" class="action-card"><span class="action-icon">⭐</span><span class="action-text">Avis</span></a>
            </div>
        </section>
    </main>
</body>

</html>
