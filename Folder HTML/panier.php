<?php
session_start();
$panier = $_SESSION['panier'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier | Sandwicherie</title>

    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <link rel="stylesheet" href="../Folder CSS/Panier.css">
</head>

<body>

    <div class="cart-container">


        <div class="cart-items">
            <h1 class="cart-title">🛒 Votre commande</h1>

            <?php if (empty($panier)): ?>
                <div class="cart-card">
                    <p>Votre panier est vide. <a href="index.php" style="color: var(--orange-theme);">Retour au menu</a></p>
                </div>
            <?php else: ?>
                <?php foreach ($panier as $index => $item): ?>
                    <div class="cart-card">
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['nom']); ?></h3>
                            <p>Prix unitaire : <?php echo number_format($item['prix'], 2); ?> €</p>
                        </div>
                        <div style="text-align: right;">
                            <p style="font-weight: bold; margin-bottom: 10px; font-size: 1.2em;">
                                <?php echo number_format($item['prix'], 2); ?> €
                            </p>
                            <a href="supprimer_item.php?index=<?php echo $index; ?>" class="delete-link">Supprimer</a>
                        </div>
                    </div>
                    <?php $total += $item['prix']; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <?php if (!empty($panier)): ?>
            <div class="cart-summary">
                <h2 class="summary-title">Résumé</h2>
                <div style="display: flex; justify-content: space-between;">
                    <span>Articles (<?php echo count($panier); ?>)</span>
                    <span><?php echo number_format($total, 2); ?> €</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <span>Livraison</span>
                    <span style="color: var(--vert-succes);">Gratuite</span>
                </div>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">

                <span class="total-amount">Total : <?php echo number_format($total, 2); ?> €</span>

                <form action="valider_commande.php" method="POST">
                    <button type="submit" class="btn-confirm">
                        CONFIRMER LA COMMANDE
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