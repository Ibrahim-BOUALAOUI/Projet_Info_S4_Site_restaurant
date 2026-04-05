<?php
session_start();
$connecte = isset($_SESSION['email']);
$nb_articles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
$json = file_get_contents("../Folder_Data/Menus.json");
$data = json_decode($json, true);
$plat = $data['plats'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LES SANDWICHS SIGNATURES 🥙🍟</title>
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
                <?php foreach ($plat as $p): ?>
                    <?php if($p['type'] == 'sandwich') :?>
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
