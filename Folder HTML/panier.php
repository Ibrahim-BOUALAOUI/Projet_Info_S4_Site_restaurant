<?php
session_start();
$panier = $_SESSION['panier'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
</head>

<body>
    <h1>🛒 Votre commande</h1>


    <?php if ($connecte): ?>
        <form action="valider_commande.php" method="POST">
            <button type="submit" class="Btn" style="background-color:green;">CONFIRMER LA COMMANDE</button>
        </form>
    <?php else: ?>
        <div style="background: #ffcccc; padding: 10px; border-radius: 5px; text-align: center;">
            <p>⚠️ Vous devez être connecté pour valider votre commande.</p>
            <a href="connection.php" class="Btn">Se connecter maintenant</a>
        </div>
    <?php endif; ?>

    <?php if (empty($panier)): ?>
        <p>Votre panier est vide. <a href="index.php">Retour au menu</a></p>
    <?php else: ?>
        <ul>
            <?php foreach ($panier as $index => $item): ?>
                <li>
                    <?php echo $item['nom']; ?> - <?php echo $item['prix']; ?> €
                    <a href="supprimer_item.php?index=<?php echo $index; ?>" style="color:red;">[Supprimer]</a>
                </li>
                <?php $total += $item['prix']; ?>
            <?php endforeach; ?>
        </ul>

        <h3>Total : <?php echo number_format($total, 2); ?> €</h3>

        <!-- LE BOUTON CRUCIAL -->
        <form action="valider_commande.php" method="POST">
            <button type="submit" class="Btn" style="background-color:green; color:white; padding:15px;">
                CONFIRMER ET ENVOYER LA COMMANDE
            </button>
        </form>
    <?php endif; ?>
</body>

</html>