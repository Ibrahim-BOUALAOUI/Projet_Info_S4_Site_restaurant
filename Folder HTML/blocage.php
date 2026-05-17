<?php
session_start();
header('Content-Type: application/json');

// Vérifier que c'est un admin
require("../include/Permission.php");
$role = $_SESSION['permission'] ?? null;
if (!isAdmin($role)) {
    echo json_encode(["succes" => false, "message" => "Accès refusé"]);
    exit;
}

// Récupérer les données envoyées en JSON
$input = json_decode(file_get_contents("php://input"), true);
$userId = $input['id'] ?? null;
$bloquer = $input['bloquer'] ?? null;

if (!$userId || $bloquer === null) {
    echo json_encode(["succes" => false, "message" => "Données manquantes"]);
    exit;
}

// Modifier le JSON
$json = file_get_contents("../Folder_Data/utilisateur.json");
$users = json_decode($json, true);
$trouve = false;

foreach ($users as &$user) {
    if ($user['id'] === $userId) {
        $user['bloque'] = $bloquer;
        $trouve = true;
        break;
    }
}
// Verification
if (!$trouve) {
    echo json_encode(["succes" => false, "message" => "Utilisateur introuvable"]);
    exit;
}

file_put_contents("../Folder_Data/utilisateur.json",
    json_encode(array_values($users), JSON_PRETTY_PRINT));

echo json_encode(["succes" => true, "message" => $bloquer ? "Utilisateur bloqué" : "Utilisateur débloqué"]);
?>