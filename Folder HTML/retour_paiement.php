<?php
session_start();
require('getapikey.php'); //récupérer la clé secrète

// 1. Récupération des données renvoyées par CYBank dans l'URL
$status      = isset($_GET['status']) ? $_GET['status'] : 'error';
$id_trans    = isset($_GET['transaction']) ? $_GET['transaction'] : 'Inconnu';
$montant_paye = isset($_GET['montant']) ? $_GET['montant'] : '0.00';
$vendeur     = isset($_GET['vendeur']) ? $_GET['vendeur'] : '';
$control_psp = isset($_GET['control']) ? $_GET['control'] : '';

$heure_retrait = isset($_SESSION['choix_heure']) ? $_SESSION['choix_heure'] : "Immédiat";
$message = "";
$adresse_finale = "Non renseignée";

// 2. RECALCUL DU CONTRÔLE DE SÉCURITÉ
$api_key = getAPIKey($vendeur);
$phrase_verif = $api_key . "#" . $id_trans . "#" . $montant_paye . "#" . $vendeur . "#" . $status . "#";
$control_verif = md5($phrase_verif);

// 3. VÉRIFICATION DE L'INTÉGRITÉ (au cas ou l'URL est modifiée)
if ($control_psp !== $control_verif) {
    $message = "Alerte de sécurité : Les données de paiement ont été altérées !";
    $status = "fraud"; // On force l'échec
} 
// 4. VÉRIFICATION DU MONTANT (Est-ce que le montant payé est le bon ?)
else {
    $total_panier = 0;
    if (isset($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $item) {
            $total_panier += $item['prix'];
        }
    }
    $total_attendu = number_format($total_panier, 2, '.', '');

    if ($montant_paye !== $total_attendu) {
        $message = "Erreur : Le montant payé ($montant_paye €) ne correspond pas au total du panier ($total_attendu €).";
        $status = "error_amount";
    }
}

// 5. ENREGISTREMENT SI TOUT EST VALIDE
if ($status === 'accepted' && isset($_SESSION['panier']) && count($_SESSION['panier']) > 0) {
    
    $email_session = $_SESSION['email'];
    $users_data = json_decode(file_get_contents('../Folder_Data/utilisateur.json'), true) ?? array();

    foreach ($users_data as $user) {
        if ($user['email'] === $email_session) {
            $adresse_finale = isset($user['adress']) ? $user['adress'] : "Non renseignée";
            break;
        }
    }

    $nouvelle_commande = array(
        "id" => $id_trans,
        "client" => $email_session,
        "adresse" => $adresse_finale, 
        "articles" => $_SESSION['panier'],
        "statut" => "a preparer",
        "heure_prevue" => $heure_retrait,
        "date" => date("Y-m-d H:i:s"),
        "montant_total" => $montant_paye
    );

    $file_path = '../Folder_Data/commandes.json';
    $cmds_existantes = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : array();
    
    $cmds_existantes[] = $nouvelle_commande;
    file_put_contents($file_path, json_encode($cmds_existantes, JSON_PRETTY_PRINT));

    // Nettoyage de la session après succès
    unset($_SESSION['choix_heure']);
    $_SESSION['panier'] = array();
    $message = "Commande enregistrée avec succès !";
} elseif (empty($message)) {
    $message = "Le paiement a été refusé par la banque.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" id="style-sombre" href="">
    <script src="../Folder_JS/affichage.js" defer></script>
    <meta charset="UTF-8">
    <title>Confirmation de commande</title>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
</head>
<body style="background:#121212; color:white; text-align:center; padding-top:100px;">
    <h1><?php echo $message; ?></h1>
    <p>Transaction ID : <?php echo htmlspecialchars($id_trans); ?></p>
    <?php if ($status === 'accepted'): ?>
        <p>Montant validé : <strong><?php echo htmlspecialchars($montant_paye); ?> €</strong></p>
        <p>Adresse de livraison : <strong><?php echo htmlspecialchars($adresse_finale); ?></strong></p>
    <?php endif; ?>
    <br>
    <a href="index.php" style="color:#FE9301; text-decoration:none; border:1px solid #FE9301; padding:10px;">Retour Accueil</a>
</body>
</html>