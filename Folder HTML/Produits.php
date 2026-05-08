<?php
session_start();
$nb_articles = 0;

// On vérifie si le panier existe et on compte les articles
if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
    $nb_articles = count($_SESSION['panier']);
}

$connecte = isset($_SESSION['email']);
$json = file_get_contents("../Folder_Data/Menus.json");
$data = json_decode($json, true);
$plat = $data['plats'];


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="../Folder CSS/Produits.css">
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <script src = "../Folder_JS/Filtres.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous nos produits</title>
</head>

<body>

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
            <?php if ($connecte) { ?>
                <a href="Profil.php" class="btn-profil">
                    <span class="profil-icon">👤</span>
                    <span class="profil-text">Mon profil</span>
                </a>
            <?php } else { ?>
                <a href="connexion.php">
                    <button class="Btn">Connexion</button>
                </a>
            <?php } ?>
        </aside>
    </header>

    <div class="search-container">
        <input type="search" class="search-bar" placeholder="🔍 Rechercher un produit...">
    </div>

    <main class="produits-container">
        <aside class="filters-sidebar">
            <h2 class="filters-title">🎯 Filtres</h2>
            <form method="GET" action="Produits.php">

                <section class="filter-group">
                    <h3 class="filter-title">Type</h3>
                    <label class="filter-option"><input type="checkbox" name="type" id="Menus" > Menus</label>
                    <label class="filter-option"><input type="checkbox" name="type" id="sandwich" > Sandwichs</label>
                    <label class="filter-option"><input type="checkbox" name="type" id="extra" > Extras</label>
                    <label class="filter-option"><input type="checkbox" name="type" id="boisson" > Boissons</label>
                </section>
            </form>
        </aside>

        <section class="products-grid">
            <?php if (empty($plat)): ?>
                <p class="no-result">Aucun produit trouvé.</p>
            <?php else: ?>
                <?php foreach ($plat as $p): ?>
                    <article class="product-card">
                        <div class="product-image-container" style="position: relative;">
                            <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>" class="product-image">
                            <a href="ajouter_panier.php?nom=<?= urlencode($p['nom']) ?>&prix=<?= $p['prix'] ?>" class="add-button-link">+</a>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?= htmlspecialchars($p['nom']) ?></h3>
                            <p class="product-description"><?= htmlspecialchars($p['description']) ?></p>
                            <p class="product-price"><?= number_format($p['prix'], 2, ',', ' ') ?> €</p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>