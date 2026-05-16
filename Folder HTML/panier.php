<?php
session_start();
require('getapikey.php');

if (isset($_POST['fixer_moment'])) {
    $mode = $_POST['mode_retrait'];
    $heure = isset($_POST['heure_retrait']) ? $_POST['heure_retrait'] : '';
    $_SESSION['choix_heure'] = ($mode === "Plus tard" && !empty($heure)) ? $heure : "Immédiat";
}

if (!isset($_SESSION['choix_heure'])) {
    $_SESSION['choix_heure'] = "Immédiat";
}

$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : array();

$total_general = 0;
for ($i = 0; $i < count($panier); $i++) {
    $total_general += $panier[$i]['prix'];
}
$montant_formatte = number_format($total_general, 2, '.', '');

$vendeur = "MI-1_A";
$api_key = getAPIKey($vendeur);
$transaction = "TX" . time();
$url_retour = "http://localhost:8888/Folder HTML/retour_paiement.php";

$phrase = $api_key . "#" . $transaction . "#" . $montant_formatte . "#" . $vendeur . "#" . $url_retour . "#";
$control = md5($phrase);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" id="style-sombre" href="">
    <script src="../Folder_JS/affichage.js" defer></script>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <link rel="stylesheet" href="../Folder CSS/panier.css">
</head>

<body>
    <div class="cart-container">
        <div class="cart-items">
            <h1 class="cart-title">🛒 Votre commande</h1>
            <?php if (count($panier) === 0) : ?>
                <p>Votre panier est vide.</p>
            <?php else : ?>
                <?php for ($i = 0; $i < count($panier); $i++) : ?>
                    <div class="cart-card">
                        <h3><?php echo htmlspecialchars($panier[$i]['nom']); ?></h3>
                        <p><?php echo number_format($panier[$i]['prix'], 2); ?> €</p>
                        <a href="supprimer_item.php?index=<?php echo $i; ?>">Supprimer</a>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>

        <?php if (count($panier) > 0) : ?>
            <div class="cart-summary">
                <h2 class="summary-title">Résumé</h2>

                <div style="margin: 20px 0; padding: 15px; border: 1px solid #FE9301; border-radius: 8px; background: rgba(254, 147, 1, 0.1);">
                    <form method="POST">
                        <p style="color: #FE9301; font-weight: bold; margin-bottom: 10px;">🕒 1. Régler l'heure de retrait :</p>

                        <input type="radio" name="mode_retrait" value="Immédiat" id="imm" <?php if ($_SESSION['choix_heure'] == "Immédiat") echo "checked"; ?>>
                        <label for="imm"> Immédiat</label><br>

                        <input type="radio" name="mode_retrait" value="Plus tard" id="tard" <?php if ($_SESSION['choix_heure'] != "Immédiat") echo "checked"; ?>>
                        <label for="tard"> À cette heure :</label>
                        <input type="time" name="heure_retrait" value="<?php echo ($_SESSION['choix_heure'] != "Immédiat") ? $_SESSION['choix_heure'] : ""; ?>" style="background:#222; color:white; border:1px solid #444;">

                        <button type="submit" name="fixer_moment" style="display:block; margin-top:10px; background:#444; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:3px;">
                            Valider l'horaire
                        </button>
                    </form>
                </div>

                <p>Retrait prévu : <strong><?php echo $_SESSION['choix_heure']; ?></strong></p>
                <p>Total à payer : <strong><?php echo $montant_formatte; ?> €</strong></p>

                <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST' style="margin-top: 20px;">
                    <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
                    <input type='hidden' name='montant' value='<?php echo $montant_formatte; ?>'>
                    <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
                    <input type='hidden' name='retour' value='<?php echo $url_retour; ?>'>
                    <input type='hidden' name='control' value='<?php echo $control; ?>'>
                    <button type="submit" class="btn-confirm" style="width:100%;">2. CONFIRMER ET PAYER</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
