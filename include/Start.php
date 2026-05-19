<?php
session_start();
$connecte = isset($_SESSION['email']);
$data = json_decode($json, true);
$plat = $data['plats'];

//Gestion du blocage de la personne
$json_users = file_get_contents("../Folder_Data/utilisateur.json");
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
