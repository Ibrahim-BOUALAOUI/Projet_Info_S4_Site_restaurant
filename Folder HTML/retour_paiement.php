<?php
session_start();
require('getapikey.php');

$status       = isset($_GET['status']) ? $_GET['status'] : 'error';
$id_trans     = isset($_GET['transaction']) ? $_GET['transaction'] : 'Inconnu';
$montant_paye = isset($_GET['montant']) ? $_GET['montant'] : '0.00';
$vendeur      = isset($_GET['vendeur']) ? $_GET['vendeur'] : '';
$control_psp  = isset($_GET['control']) ? $_GET['control'] : '';
$mode_modif   = isset($_GET['mode_modif']) ? $_GET['mode_modif'] : '';

$heure_retrait = isset($_SESSION['choix_heure']) ? $_SESSION['choix_heure'] : "Immédiat";
$message = "";
$adresse_finale = "Non renseignée";
$is_modification = isset($_SESSION['modifying_cmd_id']);

// 1. RECALCUL ET VÉRIFICATION DU CONTRÔLE DE SÉCURITÉ
$bypass_control = ($mode_modif === 'avoir_généré' || $mode_modif === 'montant_identique' || $mode_modif === 'zero_euro_avoir' || strpos($id_trans, 'TX_DIFF_') !== false);

if (!$bypass_control) {
    $api_key = getAPIKey($vendeur);
    $phrase_verif = $api_key . "#" . $id_trans . "#" . $montant_paye . "#" . $vendeur . "#" . $status . "#";
    $control_verif = md5($phrase_verif);

    if ($control_psp !== $control_verif) {
        $message = "Alerte de sécurité : Les données de paiement ont été altérées !";
        $status = "fraud";
    }
}

// 2. CALCUL DU TOTAL DU PANIER ACTUEL
$total_actuel = 0;
if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $total_actuel += $item['prix'];
    }
}
$nouveau_total_formatte = number_format($total_actuel, 2, '.', '');

// 3. ENREGISTREMENT ET VALIDATION DES DONNÉES
// On autorise un panier vide UNIQUEMENT si c'est une modification (ce qui équivaut à une annulation)
$panier_valide = isset($_SESSION['panier']) && (count($_SESSION['panier']) > 0 || $is_modification);

