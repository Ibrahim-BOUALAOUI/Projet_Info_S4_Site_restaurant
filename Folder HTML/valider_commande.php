<?php
session_start();

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header("Location: index.php");
    exit();
}


$nouvelleCommande = [
    "id" => time(),
    "client" => $_SESSION['email'] ?? "Client Invité",
    "articles" => $_SESSION['panier'],
    "statut" => "à préparer",
    "date" => date("Y-m-d H:i:s")
];


$fichier = '../Folder_Data/commandes.json';
$commandesActuelles = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $commandesActuelles = json_decode($contenu, true);
}

$commandesActuelles[] = $nouvelleCommande;


file_put_contents($fichier, json_encode($commandesActuelles, JSON_PRETTY_PRINT));

unset($_SESSION['panier']);
header("Location: index.php");
exit();
