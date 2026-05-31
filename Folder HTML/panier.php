<?php
session_start();
require('getapikey.php');

if (isset($_POST['fixer_moment'])) {
    $mode = $_POST['mode_retrait'];
    if (isset($_POST['heure_retrait'])) {
        $heure = $_POST['heure_retrait'];
    } else {
        $heure = '';
    }

    if ($mode === "Plus tard" && !empty($heure)) {
        $_SESSION['choix_heure'] = $heure;
    } else {
        $_SESSION['choix_heure'] = "Immédiat";
    }
}

if (!isset($_SESSION['choix_heure'])) {
    $_SESSION['choix_heure'] = "Immédiat";
}

$valeur_heure_retrait = "";
if ($_SESSION['choix_heure'] != "Immédiat") {
    $valeur_heure_retrait = $_SESSION['choix_heure'];
}

if (isset($_SESSION['panier'])) {
    $panier = $_SESSION['panier'];
} else {
    $panier = array();
}

$total_general = 0;
for ($i = 0; $i < count($panier); $i++) {
    $total_general += $panier[$i]['prix'];
}


$avoir_disponible = 0;
$avoir_deduit = 0;
$montant_apres_avoir = $total_general;

if (isset($_SESSION['email']) && !isset($_SESSION['modifying_cmd_id'])) {
    $users_data = json_decode(file_get_contents('../Folder_Data/utilisateur.json'), true);
    if ($users_data === null) {
        $users_data = [];
    }
    foreach ($users_data as $user) {
        if ($user['email'] === $_SESSION['email']) {
            if (isset($user['avoir'])) {
                $avoir_disponible = (float)$user['avoir'];
            } else {
                $avoir_disponible = 0.0;
            }
            break;
        }
    }
}

if ($avoir_disponible > 0) {
    if ($avoir_disponible >= $total_general) {
        $avoir_deduit = $total_general;
        $montant_apres_avoir = 0;
    } else {
        $avoir_deduit = $avoir_disponible;
        $montant_apres_avoir = $total_general - $avoir_disponible;
    }
    $_SESSION['avoir_deduit'] = $avoir_deduit;
} else {
    unset($_SESSION['avoir_deduit']);
}

$montant_formatte = number_format($montant_apres_avoir, 2, '.', '');

$vendeur = "MI-1_A";
$api_key = getAPIKey($vendeur);
$transaction = "TX" . time();
$url_retour = "http://localhost:8888/Folder HTML/retour_paiement.php";

$phrase = $api_key . "#" . $transaction . "#" . $montant_formatte . "#" . $vendeur . "#" . $url_retour . "#";
$control = md5($phrase);

//Gestion du blocage de la personne
$json_users = file_get_contents("../Folder_Data/utilisateur.json");
$users = json_decode($json_users, true);

