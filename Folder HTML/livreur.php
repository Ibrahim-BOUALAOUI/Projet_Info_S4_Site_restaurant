<?php
session_start();


$fichier = '../Folder_Data/commandes.json';
$commandes = [];
if (file_exists($fichier)) {
    $json = file_get_contents($fichier);
    $commandes = json_decode($json, true) ?? [];
}


if (isset($_POST['id_livraison_fin'])) {
    $id_a_modifier = $_POST['id_livraison_fin'];
    for ($i = 0; $i < count($commandes); $i++) {
        if ($commandes[$i]['id'] == $id_a_modifier) {
            $commandes[$i]['statut'] = "livrée";
        }
    }
    file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT));
    header("Location: livreur.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Livraison - Le 129</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Folder CSS/livreur.css">
</head>

<body>
    <header class="top_bar">
        <img src="../Folder img/129.png" alt="logo" class="logo-admin" width="200">
    </header>

    <?php
    $nb_total = count($commandes);
    for ($i = 0; $i < $nb_total; $i++) {

        if ($commandes[$i]['statut'] === "en cours de livraison") { ?>

            <section class="delivery-card">
                <h2 class="order-id">Commande #<?php echo $commandes[$i]['id']; ?></h2>

                <div class="info">
                    <div class="delivery-info">
                        <p><strong>Contenu :</strong>
                            <?php
                            $articles = $commandes[$i]['articles'];
                            for ($j = 0; $j < count($articles); $j++) {
                                echo htmlspecialchars($articles[$j]['nom']);
                                if ($j < count($articles) - 1) echo ", ";
                            }
                            ?>
                        </p>
                    </div>

                    <div class="info-groupe">
                        <p><strong>Client :</strong> <?php echo htmlspecialchars($commandes[$i]['client']); ?></p>

                        <p><strong>Adresse de livraison :</strong>
                            <span style="color: #f4c542;">
                                <?php echo htmlspecialchars($commandes[$i]['adresse']); ?>
                            </span>
                        </p>
                    </div>

                    <form method="POST" style="margin-top: 20px;">
                        <input type="hidden" name="id_livraison_fin" value="<?php echo $commandes[$i]['id']; ?>">
                        <button type="submit" class="btn-final">✔ LIVRAISON TERMINÉE</button>
                    </form>
                </div>
            </section>

    <?php }
    } ?>

    <?php

    $vide = true;
    for ($i = 0; $i < count($commandes); $i++) {
        if ($commandes[$i]['statut'] === "en cours de livraison") $vide = false;
    }
    if ($vide) echo "<p style='text-align:center; color:white; margin-top:50px;'>Aucune course en attente.</p>";
    ?>
</body>

</html>