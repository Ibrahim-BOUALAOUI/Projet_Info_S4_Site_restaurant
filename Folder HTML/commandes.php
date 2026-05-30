<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

if (($_SESSION['permission'] ?? '') !== 'preparateur') {
    header("Location: index.php?erreur=acces_refuse");
    exit();
}

$fichier = '../Folder_Data/commandes.json';
$json = file_get_contents($fichier);
$commandes = json_decode($json, true) ?? array();

// Gestion du blocage de la personne
$json_users = file_get_contents("../Folder_Data/utilisateur.json");
$users = json_decode($json_users, true);

foreach ($users as $user) {
    if (
        $user['email'] === $_SESSION['email'] &&
        (!empty($user['bloque']) || !empty($user['bloquee']))
    ) {
        session_destroy();
        header("Location: connexion.php?erreur=bloque");
        exit;
    }
}

if (isset($_POST['id_prete'])) {
    $id_cmd = $_POST['id_prete'];
    for ($i = 0; $i < count($commandes); $i++) {
        if ($commandes[$i]['id'] == $id_cmd) {
            $commandes[$i]['statut'] = "en cours de livraison";
            $commandes[$i]['livreur'] = "";
            break;
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
    <title>Cuisine - Commandes</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Folder CSS/commandes.css">
</head>

<body>
    <header>
        <img src="../Folder img/129.png" width="200">
    </header>


    <section class="board_orders">
        <h2 class="Bungee_orders">À Préparer</h2>
        <?php
        for ($i = 0; $i < count($commandes); $i++) {
            if ($commandes[$i]['statut'] === "a preparer") { ?>
                <div class="card_orders">
                    <div class="card-info">
                        <span class="order-id_orders">Commande #<?php echo $commandes[$i]['id']; ?></span>

                        <p style="color: #FE9301; font-weight: bold; margin: 5px 0;">
                            <?php
                            $h = isset($commandes[$i]['heure_prevue']) ? $commandes[$i]['heure_prevue'] : "Immédiat";
                            echo ($h === "Immédiat") ? "🚀 Immédiat" : "🕒 Pour : " . htmlspecialchars($h);
                            ?>
                        </p>

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
                        <button type="submit" class="btn-action_orders">Prête / Donner au livreur</button>
                    </form>
                </div>
        <?php }
        } ?>
    </section>


    <section class="board_deliveries">
        <h2 class="Bungee_deliveries">En Livraison (Parti de la cuisine)</h2>
        <?php
        for ($i = 0; $i < count($commandes); $i++) {

            if ($commandes[$i]['statut'] === "en cours de livraison") { ?>
                <div class="card_deliveries">
                    <div class="card-info">
                        <span class="order-id_deliveries">Commande #<?php echo $commandes[$i]['id']; ?></span>
                        <p>
                            Livreur :
                            <?php if (!empty($commandes[$i]['livreur'])): ?>
                                <?php echo htmlspecialchars($commandes[$i]['livreur']); ?>
                            <?php else: ?>
                                En attente d'un livreur
                            <?php endif; ?>
                        </p>
                    </div>
                    <span class="status-label">EN ROUTE...</span>
                </div>
        <?php }
        } ?>
    </section>

</body>

</html>
