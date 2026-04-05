<?php
session_start();
$fichier = '../Folder_Data/commandes.json';
$json = file_get_contents($fichier);
$commandes = json_decode($json, true) ?? [];


if (isset($_POST['id_prete'])) {
    $id_cmd = $_POST['id_prete'];
    for ($i = 0; $i < count($commandes); $i++) {
        if ($commandes[$i]['id'] == $id_cmd) {
            $commandes[$i]['statut'] = "en cours de livraison";
        }
    }
    file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT));
    header("Location: commandes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Commandes</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../FOLDER CSS/commandes.css">
</head>

<body>
    <header>
        <img src="../Folder img/129.png" alt="Logo" class="logo-admin" width="200">
    </header>

    <section class="board_orders">
        <h2 class="Bungee_orders">À Préparer</h2>

        <?php

        for ($i = 0; $i < count($commandes); $i++) {
            if ($commandes[$i]['statut'] === "a preparer") { ?>
                <div class="card_orders">
                    <div class="card-info">
                        <span class="order-id_orders">Commande #<?php echo $commandes[$i]['id']; ?></span>
                        <p>
                            <?php

                            $articles = $commandes[$i]['articles'];
                            for ($j = 0; $j < count($articles); $j++) {
                                echo htmlspecialchars($articles[$j]['nom']) . ($j < count($articles) - 1 ? ", " : "");
                            }
                            ?>
                        </p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="id_prete" value="<?php echo $commandes[$i]['id']; ?>">
                        <button type="submit" class="btn-action_orders">Prete / Livraison</button>
                    </form>
                </div>
        <?php }
        } ?>
    </section>

    <section class="board_deliveries">
        <h2 class="Bungee_deliveries">En Livraison</h2>

        <?php

        for ($i = 0; $i < count($commandes); $i++) {
            if ($commandes[$i]['statut'] === "en cours de livraison") { ?>
                <div class="card_deliveries">
                    <div class="card-info">
                        <span class="order-id_deliveries">Commande #<?php echo $commandes[$i]['id']; ?></span>
                        <p>Livreur : En route[cite: 12]</p>
                    </div>
                    <span class="status-label">En cours...</span>
                </div>
        <?php }
        } ?>
    </section>
</body>

</html>