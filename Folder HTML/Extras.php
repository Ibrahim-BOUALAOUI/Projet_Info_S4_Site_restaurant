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
    <title>Nos extras</title>
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
    <div class="rect-menus">
        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE CHEESE 🍔</h3>
                <p class="menu-price">4,00 €</p>
                <p class="menu-description">Pain bun's, steak de bœuf,fromage, crudité et sauce au choix. Servi seul
                    (sans frites et boisson).</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Cheese_burger.png" alt="Chesse Burger" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE DOUBLE CHEESE 🍔</h3>
                <p class="menu-price">7,00 € </p>
                <p class="menu-description">Pain bun's, 2 steaks de bœuf, fromage, crudités et sauce au choix. Servi
                    seul (sans frites et boisson).
                </p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Double_Cheese_Burger.png" alt="Double Cheese" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE COMPLET 🍔🥓🥚</h3>
                <p class="menu-price">8,00 €</p>
                <p class="menu-description">Pain bun's, 2 steaks de bœuf, bacon de dinde, œuf, fromage, crudités et
                    sauce au choix. Servi seul (sans frites et boisson).
                </p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Le_Complet.png" alt="Le complet" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE PANÉ BURGER 🍗</h3>
                <p class="menu-price">8,00 €</p>
                <p class="menu-description">Pain bap's, filet de poulet frais pané, fromage, crudités et sauce au choix.
                    Servi seul (sans frites et boisson).</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Pané_Burger.png" alt="Pané Burger" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE TOAST BEEF 🥪🥩</h3>
                <p class="menu-price">6,40 €</p>
                <p class="menu-description">Pain toasté, steak de bœuf, fromage, crudités et sauce au choix. Servi seul
                    (sans frites et boisson).
                </p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Toast_beef.png" alt="Toast beef" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE TOAST CHICKEN 🥪🍗</h3>
                <p class="menu-price">6,40 €</p>
                <p class="menu-description">Pain toasté, escalope de poulet frais, fromage, crudités et sauce au choix.
                    Servi seul (sans frites et boisson).</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Toast_beef.png" alt="Toast Chiken" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE CROQ MR 🥪🍗</h3>
                <p class="menu-price">5,40 €</p>
                <p class="menu-description">Pain toasté et gratiné, poulet fumé, fromage et crudités. Servi seul (sans
                    frites et boisson).</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Croq_Monsieur.png" alt="Croq Monsieur" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>
    </div>
</body>

</html>