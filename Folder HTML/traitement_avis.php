<?php
// Cette page traite le formulaire envoyé depuis Avis.php.
session_start();

// Il faut être connecté pour envoyer un avis.
if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

// On accepte uniquement les données envoyées par le formulaire en POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Profil.php");
    exit();
}

// Récupération et nettoyage des champs envoyés par le formulaire.
$idCmd = $_POST['commande_id'] ?? '';
$notePlat = (int)($_POST['rate_plat'] ?? 0);
$noteLivraison = (int)($_POST['rate_livraison'] ?? 0);
$commentaire = trim($_POST['commentaire'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');

// On bloque les avis incomplets ou avec une note hors de 1 à 5.
if ($idCmd === '' || $notePlat < 1 || $notePlat > 5 || $noteLivraison < 1 || $noteLivraison > 5) {
    header("Location: Profil.php?msg=Avis invalide");
    exit();
}

$fichierCommandes = '../Folder_Data/dfsqfiqsoifsvquvfipqf.json';
$fichierAvis = '../Folder_Data/avis.json';

// On charge les commandes et les avis déjà existants.
$commandes = json_decode(file_get_contents($fichierCommandes), true) ?? [];
$avis = file_exists($fichierAvis)
    ? json_decode(file_get_contents($fichierAvis), true)
    : [];

if (!is_array($avis)) {
    $avis = [];
}

$avisDejaEnvoye = false;
// Double sécurité : on vérifie dans avis.json que ce client n'a pas déjà noté cette commande.
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

// On cherche la commande à noter et on vérifie qu'elle remplit toutes les conditions.
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
        // On marque la commande comme déjà notée pour empêcher une deuxième notation.
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

// On sauvegarde la commande avec deja_note = true.
file_put_contents(
    $fichierCommandes,
    json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// On prépare le nouvel avis à ajouter dans avis.json.
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

// On enregistre la liste complète des avis avec le nouvel avis ajouté.
file_put_contents(
    $fichierAvis,
    json_encode($avis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// Une fois l'avis enregistré, on renvoie le client vers son profil.
header("Location: Profil.php?msg=Merci pour votre avis !");
exit();
