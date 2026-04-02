<?php
session_start();


if (!isset($_SESSION['email'])) {
    // On le renvoie vers la page de connexion avec un petit message d'erreur (optionnel)
    header("Location: connection.php?erreur=connexion_requise");
    exit();
}
// On vérifie si on a bien reçu le nom et le prix
if (isset($_GET['nom']) && isset($_GET['prix'])) {

    // Si la liste "panier" n'existe pas encore dans la session, on la crée
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // On AJOUTE l'article
    $_SESSION['panier'][] = [
        "nom" => $_GET['nom'],
        "prix" => $_GET['prix']
    ];
}

// SURTOUT : On repart direct sur la page précédente sans rien afficher
// On ne met PAS de message "panier vide" ici !
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
