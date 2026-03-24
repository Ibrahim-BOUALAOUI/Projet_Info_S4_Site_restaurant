<?php 

session_start();
$connecte = isset($_SESSION['email']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menus</title>
</head>

<body>
    <header class="top_bar">
        <a href="index.php"> <img src="../Folder img/129.png" alt="Logo" width="200"> </a>
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

    <div class="boissons-container">

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Coca.png" alt="Coca" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>COCA</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Coca Cherry.png" alt="Coca Cherry" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>COCA CHERRY</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Coca Zéro.png" alt="Coca Zéro" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>COCA ZERO</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Ice Tea.png" alt="Ice Tea" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>ICE TEA</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Perrier.png" alt="Perrier" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>PERRIER CITRON VERT</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Oasis Tropical.jpg" alt="Oasis Tropical" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>OASIS TROPICAL</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Oasis Pomme Cassis.png" alt="Oasis Pomme Cassis" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>OASIS POMME CASSIS</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/eau.jpg" alt="Eau" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>EAU</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Cristalline Fraise.png" alt="Cristalline Fraise" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>CRISTALLINE FRAISE</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

        <div class="boisson-card">
            <div style="position: relative;">
                <img src="../Folder img/Fanta Orange.png" alt="Fanta Orange" class="boisson-image">
                <button class="add-button">+</button>
            </div>
            <div class="boisson-info">
                <h3>FANTA ORANGE</h3>
                <p class="menu-price">1,50 €</p>
            </div>
        </div>

    </div>
</body>

</html>