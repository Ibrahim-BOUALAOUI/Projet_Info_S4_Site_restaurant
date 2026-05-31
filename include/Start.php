<?php
session_start();
$connecte = isset($_SESSION['email']);
$json = file_get_contents("../Folder_Data/Menus.json");
$data = json_decode($json, true);
$plat = $data['plats'];


//Gestion du blocage de la personne
$json_users = file_get_contents("../Folder_Data/dfsqfiqsoifsvquvfipqf.json");
$users = json_decode($json_users, true);

if (isset($_SESSION['email'])) {
    foreach ($users as $user) {
        if ($user['email'] === $_SESSION['email'] && !empty($user['bloque'])) {
            session_destroy();
            header("Location: connexion.php?erreur=bloque");
            exit;
        }
    }
}
?>

<?php  if (isset($accesRefuse)){ ?>

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
