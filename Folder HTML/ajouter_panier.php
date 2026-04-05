<?php
session_start();


if (!isset($_SESSION['email'])) {

    header("Location: connexion.php?erreur=connexion_requise");
    exit();
}

if (isset($_GET['nom']) && isset($_GET['prix'])) {


    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }


    $_SESSION['panier'][] = [
        "nom" => $_GET['nom'],
        "prix" => $_GET['prix']
    ];
}


header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
