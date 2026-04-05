<?php
session_start();
$connecte = isset($_SESSION['email']);

$nb_articles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;

$json = file_get_contents("../Folder_Data/Menus.json");
$data = json_decode($json, true);
$plat = $data['plats'];

$filtreType   = isset($_GET["type"])   ? $_GET["type"]   : null;
$filtreSaveur = isset($_GET["saveur"]) ? $_GET["saveur"] : null;
$filtreRegime = isset($_GET["regime"]) ? $_GET["regime"] : null;
$filtrePrix   = isset($_GET["prix"])   ? $_GET["prix"]   : null;

$plat = array_filter($plat, function ($p) use ($filtreType, $filtreSaveur, $filtreRegime, $filtrePrix) {
    if ($filtreType && $p["type"] !== $filtreType) return false;
    if ($filtreSaveur && strtolower($p["saveur"]) !== strtolower($filtreSaveur)) return false;
    if ($filtreRegime && !in_array($filtreRegime, $p["regime"])) return false;
    if ($filtrePrix === "petit"  && $p["prix"] >= 5)  return false;
    if ($filtrePrix === "moyen"  && ($p["prix"] < 5  || $p["prix"] > 10)) return false;
    if ($filtrePrix === "grand"  && $p["prix"] <= 10) return false;
    return true;
});
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="../Folder CSS/Produits.css">
    <link rel="stylesheet" href="../Folder CSS/Commander_Article.css">
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
                <div class="filter-buttons">
                    <button type="submit" class="btn-filtrer">Filtrer</button>
                    <a href="Produits.php" class="btn-reinitialiser">Reset</a>
                </div>

                <section class="filter-group">
                    <h3 class="filter-title">Type</h3>
                    <label class="filter-option"><input type="checkbox" name="type" value="Menus" <?= $filtreType === "Menus" ? "checked" : "" ?>> Menus</label>
                    <label class="filter-option"><input type="checkbox" name="type" value="sandwich" <?= $filtreType === "sandwich" ? "checked" : "" ?>> Sandwichs</label>
                    <label class="filter-option"><input type="checkbox" name="type" value="extra" <?= $filtreType === "extra" ? "checked" : "" ?>> Extras</label>
                    <label class="filter-option"><input type="checkbox" name="type" value="boisson" <?= $filtreType === "boisson" ? "checked" : "" ?>> Boissons</label>
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