if ($status === 'accepted' && $panier_valide) {

    $file_path = '../Folder_Data/commandes.json';
    $cmds_existantes = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : array();

    if ($is_modification) {
        // --- CAS A : MODIFICATION D'UNE COMMANDE EXISTANTE ---
        $id_target = $_SESSION['modifying_cmd_id'];
        $ancien_paye = (float)$_SESSION['modifying_cmd_amount_paid'];
        $ticket_avoir_message = "";

        // Si le nouveau panier coûte MOINS cher (ou vaut 0 car vide) -> on crédite la différence en avoir
        if ($total_actuel < $ancien_paye) {
            $reduction = $ancien_paye - $total_actuel;
            $ticket_avoir_message = " Un avoir de " . number_format($reduction, 2, ',', ' ') . " € a été ajouté à votre compte !";

            $users_file = '../Folder_Data/utilisateur.json';
            $users_list = json_decode(file_get_contents($users_file), true) ?? array();
            for ($u = 0; $u < count($users_list); $u++) {
                if ($users_list[$u]['email'] === $_SESSION['email']) {
                    $avoir_actuel = isset($users_list[$u]['avoir']) ? (float)$users_list[$u]['avoir'] : 0.0;
                    $users_list[$u]['avoir'] = $avoir_actuel + $reduction;
                    break;
                }
            }
            file_put_contents($users_file, json_encode($users_list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        // Écrasement et mise à jour dans commandes.json
        for ($i = 0; $i < count($cmds_existantes); $i++) {
            if ($cmds_existantes[$i]['id'] == $id_target) {

                // Si l'utilisateur a vidé son panier -> Annulation complète de la commande
                if (count($_SESSION['panier']) === 0) {
                    $cmds_existantes[$i]['articles'] = array();
                    $cmds_existantes[$i]['montant_total'] = "0.00";
                    $cmds_existantes[$i]['statut'] = "abandonnee"; // Passera en rouge sur Profil.php
                    $message = "Commande annulée avec succès !" . $ticket_avoir_message;
                } else {
                    // Sinon, modification classique des articles
                    $cmds_existantes[$i]['articles'] = $_SESSION['panier'];
                    $cmds_existantes[$i]['heure_prevue'] = $heure_retrait;
                    $cmds_existantes[$i]['montant_total'] = $nouveau_total_formatte;

                    if (strpos($id_trans, 'TX_DIFF_') !== false) {
                        $cmds_existantes[$i]['id_transaction_complement'] = $id_trans;
                    }
                    $message = "Commande mise à jour avec succès !" . $ticket_avoir_message;
                }

                $adresse_finale = $cmds_existantes[$i]['adresse'];
                break;
            }
        }
        file_put_contents($file_path, json_encode($cmds_existantes, JSON_PRETTY_PRINT));
    } else {
        // --- CAS B : FLUX D'UNE NOUVELLE COMMANDE CLASSIQUE ---

        // Si l'utilisateur a consommé tout ou partie de son avoir
        if (isset($_SESSION['avoir_deduit']) && $_SESSION['avoir_deduit'] > 0) {
            $users_file = '../Folder_Data/utilisateur.json';
            $users_list = json_decode(file_get_contents($users_file), true) ?? array();

            for ($u = 0; $u < count($users_list); $u++) {
                if ($users_list[$u]['email'] === $_SESSION['email']) {
                    // On retire exactement ce qui a été déduit du panier
                    $users_list[$u]['avoir'] = (float)$users_list[$u]['avoir'] - (float)$_SESSION['avoir_deduit'];
                    if ($users_list[$u]['avoir'] < 0) $users_list[$u]['avoir'] = 0;
                    break;
                }
            }
            file_put_contents($users_file, json_encode($users_list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            unset($_SESSION['avoir_deduit']);
        }

        // Récupération de l'adresse
        $email_session = $_SESSION['email'];
        $users_data = json_decode(file_get_contents('../Folder_Data/utilisateur.json'), true) ?? array();
        foreach ($users_data as $user) {
            if ($user['email'] === $email_session) {
                $adresse_finale = isset($user['adress']) ? $user['adress'] : "Non renseignée";
                break;
            }
        }

        // Insertion de la nouvelle commande
        $nouvelle_commande = array(
            "id" => $id_trans,
            "client" => $email_session,
            "adresse" => $adresse_finale,
            "articles" => $_SESSION['panier'],
            "statut" => "a preparer",
            "heure_prevue" => $heure_retrait,
            "date" => date("Y-m-d H:i:s"),
            "montant_total" => $nouveau_total_formatte
        );

        $cmds_existantes[] = $nouvelle_commande;
        file_put_contents($file_path, json_encode($cmds_existantes, JSON_PRETTY_PRINT));
        $message = "Commande enregistrée avec succès !";
    }

    // NETTOYAGE DES SESSIONS REQUISES
    unset($_SESSION['choix_heure']);
    unset($_SESSION['modifying_cmd_id']);
    unset($_SESSION['modifying_cmd_amount_paid']);
    $_SESSION['panier'] = array();
} elseif (empty($message)) {
    $message = "Le paiement a été refusé par la banque.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Confirmation de commande</title>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
</head>

<body style="background:#121212; color:white; text-align:center; padding-top:100px;">
    <h1><?php echo $message; ?></h1>
    <p>Transaction ID : <?php echo htmlspecialchars($id_trans); ?></p>
    <?php if ($status === 'accepted'): ?>
        <p>Adresse de livraison : <strong><?php echo htmlspecialchars($adresse_finale); ?></strong></p>
    <?php endif; ?>
    <br>
    <a href="index.php" style="color:#FE9301; text-decoration:none; border:1px solid #FE9301; padding:10px;">Retour Accueil</a>
</body>

</html>