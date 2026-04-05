<?php
session_start();
$connecte = isset($_SESSION['email']);
$nb_articles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <link rel="stylesheet" href="../Folder CSS/Commander_Article.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menus</title>
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

    <div class="rect-menus">

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">MENU LE CHICKEN ROUGE 🟥</h3>
                <p class="menu-price">13,50 €</p>
                <p class="menu-description">Pain maison, filet de poulet frais mariné au tandoori, crudités et sauce au choix. Servi avec frites et boisson au choix.</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/chiken_tendori.png" alt="Chicken Rouge" class="menu-image">
                <a href="ajouter_panier.php?nom=Menu%20Le%20Chicken%20Rouge&prix=13.50" class="add-button-link">+</a>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">MENU LE SPÉCIAL ⭐</h3>
                <p class="menu-price">13,50 € </p>
                <p class="menu-description">Pain maison, haut de cuisse de poulet frais mariné au paprika, poivrons, crudités et sauce au choix. Servi avec frites et boisson au choix.</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/special.png" alt="Spécial" class="menu-image">
                <a href="ajouter_panier.php?nom=Menu%20Le%20Spécial&prix=13.50" class="add-button-link">+</a>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">MENU L'ESCALOPE 🍗</h3>
                <p class="menu-price">13,50 €</p>
                <p class="menu-description">Pain maison, escalope de poulet frais, crudités et sauce au choix. Servi avec frites et boisson au choix.</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/escalope.png" alt="Escalope" class="menu-image">
                <a href="ajouter_panier.php?nom=Menu%20L%27Escalope&prix=13.50" class="add-button-link">+</a>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">MENU LE SUPRÊME 👑</h3>
                <p class="menu-price">13,70 €</p>
                <p class="menu-description">Pain maison, escalope de poulet frais, bacon de dinde, œuf, crudités et sauce au choix. Servi avec frites et boisson au choix.</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/supreme.png" alt="Suprême" class="menu-image">
                <a href="ajouter_panier.php?nom=Menu%20L%27Escalope&prix=13.50" class="add-button-link">+</a>
            </div>
        </div>
    </div>
</body>

</html>