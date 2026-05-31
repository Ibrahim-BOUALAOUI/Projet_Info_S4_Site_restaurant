<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_GET['id_cmd'])) {
    header("Location: Profil.php");
    exit();
}

$idCmd = $_GET['id_cmd'];
$fichierCommandes = '../Folder_Data/commandes.json';

if (!file_exists($fichierCommandes)) {
    header("Location: Profil.php?msg=Commande introuvable");
    exit();
}

$commandes = json_decode(file_get_contents($fichierCommandes), true);
if (!is_array($commandes)) {
    $commandes = [];
}

$commandeTrouvee = null;
foreach ($commandes as $cmd) {
    if (
        (string)($cmd['id'] ?? '') === (string)$idCmd &&
        ($cmd['client'] ?? '') === $_SESSION['email'] &&
        !empty($cmd['articles']) &&
        is_array($cmd['articles'])
    ) {
        $commandeTrouvee = $cmd;
        break;
    }
}

if ($commandeTrouvee === null) {
    header("Location: Profil.php?msg=Commande introuvable");
    exit();
}

$nouveauPanier = [];
foreach ($commandeTrouvee['articles'] as $article) {
    if (isset($article['nom']) && isset($article['prix'])) {
        $nouveauPanier[] = [
            "nom" => $article['nom'],
            "prix" => $article['prix']
        ];
    }
}

if (empty($nouveauPanier)) {
    header("Location: Profil.php?msg=Commande vide");
    exit();
}

$_SESSION['panier'] = $nouveauPanier;
unset($_SESSION['modifying_cmd_id']);
unset($_SESSION['modifying_cmd_amount_paid']);
unset($_SESSION['choix_heure']);

header("Location: panier.php");
exit();
?>
