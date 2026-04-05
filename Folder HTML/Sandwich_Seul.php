<?php
session_start();
$connecte = isset($_SESSION['email']);
$nb_articles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;

// 1. Définition des Sandwichs (Ta "base de données" locale)
$sandwichs = [
    [
        "nom" => "Le Chicken Rouge 🟥",
        "prix" => 9.99,
        "img" => "Chiken_Rouge_Seul.png",
        "desc" => "Pain maison, filet de poulet frais mariné au tandoori, crudités et sauce au choix. Servi avec des frites."
    ],
    [
        "nom" => "Le Spécial ⭐",
        "prix" => 9.99,
        "img" => "special.png",
        "desc" => "Pain maison, haut de cuisse de poulet frais mariné au paprika, poivrons, crudités et sauce au choix. Servi avec des frites."
    ],
    [
        "nom" => "L'Escalope 🍗",
        "prix" => 10.40,
        "img" => "Escalope_Seul.png",
        "desc" => "Pain maison, escalope de poulet frais, crudités et sauce au choix. Servi avec des frites."
    ],
    [
        "nom" => "Le Suprême 👑",
        "prix" => 11.50,
        "img" => "Supreme_seul.png",
        "desc" => "Pain maison, escalope de poulet frais, bacon de dinde, œuf, crudités et sauce au choix. Servi avec des frites."
    ],
    [
        "nom" => "Le Steak 🥩",
        "prix" => 9.80,
        "img" => "Steak_Seul.png",
        "desc" => "Pain maison, steak de bœuf, fromage, crudités et sauce au choix. Servi avec des frites."
    ],
    [
        "nom" => "Le Tremblay 🥩🍗",
        "prix" => 10.40,
        "img" => "Le_Tremblay_Seul.png",
        "desc" => "Pain maison, 3 steaks de bœuf, escalope de poulet frais, bacon de dinde, œuf, fromage, crudités et sauce au choix. Servi avec des frites."
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
        <!-- 2. Boucle PHP dynamique -->
        <?php foreach ($sandwichs as $sandwich) : ?>
            <div class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title"><?php echo strtoupper($sandwich['nom']); ?></h3>
                    <p class="menu-price"><?php echo number_format($sandwich['prix'], 2, ',', ' '); ?> €</p>
                    <p class="menu-description"><?php echo $sandwich['desc']; ?></p>
                </div>
                <div style="position: relative;">
                    <img src="../Folder img/<?php echo $sandwich['img']; ?>" alt="<?php echo $sandwich['nom']; ?>" class="menu-image">
                    
                    <!-- Lien avec encodage des caractères spéciaux pour le panier -->
                    <a href="ajouter_panier.php?nom=<?= urlencode($sandwich['nom']) ?>&prix=<?= $sandwich['prix'] ?>" class="add-button-link">+</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>