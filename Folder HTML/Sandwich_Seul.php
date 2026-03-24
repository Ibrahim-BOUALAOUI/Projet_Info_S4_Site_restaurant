<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LES SANDWICHS SIGNATURES 🥙🍟</title>
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
                <h3 class="menu-title">LE CHICKEN ROUGE 🟥</h3>
                <p class="menu-price">9,99 €</p>
                <p class="menu-description">Pain maison, filet de poulet frais mariné au tandoori, crudités et sauce au
                    choix. Servi avec des frites.</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Chiken_Rouge_Seul.png" alt="Chicken Rouge solo" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE SPÉCIAL ⭐</h3>
                <p class="menu-price">9,99 € </p>
                <p class="menu-description">Pain maison, haut de cuisse de poulet frais mariné au paprika, poivrons,
                    crudités et sauce au choix. Servi avec des frites.
                </p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/special.png" alt="Spécial solo" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">L'ESCALOPE 🍗</h3>
                <p class="menu-price">10,40 €</p>
                <p class="menu-description">Pain maison, escalope de poulet frais, crudités et sauce au choix. Servi
                    avec des frites.
                </p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Escalope_Seul.png" alt="Escalope Solo" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE SUPRÊME 👑</h3>
                <p class="menu-price">11,50 €</p>
                <p class="menu-description">Pain maison, escalope de poulet frais, bacon de dinde, œuf, crudités et
                    sauce au choix. Servi avec des frites.</p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Supreme_seul.png" alt="Suprême solo" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE STEAK 🥩</h3>
                <p class="menu-price">9,80 €</p>
                <p class="menu-description">Pain maison, steak de bœuf, fromage, crudités et sauce au choix. Servi avec
                    des frites.
                </p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Steak_Seul.png" alt="steak solo" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>

        <div class="menu-item">
            <div class="menu-info">
                <h3 class="menu-title">LE TREMBLAY 🥩🍗</h3>
                <p class="menu-price">10,40 €</p>
                <p class="menu-description">Pain maison, 3 steaks de bœuf, escalope de poulet frais, bacon de dinde,
                    œuf, fromage, crudités et sauce au choix. Servi avec des frites.
                </p>
            </div>
            <div style="position: relative;">
                <img src="../Folder img/Le_Tremblay_Seul.png" alt="Tremblay" class="menu-image">
                <button class="add-button">+</button>
            </div>
        </div>
    </div>
</body>

</html>