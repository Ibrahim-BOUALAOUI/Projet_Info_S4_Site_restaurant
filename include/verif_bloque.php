<?php
session_start();

// Si la personne n'est pas connectée, rien à faire
if (!isset($_SESSION['email'])) {
    echo json_encode(['bloque' => false]);
    exit;
}

// __DIR__ donne le chemin absolu du dossier "include". 
// On remonte d'un cran pour aller chercher Folder_Data.
$chemin_json = __DIR__ . "/../Folder_Data/ofdbisqfsqf.json"; // Due a un probleme de positions des fichier

if (!file_exists($chemin_json)) {
    echo json_encode(['bloque' => false, 'erreur' => 'Fichier JSON introuvable']);
    exit;
}

$json_users = file_get_contents($chemin_json);
$users = json_decode($json_users, true);

if ($users) {
    foreach ($users as $user) {
        if ($user['email'] === $_SESSION['email']) {
            if (!empty($user['bloque']) || !empty($user['bloquee'])) {
                
                // On détruit la session immédiatement sur le serveur
                session_destroy(); 
                
                echo json_encode(['bloque' => true]);
                exit;
            }
        }
    }
}

echo json_encode(['bloque' => false]);