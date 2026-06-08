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
<script>
// On attend que la page soit bien chargée
document.addEventListener("DOMContentLoaded", () => {
    
    // Fonction qui va interroger le serveur en arrière-plan
    async function verifierBlocageCompte() {
        try {
            // On appelle notre mini-script PHP
            const response = await fetch("../include/verif_bloque.php");
            const data = await response.json();

            // Si le serveur dit que le compte est bloqué
            if (data.bloque === true) {
                // On redirige instantanément l'utilisateur vers la page d'erreur
                window.location.href = "connexion.php?erreur=bloque";
            }
        } catch (erreur) {
            console.error("Impossible de vérifier le statut du compte:", erreur);
        }
    }

    // On lance la vérification immédiatement au chargement de la page
    verifierBlocageCompte();

    // PUIS, on répète cette vérification toutes les 3000 millisecondes (3 secondes)
    setInterval(verifierBlocageCompte, 3000);
});
</script>