<?php
session_start();

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header("Location: index.php");
    exit();
}


$email_client = $_SESSION['email'] ?? null;
$adresse_trouvee = null;

if ($email_client) {

    $file_users = "../Folder_Data/utilisateur.json";
    if (file_exists($file_users)) {
        $users = json_decode(file_get_contents($file_users), true);


        for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]['email'] === $email_client) {
                $adresse_trouvee = $users[$i]['adress'];
            }
        }
    }
}


$nouvelleCommande = [
    "id" => time(),
    "client" => $email_client ?? "Client Invité",
    "adresse" => $adresse_trouvee,
    "articles" => $_SESSION['panier'],
    "statut" => "a preparer",
    "date" => date("Y-m-d H:i:s")
];

$fichier = '../Folder_Data/commandes.json';
$commandesActuelles = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $commandesActuelles = json_decode($contenu, true);
    if ($commandesActuelles === null) {
        $commandesActuelles = [];
    }
}

$commandesActuelles[] = $nouvelleCommande;

file_put_contents($fichier, json_encode($commandesActuelles, JSON_PRETTY_PRINT));

unset($_SESSION['panier']);
header("Location: index.php");
exit();
