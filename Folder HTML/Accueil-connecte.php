<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez nos sandwichs incontournables et nos menus">
    <title>Accueil - Nos Sandwichs</title>
    <link rel="stylesheet" href="../Folder CSS/Accueil-connecte.css">
</head>
<body>
    <main>
        
        <header>
            <a href="Accueil-connecte.html">
                <img src="../Folder CSS/129.png" alt="Logo du restaurant" width="200" height="auto">
            </a>
        </header>

        
        <aside>
            <a href="Profil.html" class="btn-profil" aria-label="Accéder à mon profil"> 
                <span class="profil-icon">👤</span>
                <span class="profil-text">Mon profil</span>
            </a>
        </aside>

        
        <section class="rect-left" aria-labelledby="incontournables-title">
            <h2 id="incontournables-title" class="Bungee">
                <u>Nos incontournables</u>
            </h2> 
            
            <article class="item-incontournable">
                <img src="../Folder CSS/chiken_tendori.png" 
                     alt="Sandwich Chicken Tandoori" 
                     class="img-resize"
                     loading="lazy">
                <span class="nom-sandwich">Chicken Tandoori</span>
            </article>

            <article class="item-incontournable">
                <img src="../Folder CSS/steak.png" 
                     alt="Sandwich Le Steak" 
                     class="img-resize"
                     loading="lazy">
                <span class="nom-sandwich">Le Steak</span>
            </article>

            <article class="item-incontournable">
                <img src="../Folder CSS/special.png" 
                     alt="Sandwich Le Spécial" 
                     class="img-resize"
                     loading="lazy">
                <span class="nom-sandwich">Le Spécial</span>
            </article>
        </section>

        
        <section class="rect-menus" aria-label="Nos catégories de produits">
            
            <article class="menu-item menu-item-special">
                <div class="menu-info">
                    <h3 class="menu-title">🛒 Tous nos produits</h3>
                    <a href="Produits.html" class="menu-link"> 
                        <img src="../Folder CSS/Chiken_tendori_logo.png" alt="Voir tous les produits">
                    </a>
                </div>
            </article>

           
            <article class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title">Nos menus</h3>
                    <a href="Menus.html" class="menu-link"> 
                        <img src="../Folder CSS/Chiken_tendori_logo.png" alt="Illustration menu complet">
                    </a>
                </div>
            </article>

            
            <article class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title">Les extras</h3>
                    <a href="Extras.html" class="menu-link"> 
                        <img src="../Folder CSS/menus_steak.png" alt="Illustration extras">
                    </a>
                </div>
            </article>

            
            <article class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title">Sandwich Seul</h3>
                    <a href="Sandwich_Seul.html" class="menu-link"> 
                        <img src="../Folder CSS/Supreme_seul.png" alt="Illustration sandwich seul">
                    </a>
                </div>
            </article>

            
            <article class="menu-item">
                <div class="menu-info">
                    <h3 class="menu-title">Nos boissons</h3>
                    <a href="Boissons.html" class="menu-link"> 
                        <img src="../Folder CSS/Canette.png" alt="Illustration boissons">
                    </a>
                </div>
            </article>
        </section>
    </main>
</body>
</html>
