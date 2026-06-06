<?php
// Start.php regroupe les éléments communs à plusieurs pages.
session_start();
// $connecte permet de savoir si l'utilisateur est connecté pour adapter l'affichage.
$connecte = isset($_SESSION['email']);
// Chargement du catalogue de produits depuis le JSON.
$json = file_get_contents("../Folder_Data/Menus.json");
$data = json_decode($json, true);
$plat = $data['plats'];


// Gestion du blocage de la personne connectée.
$json_users = file_get_contents("../Folder_Data/ofdbisqfsqf.json");
$users = json_decode($json_users, true);

if (isset($_SESSION['email'])) {
    foreach ($users as $user) {
        // On accepte les deux noms de champ car certains anciens comptes utilisent bloque et d'autres bloquee.
        if ($user['email'] === $_SESSION['email'] && (!empty($user['bloque']) || !empty($user['bloquee']))) {
            session_destroy();
            header("Location: connexion.php?erreur=bloque");
            exit;
        }
    }
}

// On compte les articles déjà présents dans le panier pour afficher la notification en haut de page.
if (isset($_SESSION['panier'])) {
    $nb_articles = count($_SESSION['panier']);
} else {
    $nb_articles = 0;
}

?>

<?php  if (isset($accesRefuse)){ ?>

    <?php // Bloc visuel réutilisable quand une page veut afficher un accès refusé. ?>
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
<?php }?>
