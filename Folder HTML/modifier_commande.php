<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_GET['id_cmd'])) {
    header("Location: index.php");
    exit();
}

$id_cmd = $_GET['id_cmd'];
$file_path = '../Folder_Data/commandes.json';
$commandes = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : [];

$commande_cible = null;
foreach ($commandes as $cmd) {
    if ($cmd['id'] == $id_cmd && $cmd['client'] === $_SESSION['email'] && $cmd['statut'] === 'a preparer') {
        $commande_cible = $cmd;
        break;
    }
}

if (!$commande_cible) {
    header("Location: Profil.php"); // Commande introuvable ou déjà en préparation
    exit();
}

// On injecte la commande dans la session pour simuler le panier
$_SESSION['panier'] = $commande_cible['articles'];
$_SESSION['modifying_cmd_id'] = $commande_cible['id'];
$_SESSION['modifying_cmd_amount_paid'] = (float)$commande_cible['montant_total'];
$_SESSION['choix_heure'] = $commande_cible['heure_prevue'];

// Redirection vers le panier pour voir et modifier les articles
header("Location: panier.php");
exit();
