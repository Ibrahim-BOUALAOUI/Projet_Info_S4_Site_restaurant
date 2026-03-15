<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="../Folder CSS/Profil.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
</head>

<body>
    <!-- En-tête avec logo -->
    <header>
        <a href="Accueil.php">
            <img src="../Folder CSS/129.png" alt="Logo" width="200">
        </a>
    </header>

    <main class="rect-mid">


        <section class="informations">
            <h2 class="section-title"> Vos informations</h2>

            <div class="info-content">
                <div class="info-item">
                    <span class="info-label">Prénom :</span>
                    <span class="info-value">Poline</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Adresse :</span>
                    <span class="info-value">147 Rue de Macron</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Téléphone :</span>
                    <span class="info-value">06 40 80 94 50</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Email :</span>
                    <span class="info-value">poline@example.com</span>
                </div>
            </div>

            <button class="btn-modifier">✏️ Modifier mes informations</button>
        </section>

        <!-- Section Dernières Commandes -->
        <section class="commandes-section">
            <h2 class="section-title">🛍️ Vos dernières commandes</h2>

            <div class="commandes-grid">
                <!-- Commande 1 -->
                <article class="commande-card">
                    <div class="commande-image">
                        <img src="../Folder CSS/Chiken_tendori_logo.png" alt="Menu Chicken Tandoori">
                    </div>
                    <div class="commande-info">
                        <h3 class="commande-nom">Menu Chicken Tandoori</h3>
                        <p class="commande-date"> 12 Février 2026</p>
                        <p class="commande-prix">15,90 €</p>
                        <span class="commande-statut livré">Livré</span>
                    </div>
                    <a href="Menus.html"> <button class="btn-recommander"> Recommander</button> </a>
                </article>

                <!-- Commande 2 -->
                <article class="commande-card">
                    <div class="commande-image">
                        <img src="../Folder CSS/special.png" alt="Menu Spécial">
                    </div>
                    <div class="commande-info">
                        <h3 class="commande-nom">Menu Spécial</h3>
                        <p class="commande-date"> 8 Février 2026</p>
                        <p class="commande-prix">17,50 €</p>
                        <span class="commande-statut livré">Livré</span>
                    </div>
                    <a href="Menus.html"> <button class="btn-recommander"> Recommander</button> </a>
                </article>

                <!-- Commande 3 -->
                <article class="commande-card">
                    <div class="commande-image">
                        <img src="../Folder CSS/steak.png" alt="Menu Steak">
                    </div>
                    <div class="commande-info">
                        <h3 class="commande-nom">Menu Steak</h3>
                        <p class="commande-date"> 5 Février 2026</p>
                        <p class="commande-prix">16,90 €</p>
                        <span class="commande-statut livré">Livré</span>
                    </div>
                    <a href="Menus.html"> <button class="btn-recommander"> Recommander</button> </a>
                </article>
            </div>
        </section>

        <!-- Section Actions Rapides -->
        <section class="actions-section">
            <h2 class="section-title">⚡ Actions rapides</h2>

            <div class="actions-grid">
                <a href="Menus.html" class="action-card">
                    <span class="action-icon">🍔</span>
                    <span class="action-text">Commander</span>
                </a>

                <a href="Avis.html" class="action-card">
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