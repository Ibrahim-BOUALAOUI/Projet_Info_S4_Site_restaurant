<?php
session_start();
$connecte = isset($_SESSION['email']);
$nb_articles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;

// 1. Définition des données (La "base de données" locale)
$extras = [
    [
        "nom" => "Le Cheese 🍔",
        "prix" => 4.00,
        "img" => "Cheese_burger.png",
        "desc" => "Pain bun's, steak de bœuf, fromage, crudité et sauce au choix. Servi seul (sans frites et boisson)."
    ],
    [
        "nom" => "Le Double Cheese 🍔",
        "prix" => 7.00,
        "img" => "Double_Cheese_Burger.png",
        "desc" => "Pain bun's, 2 steaks de bœuf, fromage, crudités et sauce au choix. Servi seul (sans frites et boisson)."
    ],
    [
        "nom" => "Le Complet 🍔🥓🥚",
        "prix" => 8.00,
        "img" => "Le_Complet.png",
        "desc" => "Pain bun's, 2 steaks de bœuf, bacon de dinde, œuf, fromage, crudités et sauce au choix. Servi seul (sans frites et boisson)."
    ],
    [
        "nom" => "Le Pané Burger 🍗",
        "prix" => 8.00,
        "img" => "Pané_Burger.png",
        "desc" => "Pain bap's, filet de poulet frais pané, fromage, crudités et sauce au choix. Servi seul (sans frites et boisson)."
    ],
    [
        "nom" => "Le Toast Beef 🥪🥩",
        "prix" => 6.40,
        "img" => "Toast_beef.png",
        "desc" => "Pain toasté, steak de bœuf, fromage, crudités et sauce au choix. Servi seul (sans frites et boisson)."
    ],
    [
        "nom" => "Le Toast Chicken 🥪🍗",
        "prix" => 6.40,
        "img" => "Toast_beef.png", // Attention, tu utilisais la même image que Beef
        "desc" => "Pain toasté, escalope de poulet frais, fromage, crudités et sauce au choix. Servi seul (sans frites et boisson)."
    ],
    [
        "nom" => "Le Croq Mr 🥪🍗",
        "prix" => 5.40,
        "img" => "Croq_Monsieur.png",
        "desc" => "Pain toasté et gratiné, poulet fumé, fromage et crudités. Servi seul (sans frites et boisson)."
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <link rel="stylesheet" href="../Folder CSS/Commander_Article.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Extras - Burgers</title>
</head>

<body>
    <?php if ($nb_articles > 0): ?>
        <div class="panier-notif">
            🛒 Panier : <?php echo $nb_articles; ?> article(s)
            <a href="panier.php">VOIR MA COMMANDE</a>
        </div>
    <?php endif; ?>

    <header class="top_bar">
        <a href="index.php"> <img src="../Folder img/129.png" alt="Logo" width="200"> </a>
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

    <div class="rect-menus">
        <!-- 2. Boucle Foreach pour générer les items -->
        <?php foreach ($extras as $item) : ?>
            <div class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title"><?php echo strtoupper($item['nom']); ?></h3>
                    <p class="menu-price"><?php echo number_format($item['prix'], 2, ',', ' '); ?> €</p>
                    <p class="menu-description"><?php echo $item['desc']; ?></p>
                </div>
                <div style="position: relative;">
                    <img src="../Folder img/<?php echo $item['img']; ?>" alt="<?php echo $item['nom']; ?>" class="menu-image">
                    
                    <!-- Lien vers le panier avec encodage URL pour les caractères spéciaux -->
                    <a href="ajouter_panier.php?nom=<?php echo urlencode($item['nom']); ?>&prix=<?php echo $item['prix']; ?>" class="add-button-link">+</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>