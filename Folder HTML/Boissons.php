<?php
session_start();
$connecte = isset($_SESSION['email']);
$nb_articles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;


$boissons = [
    ["nom" => "Coca-Cola", "prix" => 1.50, "img" => "Coca.png"],
    ["nom" => "Coca Cherry", "prix" => 1.50, "img" => "Coca Cherry.png"],
    ["nom" => "Coca Zero", "prix" => 1.50, "img" => "Coca Zéro.png"],
    ["nom" => "Ice Tea", "prix" => 1.50, "img" => "Ice Tea.png"],
    ["nom" => "Perrier Citron Vert", "prix" => 1.50, "img" => "Perrier.png"],
    ["nom" => "Oasis Tropical", "prix" => 1.50, "img" => "Oasis Tropical.jpg"],
    ["nom" => "Oasis Pomme Cassis", "prix" => 1.50, "img" => "Oasis Pomme Cassis.png"],
    ["nom" => "Eau", "prix" => 1.00, "img" => "eau.jpg"],
    ["nom" => "Cristalline Fraise", "prix" => 1.50, "img" => "Cristalline Fraise.png"],
    ["nom" => "Fanta Orange", "prix" => 1.50, "img" => "Fanta Orange.png"],
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <link rel="stylesheet" href="../Folder CSS/Commander_Article.css">
    <meta charset="UTF-8">
    <title>Nos Boissons</title>
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
                    <span class="profil-icon">👤</span> Mon profil
                </a>
            <?php else : ?>
                <a href="connexion.php"><button class="Btn">Connexion</button></a>
            <?php endif; ?>
        </aside>
    </header>

    <div class="boissons-container">

        
        <?php foreach ($boissons as $boisson) : ?>
            <div class="boisson-card">
                <div style="position: relative;">
                    <img src="../Folder img/<?php echo $boisson['img']; ?>" alt="<?php echo $boisson['nom']; ?>" class="boisson-image">
                    
                    
                    <a href="ajouter_panier.php?nom=<?php echo urlencode($boisson['nom']); ?>&prix=<?php echo $boisson['prix']; ?>" class="add-button-link">+</a>
                </div>
                <div class="boisson-info">
                    <h3><?php echo strtoupper($boisson['nom']); ?></h3>
                    <p class="menu-price"><?php echo number_format($boisson['prix'], 2, ',', ' '); ?> €</p>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</body>
</html>