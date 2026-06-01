<?php
// Cette page reçoit le retour de CYBank ou les validations sans paiement bancaire.
session_start();
require('getapikey.php');

// On récupère les informations renvoyées dans l'URL après le paiement.
if (isset($_GET['status'])) {
    $status = $_GET['status'];
} else {
    $status = 'error';
}

if (isset($_GET['transaction'])) {
    $id_trans = $_GET['transaction'];
} else {
    $id_trans = 'Inconnu';
}

if (isset($_GET['montant'])) {
    $montant_paye = $_GET['montant'];
} else {
    $montant_paye = '0.00';
}

if (isset($_GET['vendeur'])) {
    $vendeur = $_GET['vendeur'];
} else {
    $vendeur = '';
}

if (isset($_GET['control'])) {
    $control_psp = $_GET['control'];
} else {
    $control_psp = '';
}

if (isset($_GET['mode_modif'])) {
    $mode_modif = $_GET['mode_modif'];
} else {
    $mode_modif = '';
}

if (isset($_SESSION['choix_heure'])) {
    $heure_retrait = $_SESSION['choix_heure'];
} else {
    $heure_retrait = "Immédiat";
}
$message = "";
$adresse_finale = "Non renseignée";
// Si cet identifiant existe, on sait que le client modifie une commande déjà passée.
$is_modification = isset($_SESSION['modifying_cmd_id']);

// 1. RECALCUL ET VÉRIFICATION DU CONTRÔLE DE SÉCURITÉ
// Les seuls cas contournés sont ceux où aucun vrai paiement bancaire n'est envoyé à CYBank.
$bypass_control = in_array($mode_modif, ['avoir_généré', 'montant_identique', 'zero_euro_avoir'], true);

