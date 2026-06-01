<?php
// Start.php démarre la session, charge les produits du menu et vérifie si l'utilisateur est connecté.
require("../include/Start.php");

// On compte les articles déjà présents dans le panier pour afficher la notification en haut de page.
if (isset($_SESSION['panier'])) {
    $nb_articles = count($_SESSION['panier']);
} else {
    $nb_articles = 0;
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" id="style-sombre" href="">
<script src="../Folder_JS/affichage.js" defer></script>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LES SANDWICHS SIGNATURES 🥙🍟</title>
</head>

<body>

    <?php // La notification panier apparaît seulement si le client a déjà ajouté au moins un article. ?>
    <?php if ($nb_articles > 0): ?>
        <div class="panier-notif">
            🛒 Panier : <?php echo $nb_articles; ?> article(s)
            <a href="panier.php">VOIR MA COMMANDE</a>
        </div>
    <?php endif; ?>

    <header class="top_bar">
        <a href="index.php">
            <img src="../Folder img/129.png" alt="Logo" width="200">
        </a>
        <aside>
            <?php // Si le client est connecté, on affiche son profil ; sinon, on propose la connexion. ?>
            <?php if ($connecte) : ?>
                <a href="Profil.php" class="btn-profil">
                    <span class="profil-icon">👤</span>
                    <span class="profil-text">Mon profil</span>
                </a>
            <?php else : ?>
                <a href="connexion.php">
                    <button class="Btn">Connexion</button>
                </a>
            <?php endif; ?>
        </aside>
    </header>

    <main>
        <div class="rect-menus">
            <?php if (empty($plat)): ?>
                <p class="no-result">Aucun produit trouvé.</p>
            <?php else: ?>
                <?php // $plat contient tout le catalogue ; cette page n'affiche que les produits de type boisson. ?>
                <?php foreach ($plat as $p): ?>
                    <?php if($p['type'] == 'boisson') :?>
                    <div class="menu-item">
                        <div class="menu-info">
                            <h3 class="menu-title"><?= htmlspecialchars($p['nom']) ?></h3>
                            <p class="menu-description"><?= htmlspecialchars($p['description']) ?></p>
                            <p class="menu-price"><?= number_format($p['prix'], 2, ',', ' ') ?> €</p>
                        </div>
                        <div style="position: relative; flex-shrink: 0;">
                            <img src="<?= htmlspecialchars($p['image']) ?>"
                                 alt="<?= htmlspecialchars($p['nom']) ?>"
                                 class="menu-image">
                            <?php // Le bouton + transmet le nom et le prix à ajouter_panier.php pour ajouter la boisson au panier. ?>
                            <a href="ajouter_panier.php?nom=<?= urlencode($p['nom']) ?>&prix=<?= $p['prix'] ?>"
                               class="add-button-link"
                               title="Ajouter au panier">+</a>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
