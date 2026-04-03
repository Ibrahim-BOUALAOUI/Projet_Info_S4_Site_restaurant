<?php
session_start();
$connecte = isset($_SESSION['email']);


$nb_articles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boissons - Commande</title>
    <style>
        
        .panier-notif {
            background: #28a745;
            color: white;
            padding: 10px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            font-family: Arial, sans-serif;
        }

        .panier-notif a {
            color: yellow;
            font-weight: bold;
            text-decoration: none;
            margin-left: 10px;
        }

       
        .add-button-link {
            text-decoration: none;
            background-color: #ff0000;
            
            color: white;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <?php if ($nb_articles > 0): ?>
        <div class="panier-notif">
            🛒 Panier : <?php echo $nb_articles; ?> article(s)
            <a href="panier.php">👉 VOIR MA COMMANDE</a>
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
                <a href="connection.php">
                    <button class="Btn">Connexion</button>
                </a>
            <?php } ?>
        </aside>
    </header>

    <div class="boissons-container">

   
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Coca.png" alt="Coca" class="boisson-image">
                <a href="ajouter_panier.php?nom=Coca-Cola&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>COCA-COLA</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

      
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Coca Cherry.png" alt="Coca Cherry" class="boisson-image">
                <a href="ajouter_panier.php?nom=Coca%20Cherry&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>COCA CHERRY</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

      
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Coca Zéro.png" alt="Coca Zéro" class="boisson-image">
                <a href="ajouter_panier.php?nom=Coca%20Zero&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>COCA ZERO</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

       
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Ice Tea.png" alt="Ice Tea" class="boisson-image">
                <a href="ajouter_panier.php?nom=Ice%20Tea&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>ICE TEA</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Perrier.png" alt="Perrier" class="boisson-image">
                <a href="ajouter_panier.php?nom=Perrier%20Citron&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>PERRIER CITRON VERT</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

      
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Oasis Tropical.jpg" alt="Oasis Tropical" class="boisson-image">
                <a href="ajouter_panier.php?nom=Oasis%20Tropical&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>OASIS TROPICAL</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Oasis Pomme Cassis.png" alt="Oasis Pomme Cassis" class="boisson-image">
                <a href="ajouter_panier.php?nom=Oasis%20Pomme%20Cassis&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>OASIS POMME CASSIS</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/eau.jpg" alt="Eau" class="boisson-image">
                <a href="ajouter_panier.php?nom=Eau&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>EAU</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Cristalline Fraise.png" alt="Cristalline Fraise" class="boisson-image">
                <a href="ajouter_panier.php?nom=Cristalline%20Fraise&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>CRISTALLINE FRAISE</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

       
        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Fanta Orange.png" alt="Fanta Orange" class="boisson-image">
                <a href="ajouter_panier.php?nom=Fanta%20Orange&prix=1.50" class="add-button-link">+</a>
            </div>
            <div class="boisson-info">
                <h3>FANTA ORANGE</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

    </div>
</body>

</html>
