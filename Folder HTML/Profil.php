<?php
session_start();


if (!isset($_SESSION['connecte'])) {
    header("Location: index.php");
    exit();
}

// Recupere les info depuis le json
$users = json_decode(file_get_contents('../Folder_Data/utilisateur.json'), true);
$userActuel = null;

foreach ($users as $user) {
    if ($user['email'] === $_SESSION['email']) {
        $userActuel = $user;
        break;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="../Folder CSS/Profil.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
</head>

<body>
    <header>
        <a href="index.php">
            <img src="../Folder img/129.png" alt="Logo" width="200">
        </a>
        <a href="deconnection.php" class="btn-deconnexion">🚪 Se déconnecter</a>
    </header>

    <main class="rect-mid">


        <section class="informations">
            <h2 class="section-title"> Vos informations</h2>

            <div class="info-content">
                <div class="info-item">
                    <span class="info-label">Prénom :</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['nom']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Adresse :</span>
                    <span class="info-value"><?= htmlspecialchars($userActuel['adress']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Téléphone :</span>
                    <span class="info-value"><?= htmlspecialchars($userActuel['phone']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Email :</span>
                    <span class="info-value"><?= htmlspecialchars($_SESSION['email']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Date de naissance :</span>
                    <span class="info-value"><?= htmlspecialchars($userActuel['birthdate']) ?></span>
                </div>
            </div>

            <a href="modifier_profil.php">
                <button class="btn-modifier">✏️ Modifier mes informations</button>
            </a>
        </section>


        <section class="commandes-section">
            <h2 class="section-title">🛍️ Vos dernières commandes</h2>

            <div class="commandes-grid">

                <article class="commande-card">
                    <div class="commande-image">
                        <img src="../Folder img/Chiken_tendori_logo.png" alt="Menu Chicken Tandoori">
                    </div>
                    <div class="commande-info">
                        <h3 class="commande-nom">Menu Chicken Tandoori</h3>
                        <p class="commande-date"> 12 Février 2026</p>
                        <p class="commande-prix">15,90 €</p>
                        <span class="commande-statut livré">Livré</span>
                    </div>
                    <a href="Menus.php"> <button class="btn-recommander"> Recommander</button> </a>
                </article>


                <article class="commande-card">
                    <div class="commande-image">
                        <img src="../Folder img/special.png" alt="Menu Spécial">
                    </div>
                    <div class="commande-info">
                        <h3 class="commande-nom">Menu Spécial</h3>
                        <p class="commande-date"> 8 Février 2026</p>
                        <p class="commande-prix">17,50 €</p>
                        <span class="commande-statut livré">Livré</span>
                    </div>
                    <a href="Menus.php"> <button class="btn-recommander"> Recommander</button> </a>
                </article>


                <article class="commande-card">
                    <div class="commande-image">
                        <img src="../Folder img/steak.png" alt="Menu Steak">
                    </div>
                    <div class="commande-info">
                        <h3 class="commande-nom">Menu Steak</h3>
                        <p class="commande-date"> 5 Février 2026</p>
                        <p class="commande-prix">16,90 €</p>
                        <span class="commande-statut livré">Livré</span>
                    </div>
                    <a href="Menus.php"> <button class="btn-recommander"> Recommander</button> </a>
                </article>
            </div>
        </section>


        <section class="actions-section">
            <h2 class="section-title">⚡ Actions rapides</h2>

            <div class="actions-grid">
                <a href="Menus.php" class="action-card">
                    <span class="action-icon">🍔</span>
                    <span class="action-text">Commander</span>
                </a>

                <a href="Avis.php" class="action-card">
                    <span class="action-icon">⭐</span>
                    <span class="action-text">Donner un avis</span>
                </a>

                <a href="#" class="action-card">
                    <span class="action-icon">🎁</span>
                    <span class="action-text">Récompenses</span>
                </a>
            </div>
        </section>

    </main>
</body>

</html>