if (isset($_SESSION['email'])) {
    foreach ($users as $user) {
        if ($user['email'] === $_SESSION['email'] && !empty($user['bloque'])) {
            session_destroy();
            header("Location: connexion.php?erreur=bloque");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <link rel="stylesheet" href="../Folder CSS/panier.css">
</head>

<body>
    <header class="top_bar" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 40px;"> 
        <a href="index.php">
            <img src="../Folder img/129.png" alt="Logo" width="200">
        </a>
</header>
    <div class="cart-container">
        <div class="cart-items">
            <h1 class="cart-title">🛒 Votre commande</h1>
            <?php if (count($panier) === 0) : ?>
                <?php if (isset($_SESSION['modifying_cmd_id'])): ?>
                    <p style="color: #e74c3c; font-weight: bold;">Votre panier est vide. Validez les modifications à droite pour confirmer l'annulation complète et générer votre avoir.</p>
                <?php else: ?>
                    <p>Votre panier est vide.</p>
                <?php endif; ?>
                <a href="Menus.php" style="color: orange;">Retour aux menus</a>
            <?php else : ?>
                <?php for ($i = 0; $i < count($panier); $i++) : ?>
                    <div class="cart-card">
                        <h3><?php echo htmlspecialchars($panier[$i]['nom']); ?></h3>
                        <p><?php echo number_format($panier[$i]['prix'], 2); ?> €</p>
                        <a href="supprimer_item.php?index=<?php echo $i; ?>">Supprimer</a>
                    </div>
                <?php endfor; ?>
                <br>
                <a href="Menus.php" class="btn-ajouter-plus" style="background: #222; color: #FE9301; padding: 10px; border: 1px solid #FE9301; text-decoration: none; border-radius: 5px; display: inline-block;">+ Ajouter un autre produit</a>
            <?php endif; ?>
        </div>

        <?php /* CHANGEMENT DE CONDITION ICI : On affiche le résumé si le panier contient des éléments OU si on est en train de modifier une commande */ ?>
        <?php if (count($panier) > 0 || isset($_SESSION['modifying_cmd_id'])) : ?>
            <div class="cart-summary">
                <h2 class="summary-title">Résumé</h2>

                <div style="margin: 20px 0; padding: 15px; border: 1px solid #FE9301; border-radius: 8px; background: rgba(254, 147, 1, 0.1);">
                    <form method="POST">
                        <p style="color: #FE9301; font-weight: bold; margin-bottom: 10px;">🕒 1. Heure de retrait :</p>
                        <input type="radio" name="mode_retrait" value="Immédiat" id="imm" <?php if ($_SESSION['choix_heure'] == "Immédiat") echo "checked"; ?>>
                        <label for="imm"> Immédiat</label><br>
                        <input type="radio" name="mode_retrait" value="Plus tard" id="tard" <?php if ($_SESSION['choix_heure'] != "Immédiat") echo "checked"; ?>>
                        <label for="tard"> À cette heure :</label>
                        <input type="time" name="heure_retrait" value="<?php echo $valeur_heure_retrait; ?>" style="background:#222; color:white; border:1px solid #444;">
                        <button type="submit" name="fixer_moment" style="display:block; margin-top:10px; background:#444; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:3px;">Valider</button>
                    </form>
                </div>

                <p>Retrait prévu : <strong><?php echo $_SESSION['choix_heure']; ?></strong></p>
                <p>Total articles : <strong><?php echo number_format($total_general, 2); ?> €</strong></p>

                <?php if (isset($_SESSION['modifying_cmd_id'])):
                    // -----------------------------------------------------------------
                    // LOGIQUE : MODE MODIFICATION DE COMMANDE EXISTANTE
                    // -----------------------------------------------------------------
                    $deja_paye = $_SESSION['modifying_cmd_amount_paid'];
                    $difference = $total_general - $deja_paye;
                    $diff_formattee = number_format(abs($difference), 2, '.', '');
                ?>
                    <div style="margin: 15px 0; padding: 10px; border: 1px dashed #3498db; border-radius: 5px; background: rgba(52, 152, 219, 0.1);">
                        <p>Déjà payé lors du premier achat : <strong><?= number_format($deja_paye, 2) ?> €</strong></p>

                        <?php if ($difference > 0): ?>
                            <p style="color: #e74c3c; font-weight: bold;">Reste à payer (complément) : <?= $diff_formattee ?> €</p>
                            <?php
                            $transaction_diff = "TX_DIFF_" . time();
                            $phrase_diff = $api_key . "#" . $transaction_diff . "#" . $diff_formattee . "#" . $vendeur . "#" . $url_retour . "#";
                            $control_diff = md5($phrase_diff);
                            ?>
                            <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST' style="margin-top: 10px;">
                                <input type='hidden' name='transaction' value='<?php echo $transaction_diff; ?>'>
                                <input type='hidden' name='montant' value='<?php echo $diff_formattee; ?>'>
                                <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
                                <input type='hidden' name='retour' value='<?php echo $url_retour; ?>'>
                                <input type='hidden' name='control' value='<?php echo $control_diff; ?>'>
                                <button type="submit" class="btn-confirm" style="width:100%; background-color: #3498db;">PAYER LE COMPLÉMENT</button>
                            </form>

                        <?php elseif ($difference < 0): ?>
                            <p style="color: #2ecc71; font-weight: bold;">Nouvel Avoir généré : <?= $diff_formattee ?> €</p>
                            <form action='retour_paiement.php' method='GET' style="margin-top: 10px;">
                                <input type='hidden' name='status' value='accepted'>
                                <input type='hidden' name='transaction' value='<?= $_SESSION['modifying_cmd_id'] ?>'>
                                <input type='hidden' name='montant' value='<?= $diff_formattee ?>'>
                                <input type='hidden' name='vendeur' value='<?= $vendeur ?>'>
                                <input type='hidden' name='mode_modif' value='avoir_généré'>
                                <button type="submit" class="btn-confirm" style="width:100%; background-color: #2ecc71;">VALIDER LES MODIFICATIONS</button>
                            </form>

                        <?php else: ?>
                            <p style="color: #2ecc71;">Montant inchangé (0.00 € de différence).</p>
                            <form action='retour_paiement.php' method='GET' style="margin-top: 10px;">
                                <input type='hidden' name='status' value='accepted'>
                                <input type='hidden' name='transaction' value='<?= $_SESSION['modifying_cmd_id'] ?>'>
                                <input type='hidden' name='montant' value='0.00'>
                                <input type='hidden' name='vendeur' value='<?= $vendeur ?>'>
                                <input type='hidden' name='mode_modif' value='montant_identique'>
                                <button type="submit" class="btn-confirm" style="width:100%;">VALIDER LES MODIFICATIONS</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <a href="annuler_modification.php" style="display:block; text-align:center; color:#e74c3c; margin-top:10px; text-decoration: none;">❌ Abandonner les modifications</a>

                <?php else: ?>
                    <?php if ($avoir_deduit > 0): ?>
                        <p style="color: #2ecc71; font-weight: bold;">🍀 Déduction Avoir : - <?php echo number_format($avoir_deduit, 2); ?> €</p>
                    <?php endif; ?>

                    <p>Total final à régler : <strong><?php echo $montant_formatte; ?> €</strong></p>

                    <?php if ($montant_apres_avoir <= 0): ?>
                        <form action='retour_paiement.php' method='GET' style="margin-top: 20px;">
                            <input type='hidden' name='status' value='accepted'>
                            <input type='hidden' name='transaction' value='<?= $transaction ?>'>
                            <input type='hidden' name='montant' value='0.00'>
                            <input type='hidden' name='vendeur' value='<?= $vendeur ?>'>
                            <input type='hidden' name='mode_modif' value='zero_euro_avoir'>
                            <button type="submit" class="btn-confirm" style="width:100%; background-color: #2ecc71;">VALIDER (COMMANDE 100% GRATUITE VIA AVOIR)</button>
                        </form>
                    <?php else: ?>
                        <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST' style="margin-top: 20px;">
                            <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
                            <input type='hidden' name='montant' value='<?php echo $montant_formatte; ?>'>
                            <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
                            <input type='hidden' name='retour' value='<?php echo $url_retour; ?>'>
                            <input type='hidden' name='control' value='<?php echo $control; ?>'>
                            <button type="submit" class="btn-confirm" style="width:100%;">2. CONFIRMER ET PAYER</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
