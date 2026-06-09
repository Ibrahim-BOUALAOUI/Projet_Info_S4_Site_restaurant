<?php
header('Content-Type: application/json');

// On récupère l'id de la commande demandé dans l'URL (?id=...)
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["succes" => false, "message" => "Aucun id fourni"]);
    exit;
}

// On lit le fichier des commandes
$commandes = json_decode(file_get_contents("../Folder_Data/commandes.json"), true);

// On cherche la commande correspondante
foreach ($commandes as $cmd) {
    if ($cmd['id'] == $id) {
        echo json_encode([
            "succes" => true,
            "statut" => $cmd['statut']
        ]);
        exit;
    }
}

// Si on arrive ici, aucune commande n'a été trouvée
echo json_encode(["succes" => false, "message" => "Commande introuvable"]);
?>