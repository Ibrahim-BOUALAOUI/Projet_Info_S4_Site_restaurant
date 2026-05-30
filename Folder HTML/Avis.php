<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

$idCmd = $_GET['commande_id'] ?? '';

if ($idCmd === '') {
    header("Location: Choisir_avis.php");
    exit();
}

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

$commandes = json_decode(file_get_contents('../Folder_Data/commandes.json'), true) ?? [];
$avisData = file_exists('../Folder_Data/avis.json')
    ? json_decode(file_get_contents('../Folder_Data/avis.json'), true)
    : [];

if (!is_array($avisData)) {
    $avisData = [];
}

$dejaNotee = false;
foreach ($avisData as $avis) {
    if (
        (string)($avis['commande_id'] ?? '') === $idCmd &&
        (($avis['client'] ?? $avis['email'] ?? '') === $_SESSION['email'])
    ) {
        $dejaNotee = true;
        break;
    }
}

$commandeNotable = false;
foreach ($commandes as $cmd) {
    if (
        (string)($cmd['id'] ?? '') === $idCmd &&
        ($cmd['client'] ?? '') === $_SESSION['email'] &&
        ($cmd['statut'] ?? '') === 'livree' &&
        ($cmd['type_commande'] ?? 'livraison') === 'livraison' &&
        empty($cmd['deja_note']) &&
        !$dejaNotee
    ) {
        $commandeNotable = true;
        break;
    }
}

if (!$commandeNotable) {
    header("Location: Choisir_avis.php?msg=Commande non notifiable ou déjà notée");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donner mon avis - Le 129</title>

    <link rel="stylesheet" href="../Folder CSS/Avis.css">
    <link rel="stylesheet" id="style-sombre" href="">

    <script src="../Folder_JS/affichage.js" defer></script>
</head>
<body>
    <header>
        <a href="index.php">
            <img src="../Folder img/129.png" alt="Logo" width="250">
        </a>
    </header>

    <section class="avis-form">
        <div class="card">
            <h2>Donnez votre avis</h2>
            <p class="subtitle">Votre expérience compte pour nous.</p>

            <form action="traitement_avis.php" method="POST">
                <input type="hidden" name="commande_id" value="<?= htmlspecialchars($idCmd) ?>">

                <div class="form-group">
                    <label class="label-title">Votre note du plat</label>
                    <div class="rating">
                        <input value="5" name="rate_plat" id="star5" type="radio"><label title="5 étoiles" for="star5"></label>
                        <input value="4" name="rate_plat" id="star4" type="radio"><label title="4 étoiles" for="star4"></label>
                        <input value="3" name="rate_plat" id="star3" type="radio" checked><label title="3 étoiles" for="star3"></label>
                        <input value="2" name="rate_plat" id="star2" type="radio"><label title="2 étoiles" for="star2"></label>
                        <input value="1" name="rate_plat" id="star1" type="radio"><label title="1 étoile" for="star1"></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label-title">Note de la livraison</label>
                    <div class="livraison-rating">
                        <?php for($i=1; $i<=5; $i++): ?>
                        <div class="radio-option">
                            <input type="radio" name="rate_livraison" id="liv<?= $i ?>" value="<?= $i ?>" <?= ($i === 3) ? 'checked' : '' ?>>
                            <label for="liv<?= $i ?>"><?= $i ?></label>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label-title" for="commentaire">Votre avis</label>
                    <textarea id="commentaire" name="commentaire" placeholder="Décrivez votre expérience..."></textarea>
                </div>

                <div class="form-group">
                    <label class="label-title" for="prenom">Prénom (facultatif)</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Ex: Anthony">
                </div>

                <button class="btn" type="submit">Envoyer mon avis</button> 
            </form>
        </div>
    </section>
</body>
</html>
