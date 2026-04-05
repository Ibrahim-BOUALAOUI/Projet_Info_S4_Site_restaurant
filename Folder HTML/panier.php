<?php
session_start();
require('getapikey.php');


$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : array();


$vendeur = "MI-1_A"; 
$api_key = getAPIKey($vendeur);
$transaction = "TX" . time();

$url_retour = "http://localhost:8888/Folder HTML/retour_paiement.php"; 


$total_general = 0;
foreach ($panier as $item) {
    $total_general += $item['prix'];
}
$montant_formatte = number_format($total_general, 2, '.', '');


$phrase = $api_key . "#" . $transaction . "#" . $montant_formatte . "#" . $vendeur . "#" . $url_retour . "#";
$control = md5($phrase);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier | Paiement Sécurisé</title>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <link rel="stylesheet" href="../Folder CSS/Panier.css">
</head>
<body>

    <div class="cart-container">
        
      
        <div class="cart-items">
            <h1 class="cart-title">🛒 Votre commande</h1>

            <?php if (empty($panier)) : ?>
                <div class="cart-card">
                    <p>Votre panier est vide. <a href="index.php" style="color: var(--orange-theme);">Retour au menu</a></p>
                </div>
            <?php else : ?>
                <?php foreach ($panier as $index => $item) : ?>
                    <div class="cart-card">
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['nom']); ?></h3>
                            <p>Prix : <?php echo number_format($item['prix'], 2); ?> €</p>
                        </div>

               

                        <div style="text-align: right; min-width: 100px;">
                            <p class="item-total-price">
                                <?php echo number_format($item['prix'], 2); ?> €
                            </p>
                            <a href="supprimer_item.php?index=<?php echo $index; ?>" class="delete-link">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

  
        <?php if (!empty($panier)) : ?>
        <div class="cart-summary">
            <h2 class="summary-title">Résumé</h2>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span>Nombre d'articles</span>
                <span><?php echo count($panier); ?></span>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span>Livraison</span>
                <span style="color: var(--vert-succes); font-weight: bold;">OFFERTE</span>
            </div>
            
            <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 200px 0;">
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 1.2em;">Total TTC</span>
                <span class="total-amount"><?php echo $montant_formatte; ?> €</span>
            </div>
            
           
            <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST' style="margin-top: 25px;">
                <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
                <input type='hidden' name='montant' value='<?php echo $montant_formatte; ?>'>
                <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
                <input type='hidden' name='retour' value='<?php echo $url_retour; ?>'>
                <input type='hidden' name='control' value='<?php echo $control; ?>'>

                <button type="submit" class="btn-confirm">
                    CONFIRMER ET PAYER
                </button>
            </form>
            
            <a href="index.php" class="back-link">
                ← Continuer mes achats
            </a>
        </div>
        <?php endif; ?>

    </div>

</body>
</html>