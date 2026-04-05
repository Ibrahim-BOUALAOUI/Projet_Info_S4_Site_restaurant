<?php
session_start();

$status = isset($_GET['status']) ? $_GET['status'] : 'error';
$id_trans = isset($_GET['transaction']) ? $_GET['transaction'] : 'Inconnu';

// On récupère l'heure que tu as fixée dans le panier (Session)
$heure_retrait = isset($_SESSION['choix_heure']) ? $_SESSION['choix_heure'] : "Immédiat";

if ($status === 'accepted' && isset($_SESSION['panier']) && count($_SESSION['panier']) > 0) {

    $email_session = $_SESSION['email']; // L'email de celui qui est connecté
    $adresse_finale = "Adresse non trouvée"; // Valeur par défaut

    // --- 1. ALLER CHERCHER L'ADRESSE DANS utilisateur.json ---
    $users_data = json_decode(file_get_contents('../Folder_Data/utilisateur.json'), true) ?? array();

    for ($i = 0; $i < count($users_data); $i++) {
        if ($users_data[$i]['email'] === $email_session) {
            // IMPORTANT : On vérifie l'orthographe 'adress' comme dans ton JSON
            $adresse_finale = isset($users_data[$i]['adress']) ? $users_data[$i]['adress'] : "Non renseignée";
            break;
        }
    }

    // --- 2. PRÉPARER LA COMMANDE AVEC TOUTES LES INFOS ---
    $nouvelle_commande = array(
        "id" => $id_trans,
        "client" => $email_session,
        "adresse" => $adresse_finale, // L'adresse récupérée ci-dessus
        "articles" => $_SESSION['panier'],
        "statut" => "a preparer",
        "heure_prevue" => $heure_retrait,
        "date" => date("Y-m-d H:i:s")
    );

    // --- 3. ENREGISTRER DANS commandes.json ---
    $file_path = '../Folder_Data/commandes.json';
    $cmds_existantes = array();
    if (file_exists($file_path)) {
        $cmds_existantes = json_decode(file_get_contents($file_path), true) ?? array();
    }

    $cmds_existantes[] = $nouvelle_commande;
    file_put_contents($file_path, json_encode($cmds_existantes, JSON_PRETTY_PRINT));

    // Nettoyage
    unset($_SESSION['choix_heure']);
    $_SESSION['panier'] = array();
    $message = "Commande enregistrée avec succès !";
} else {
    $message = "Erreur de paiement.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
</head>

<body style="background:#121212; color:white; text-align:center; padding-top:100px;">
    <h1><?php echo $message; ?></h1>
    <p>Transaction : <?php echo htmlspecialchars($id_trans); ?></p>
    <p>Adresse de livraison : <strong><?php echo htmlspecialchars($adresse_finale); ?></strong></p>
    <br>
    <a href="index.php" style="color:#FE9301; text-decoration:none; border:1px solid #FE9301; padding:10px;">Retour Accueil</a>
</body>

</html>