if (!$bypass_control) {
    // On recalcule la valeur de contrôle pour vérifier que le retour banque n'a pas été modifié.
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

if ($bypass_control) {
    $mode_sans_paiement_valide = false;

    // Cas où la modification coûte moins cher : on vérifie que l'avoir attendu correspond au montant.
    if ($mode_modif === 'avoir_généré' && $is_modification) {
        $ancien_paye = (float)($_SESSION['modifying_cmd_amount_paid'] ?? 0);
        $avoir_attendu = $ancien_paye - $total_actuel;
        $mode_sans_paiement_valide = $avoir_attendu > 0 && abs((float)$montant_paye - $avoir_attendu) < 0.01;
    }

    // Cas où la modification garde exactement le même montant.
    if ($mode_modif === 'montant_identique' && $is_modification) {
        $ancien_paye = (float)($_SESSION['modifying_cmd_amount_paid'] ?? 0);
        $mode_sans_paiement_valide = abs($total_actuel - $ancien_paye) < 0.01 && (float)$montant_paye === 0.0;
    }

    // Cas où l'avoir du client couvre totalement la commande, donc aucun paiement banque n'est nécessaire.
    if ($mode_modif === 'zero_euro_avoir' && !$is_modification && isset($_SESSION['email'])) {
        $avoir_disponible = 0.0;
        $users_file = '../Folder_Data/ofdbisqfsqf.json';
        $users_list = json_decode(file_get_contents($users_file), true);
        if (!is_array($users_list)) {
            $users_list = [];
        }

        foreach ($users_list as $user) {
            if (($user['email'] ?? '') === $_SESSION['email']) {
                $avoir_disponible = (float)($user['avoir'] ?? 0);
                break;
            }
        }

        $avoir_deduit_session = (float)($_SESSION['avoir_deduit'] ?? 0);
        $mode_sans_paiement_valide = $total_actuel > 0 &&
            $avoir_disponible >= $total_actuel &&
            abs($avoir_deduit_session - $total_actuel) < 0.01 &&
            (float)$montant_paye === 0.0;
    }

    if (!$mode_sans_paiement_valide) {
        $message = "Alerte de sécurité : le mode de paiement sans banque n'est pas valide.";
        $status = "fraud";
    }
}

// 3. ENREGISTREMENT ET VALIDATION DES DONNÉES
// On autorise un panier vide UNIQUEMENT si c'est une modification (ce qui équivaut à une annulation)
$panier_valide = isset($_SESSION['panier']) && (count($_SESSION['panier']) > 0 || $is_modification);

if ($status === 'accepted' && $panier_valide) {

    $file_path = '../Folder_Data/dfsqfiqsoifsvquvfipqf.json';
    // On charge toutes les commandes existantes avant d'ajouter ou modifier une commande.
    if (file_exists($file_path)) {
        $cmds_existantes = json_decode(file_get_contents($file_path), true);
        if ($cmds_existantes === null) {
            $cmds_existantes = array();
        }
    } else {
        $cmds_existantes = array();
    }

    if ($is_modification) {
        // --- CAS A : MODIFICATION D'UNE COMMANDE EXISTANTE ---
        $id_target = $_SESSION['modifying_cmd_id'];
        $ancien_paye = (float)$_SESSION['modifying_cmd_amount_paid'];
        $ticket_avoir_message = "";

        // Si le nouveau panier coûte moins cher, on crédite la différence dans l'avoir du client.
        if ($total_actuel < $ancien_paye) {
            $reduction = $ancien_paye - $total_actuel;
            $ticket_avoir_message = " Un avoir de " . number_format($reduction, 2, ',', ' ') . " € a été ajouté à votre compte !";

            $users_file = '../Folder_Data/ofdbisqfsqf.json';
            $users_list = json_decode(file_get_contents($users_file), true);
            if ($users_list === null) {
                $users_list = array();
            }
            for ($u = 0; $u < count($users_list); $u++) {
                if ($users_list[$u]['email'] === $_SESSION['email']) {
                    if (isset($users_list[$u]['avoir'])) {
                        $avoir_actuel = (float)$users_list[$u]['avoir'];
                    } else {
                        $avoir_actuel = 0.0;
                    }
                    $users_list[$u]['avoir'] = $avoir_actuel + $reduction;
                    break;
                }
            }
            file_put_contents($users_file, json_encode($users_list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        // Écrasement et mise à jour dans dfsqfiqsoifsvquvfipqf.json
        // On remplace les anciens articles de la commande par le contenu actuel du panier.
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

                    // Si un complément a été payé, on garde l'identifiant de cette transaction.
                    if (strpos($id_trans, 'TXDIFF') !== false) {
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
            $users_file = '../Folder_Data/ofdbisqfsqf.json';
            $users_list = json_decode(file_get_contents($users_file), true);
            if ($users_list === null) {
                $users_list = array();
            }

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
        $users_data = json_decode(file_get_contents('../Folder_Data/ofdbisqfsqf.json'), true);
        if ($users_data === null) {
            $users_data = array();
        }
        foreach ($users_data as $user) {
            if ($user['email'] === $email_session) {
                if (isset($user['adress'])) {
                    $adresse_finale = $user['adress'];
                } else {
                    $adresse_finale = "Non renseignée";
                }
                break;
            }
        }

        // Insertion de la nouvelle commande
        // Si ce n'est pas une modification, on crée une nouvelle commande à préparer.
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
    // Une fois la commande enregistrée, on vide les informations temporaires.
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
    <?php // Cette partie affiche le résultat final du paiement ou de la modification. ?>
    <h1><?php echo $message; ?></h1>
    <p>Transaction ID : <?php echo htmlspecialchars($id_trans); ?></p>
    <?php if ($status === 'accepted'): ?>
        <p>Adresse de livraison : <strong><?php echo htmlspecialchars($adresse_finale); ?></strong></p>
    <?php endif; ?>
    <br>
    <a href="index.php" style="color:#FE9301; text-decoration:none; border:1px solid #FE9301; padding:10px;">Retour Accueil</a>
</body>

</html>
