<?php
session_start();
$connecte = isset($_SESSION['email']);

$json = file_get_contents("../Folder_Data/Menus.json");
$data = json_decode($json, true);
$plat = $data['plats'];

$filtreType   = isset($_GET["type"])   ? $_GET["type"]   : null;
$filtreSaveur = isset($_GET["saveur"]) ? $_GET["saveur"] : null;
$filtreRegime = isset($_GET["regime"]) ? $_GET["regime"] : null;
$filtrePrix   = isset($_GET["prix"])   ? $_GET["prix"]   : null;

$plat = array_filter($plat, function($plat) use ($filtreType, $filtreSaveur, $filtreRegime, $filtrePrix) {
    if ($filtreType && $plat["type"] !== $filtreType) return false;
    if ($filtreSaveur && strtolower($plat["saveur"]) !== strtolower($filtreSaveur)) return false;
    if ($filtreRegime && !in_array($filtreRegime, $plat["regime"])) return false;
    if ($filtrePrix === "petit"  && $plat["prix"] >= 5)  return false;
    if ($filtrePrix === "moyen"  && ($plat["prix"] < 5  || $plat["prix"] > 10)) return false;
    if ($filtrePrix === "grand"  && $plat["prix"] <= 10) return false;
    return true;
});



?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../Folder CSS/Produits.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous nos produits</title>
</head>
<body>

    <header class="top_bar">
        <a href="index.php">
            <img src="../Folder img/129.png" alt="Logo" width="200">
        </a>
        <aside>
                    <?php if ($connecte){ ?>
                        <a href="Profil.php" class="btn-profil" aria-label="Accéder à mon profil"> 
                            <span class="profil-icon">👤</span>
                            <span class="profil-text">Mon profil</span>
                        </a>

                    <?php } else{ ?>

                        <a href="connection.php" aria-label="Se connecter">
                            <button class="Btn" aria-label="Connexion">Connexion</button>
                        </a>

                    <?php } ?>
                    </aside>
    </header>

    <div class="search-container">
        <input type="search" class="search-bar"
            placeholder="🔍 Rechercher un plat, une boisson..."
            aria-label="Rechercher un produit">
    </div>

    <main class="produits-container">

        <!-- ASIDE : contient le form ET toutes les sections -->
        <aside class="filters-sidebar">
            <h2 class="filters-title">🎯 Filtres</h2>

            <form method="GET" action="Produits.php">
                <div class="filter-buttons">
        <button type="submit" class="btn-filtrer">Filtrer</button>
        <a href="Produits.php" class="btn-reinitialiser"></a>
    </div>

                <section class="filter-group">
                    <h3 class="filter-title">Type de plat</h3>
                    <label class="filter-option">
                        <input type="checkbox" name="type" value="Menus"
                            <?= isset($_GET["type"]) && $_GET["type"] === "Menus" ? "checked" : "" ?>>
                        <span>Menus Complet</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="type" value="sandwich"
                            <?= isset($_GET["type"]) && $_GET["type"] === "sandwich" ? "checked" : "" ?>>
                        <span>Sandwichs</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="type" value="extra"
                            <?= isset($_GET["type"]) && $_GET["type"] === "extra" ? "checked" : "" ?>>
                        <span>Extras</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="type" value="boisson"
                            <?= isset($_GET["type"]) && $_GET["type"] === "boisson" ? "checked" : "" ?>>
                        <span>Boissons</span>
                    </label>
                </section>

                <section class="filter-group">
                    <h3 class="filter-title">Saveurs</h3>
                    <label class="filter-option">
                        <input type="checkbox" name="saveur" value="épicé"
                            <?= isset($_GET["saveur"]) && $_GET["saveur"] === "épicé" ? "checked" : "" ?>>
                        <span>Épicé</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="saveur" value="doux"
                            <?= isset($_GET["saveur"]) && $_GET["saveur"] === "doux" ? "checked" : "" ?>>
                        <span>Doux</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="saveur" value="salé"
                            <?= isset($_GET["saveur"]) && $_GET["saveur"] === "salé" ? "checked" : "" ?>>
                        <span>Salé</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="saveur" value="sucré"
                            <?= isset($_GET["saveur"]) && $_GET["saveur"] === "sucré" ? "checked" : "" ?>>
                        <span>Sucré</span>
                    </label>
                </section>

                <section class="filter-group">
                    <h3 class="filter-title">Prix</h3>
                    <label class="filter-option">
                        <input type="checkbox" name="prix" value="petit"
                            <?= isset($_GET["prix"]) && $_GET["prix"] === "petit" ? "checked" : "" ?>>
                        <span>€ (moins de 5€)</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="prix" value="moyen"
                            <?= isset($_GET["prix"]) && $_GET["prix"] === "moyen" ? "checked" : "" ?>>
                        <span>€€ (5€ - 10€)</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="prix" value="grand"
                            <?= isset($_GET["prix"]) && $_GET["prix"] === "grand" ? "checked" : "" ?>>
                        <span>€€€ (plus de 10€)</span>
                    </label>
                </section>

                <section class="filter-group">
                    <h3 class="filter-title">Régimes spéciaux</h3>
                    <label class="filter-option">
                        <input type="checkbox" name="regime" value="sans-gluten"
                            <?= isset($_GET["regime"]) && $_GET["regime"] === "sans-gluten" ? "checked" : "" ?>>
                        <span>🌾 Sans gluten</span>
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="regime" value="sans-lactose"
                            <?= isset($_GET["regime"]) && $_GET["regime"] === "sans-lactose" ? "checked" : "" ?>>
                        <span>🥛 Sans lactose</span>
                    </label>
                </section>

            </form> 

        </aside> 

        
        <section class="products-grid">
            <?php if (empty($plat)): ?>
                <p class="no-result">Aucun produit ne correspond à vos critères.</p>
            <?php else: ?>
                <?php foreach ($plat as $p): ?>
                    <article class="product-card"
                            data-type="<?= htmlspecialchars($p['type']) ?>"
                            data-saveur="<?= htmlspecialchars($p['saveur']) ?>"
                            data-prix="<?= $p['prix'] <= 5 ? 'petit' : ($p['prix'] <= 10 ? 'moyen' : 'grand') ?>">
                        <div class="product-image-container">
                            <img src="<?= htmlspecialchars($p['image']) ?>"
                                 alt="<?= htmlspecialchars($p['nom']) ?>"
                                 class="product-image">
                            <button class="add-button">+</button>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?= htmlspecialchars($p['nom']) ?></h3>
                            <p class="product-description"><?= htmlspecialchars($p['description']) ?></p>
                            <div class="product-tags">
                                <span class="tag <?= htmlspecialchars($p['type']) ?>">
                                    <?= htmlspecialchars(ucfirst($p['type'])) ?>
                                </span>
                                <?php if (strtolower($p['saveur']) === 'épicé'): ?>
                                    <span class="tag épicé">🌶️ Épicé</span>
                                <?php endif; ?>
                            </div>
                            <p class="product-price"><?= number_format($p['prix'], 2, ',', ' ') ?> €</p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

    </main>
</body>
</html>