<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Profil.php");
    exit();
}

$idCmd = $_POST['commande_id'] ?? '';
$notePlat = (int)($_POST['rate_plat'] ?? 0);
$noteLivraison = (int)($_POST['rate_livraison'] ?? 0);
$commentaire = trim($_POST['commentaire'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');

if ($idCmd === '' || $notePlat < 1 || $notePlat > 5 || $noteLivraison < 1 || $noteLivraison > 5) {
    header("Location: Profil.php?msg=Avis invalide");
    exit();
}

$fichierCommandes = '../Folder_Data/dfsqfiqsoifsvquvfipqf.json';
$fichierAvis = '../Folder_Data/avis.json';

$commandes = json_decode(file_get_contents($fichierCommandes), true) ?? [];
$avis = file_exists($fichierAvis)
    ? json_decode(file_get_contents($fichierAvis), true)
    : [];

if (!is_array($avis)) {
    $avis = [];
}

$avisDejaEnvoye = false;
foreach ($avis as $unAvis) {
    if (
        (string)($unAvis['commande_id'] ?? '') === $idCmd &&
        ($unAvis['client'] ?? $unAvis['email'] ?? '') === $_SESSION['email']
    ) {
        $avisDejaEnvoye = true;
        break;
    }
}

if ($avisDejaEnvoye) {
    header("Location: Profil.php?msg=Commande déjà notée");
    exit();
}

$commandeTrouvee = false;

foreach ($commandes as &$cmd) {
    $statut = $cmd['statut'] ?? '';
    $typeCommande = $cmd['type_commande'] ?? 'livraison';
    $dejaNote = $cmd['deja_note'] ?? false;

    if (
        (string)($cmd['id'] ?? '') === $idCmd &&
        ($cmd['client'] ?? '') === $_SESSION['email'] &&
        $statut === 'livree' &&
        $typeCommande === 'livraison' &&
        !$dejaNote
    ) {
        $cmd['deja_note'] = true;
        $commandeTrouvee = true;
        break;
    }
}
unset($cmd);

if (!$commandeTrouvee) {
    header("Location: Profil.php?msg=Commande non notifiable ou déjà notée");
    exit();
}

file_put_contents(
    $fichierCommandes,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

$avis[] = [
    "id" => uniqid("avis_"),
    "commande_id" => $idCmd,
    "client" => $_SESSION['email'],
    "email" => $_SESSION['email'],
    "note_plat" => $notePlat,
    "note_livraison" => $noteLivraison,
    "commentaire" => $commentaire,
    "avis" => $commentaire,
    "prenom" => $prenom,
    "date" => date("Y-m-d H:i:s")
];

file_put_contents(
    $fichierAvis,
    json_encode($avis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

header("Location: Profil.php?msg=Merci pour votre avis !");
exit();
