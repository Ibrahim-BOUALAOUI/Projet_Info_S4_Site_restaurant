<?php  if (isset($accesRefuse)): ?>

    <div class="acces-refuse-overlay">
        <div class="acces-refuse-card">
            <span class="acces-refuse-icon">🔒</span>
            <h1 class="acces-refuse-titre">Accès refusé</h1>
            <p class="acces-refuse-message">
                Vous n'avez pas les droits nécessaires pour accéder à cette page.
            </p>
            <a href="index.php" class="acces-refuse-btn">Retour à l'accueil</a>
        </div>
    </div>
<?php endif : ?>