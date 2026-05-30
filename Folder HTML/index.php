<?php
require("../include/Start.php");
$role = $_SESSION['permission'] ?? null;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Nos Sandwichs</title>
    <link rel="stylesheet" href="../Folder CSS/Accueil.css">
    <link rel="stylesheet" id="style-sombre" href="">
    <script src="../Folder_JS/affichage.js" defer></script>
</head>

<body>
    <main>
        <header class="top_bar">
    <div class="header-left">
        <img src="../Folder img/129.png" alt="Logo du restaurant" width="200" height="auto">
        <a href="panier.php" class="btn-panier" aria-label="Accéder à mon panier">
            <span class="panier-icon">🛒</span>
            <span class="panier-text">Mon panier</span>
        </a>
    </div>

    <div class="header-right">
        <?php if ($connecte) { ?>
            <a href="Profil.php" class="btn-profil" aria-label="Accéder à mon profil">
                <span class="profil-icon">👤</span>
                <span class="profil-text">Mon profil</span>
            </a>
            <?php if ($role === 'admin'): ?>
                <a href="Admin.php" class="btn-role btn-admin">
                    <span class="role-icon">⚙️</span>
                    <span class="role-text">Administration</span>
                </a>
            <?php elseif ($role === 'preparateur'): ?>
                <a href="commandes.php" class="btn-role btn-preparateur">
                    <span class="role-icon">👨‍🍳</span>
                    <span class="role-text">Commandes</span>
                </a>
            <?php elseif ($role === 'livreur'): ?>
                <a href="livreur.php" class="btn-role btn-livreur">
                    <span class="role-icon">🛵</span>
                    <span class="role-text">Livraisons</span>
                </a>
            <?php endif; ?>
        <?php } else { ?>
            <a href="connexion.php" aria-label="Se connecter">
                <button class="Btn" aria-label="Connexion">Connexion</button>
            </a>
        <?php } ?>
        <button id="bouton-theme" class="btn-theme">☀️ Mode Clair</button>
    </div>
</header>

        <section class="rect-left" aria-labelledby="incontournables-title">
            <h2 id="incontournables-title" class="Bungee"><u>Nos incontournables</u></h2>
            <a href="Menus.php">
                <article class="item-incontournable">
                    <img src="../Folder img/chiken_tendori.png" alt="Sandwich Chicken Tandoori" class="img-resize" loading="lazy">
                    <span class="nom-sandwich">Chicken Tandoori</span>
                </article>
            </a>
            <a href="Menus.php">
                <article class="item-incontournable">
                    <img src="../Folder img/steak.png" alt="Sandwich Le Steak" class="img-resize" loading="lazy">
                    <span class="nom-sandwich">Le Steak</span>
                </article>
            </a>
            <a href="Menus.php">
                <article class="item-incontournable">
                    <img src="../Folder img/special.png" alt="Sandwich Le Spécial" class="img-resize" loading="lazy">
                    <span class="nom-sandwich">Le Spécial</span>
                </article>
            </a>
        </section>

        
        <section class="rect-menus" aria-label="Nos catégories de produits">
            <article class="menu-item menu-item-special">
                <div class="menu-info">
                    <h3 class="menu-title">🛒 Tous nos produits</h3>
                    <a href="Produits.php" class="menu-link">
                        <img src="../Folder img/Chiken_tendori_logo.png" alt="Voir tous les produits">
                    </a>
                </div>
            </article>
            
            <article class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title">Nos menus</h3>
                    <a href="Menus.php"><img src="../Folder img/Chiken_tendori_logo.png" alt="Menus" loading="lazy"></a>
                </div>
            </article>
            <article class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title">Les extras</h3>
                    <a href="Extras.php"><img src="../Folder img/menus_steak.png" alt="Extras" loading="lazy"></a>
                </div>
            </article>
            <article class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title">Sandwich Seul</h3>
                    <a href="Sandwich_Seul.php"><img src="../Folder img/Supreme_seul.png" alt="Sandwich Seul" loading="lazy"></a>
                </div>
            </article>
            <article class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title">Nos boissons</h3>
                    <a href="Boissons.php"><img src="../Folder img/Canette.png" alt="Boissons" loading="lazy"></a>
                </div>
            </article>
        </section>
    </main>
</body>
</html>