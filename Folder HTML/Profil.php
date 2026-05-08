<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$users = json_decode(file_get_contents('../Folder_Data/utilisateur.json'), true) ?? [];
$userActuel = null;
for ($i = 0; $i < count($users); $i++) {
    if ($users[$i]['email'] === $_SESSION['email']) {
        $userActuel = $users[$i];
        break;
    }
}

$commandesData = json_decode(file_get_contents('../Folder_Data/commandes.json'), true) ?? [];
$mesCommandes = [];
for ($i = 0; $i < count($commandesData); $i++) {
    if ($commandesData[$i]['client'] === $_SESSION['email']) {
        $mesCommandes[] = $commandesData[$i];
    }
}

$mesCommandes = array_reverse($mesCommandes);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>

    <!-- Style de base du profil -->
    <link rel="stylesheet" href="../Folder CSS/Profil.css">

    <!-- Balise pour le mode sombre -->
    <link rel="stylesheet" id="style-sombre" href="">

    <script src="../Folder JS/affichage.js" defer></script>
</head>

<body>
    <header style="display: flex; align-items: center; justify-content: space-between; padding: 10px 50px; background-color: #1b0730; border-bottom: 2px solid #F67D00;">
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
                <div class="info-item">
                    <span class="info-label">Prénom :</span>
                    <span class="info-value"><?= htmlspecialchars($userActuel['name'] ?? 'Non renseigné') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Adresse :</span>
                    <span class="info-value"><?= htmlspecialchars($userActuel['adress'] ?? 'Non renseignée') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Téléphone :</span>
                    <span class="info-value"><?= htmlspecialchars($userActuel['phone'] ?? 'Non renseigné') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email :</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['email']) ?></span>
                </div>
            </div>
            <a href="modifier_profil.php"><button class="btn-modifier">✏️ Modifier</button></a>
        </section>

        <section class="commandes-section">
            <h2 class="section-title">🛍️ Vos dernières commandes</h2>
            <div class="commandes-grid">

                <?php
                if (count($mesCommandes) === 0) {
                    echo "<p style='color:white;'>Vous n'avez pas encore passé de commande.</p>";
                }

                for ($i = 0; $i < count($mesCommandes); $i++) {
                    $cmd = $mesCommandes[$i];

                    $totalPrix = 0;
                    for ($j = 0; $j < count($cmd['articles']); $j++) {
                        $totalPrix += $cmd['articles'][$j]['prix'];
                    }

                    $couleurStatus = "#f39c12"; 
                    if ($cmd['statut'] === "livree") {
                        $couleurStatus = "#2ecc71";
                    }
                    if ($cmd['statut'] === "abandonnee") {
                        $couleurStatus = "#e74c3c";
                    }
                ?>
                    <article class="commande-card">
                        <div class="commande-image">
                            <img src="../Folder img/129.png" alt="Commande">
                        </div>
                        <div class="commande-info">
                            <h3 class="commande-nom">
                                <?php
                                echo htmlspecialchars($cmd['articles'][0]['nom']);
                                if (count($cmd['articles']) > 1) echo " (+" . (count($cmd['articles']) - 1) . ")";
                                ?>
                            </h3>
                            <p class="commande-date"><?= $cmd['date'] ?></p>
                            <p class="commande-prix"><?= number_format($totalPrix, 2) ?> €</p>

                            <span class="commande-statut" style="background-color: <?= $couleurStatus ?>; color: white; padding: 3px 10px; border-radius: 5px; font-weight: bold; font-size: 0.8em; text-transform: uppercase;">
                                <?= htmlspecialchars($cmd['statut']) ?>
                            </span>
                        </div>
                        <a href="Menus.php"><button class="btn-recommander">Recommander</button></a>
                    </article>
                <?php } ?>

            </div>
        </section>

        <section class="actions-section">
            <h2 class="section-title">⚡ Actions rapides</h2>
            <div class="actions-grid">
                <a href="Menus.php" class="action-card">
                    <span class="action-icon">🍔</span><span class="action-text">Commander</span>
                </a>
                <a href="Avis.php" class="action-card">
                    <span class="action-icon">⭐</span><span class="action-text">Avis</span>
                </a>
            </div>
        </section>
    </main>
</body>

</html>
