<?php
session_start();

$fichier = '../Folder_Data/commandes.json';
$commandes = [];
if (file_exists($fichier)) {
    $json = file_get_contents($fichier);
    $commandes = json_decode($json, true) ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cmd'])) {
    $id_a_modifier = $_POST['id_cmd'];

    $nouveau_statut = isset($_POST['action_livrer']) ? "livree" : "abandonnee";

    for ($i = 0; $i < count($commandes); $i++) {
        if ($commandes[$i]['id'] == $id_a_modifier) {
            $commandes[$i]['statut'] = $nouveau_statut;
            break;
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
    $vide = true;

    for ($i = 0; $i < $nb_total; $i++) {

        if ($commandes[$i]['statut'] === "en cours de livraison") {
            $vide = false; ?>

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
                        <p><strong>Adresse :</strong> <span style="color: #f4c542;"><?php echo htmlspecialchars($commandes[$i]['adresse']); ?></span></p>
                    </div>
                    <!-- FORMULAIRE UNIQUE POUR LES DEUX ACTIONS -->
                    <form method="POST" style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
                        <input type="hidden" name="id_cmd" value="<?php echo $commandes[$i]['id']; ?>">


                        <button type="submit" name="action_livrer" class="btn-final">✔ LIVRAISON TERMINÉE</button>


                        <button type="submit" name="action_abandon"
                            style="background-color: #e74c3c; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; font-weight: bold;"
                            onclick="return confirm('Confirmer l\'abandon ? (Adresse introuvable)')">
                            ✖ ABANDONNER (ADRESSE INTROUVABLE)
                        </button>
                    </form>
                </div>
            </section>

    <?php }
    }

    if ($vide) {
        echo "<p style='text-align:center; color:white; margin-top:50px;'>Aucune course en attente.</p>";
    }
    ?>
</body>

</html>