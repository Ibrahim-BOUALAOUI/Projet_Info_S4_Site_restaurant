<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

$commandes = json_decode(file_get_contents('../Folder_Data/dfsqfiqsoifsvquvfipqf.json'), true) ?? [];
$avisData = file_exists('../Folder_Data/avis.json')
    ? json_decode(file_get_contents('../Folder_Data/avis.json'), true)
    : [];

if (!is_array($avisData)) {
    $avisData = [];
}

$commandesDejaNotees = [];
foreach ($avisData as $avis) {
    if (($avis['client'] ?? $avis['email'] ?? '') === $_SESSION['email']) {
        $commandesDejaNotees[] = (string)($avis['commande_id'] ?? '');
    }
}

$commandesANoter = [];
foreach ($commandes as $cmd) {
    $idCommande = (string)($cmd['id'] ?? '');
    $estAuClient = ($cmd['client'] ?? '') === $_SESSION['email'];
    $estLivree = ($cmd['statut'] ?? '') === 'livree';
    $estLivraison = ($cmd['type_commande'] ?? 'livraison') === 'livraison';
    $dejaNotee = !empty($cmd['deja_note']) || in_array($idCommande, $commandesDejaNotees, true);

    if ($estAuClient && $estLivree && $estLivraison && !$dejaNotee) {
        $commandesANoter[] = $cmd;
    }
}

$commandesANoter = array_reverse($commandesANoter);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir une commande à noter - Le 129</title>

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
            <h2>Choisissez une commande</h2>
            <p class="subtitle">Seules les commandes livrées et non notées sont affichées.</p>

            <?php if (empty($commandesANoter)): ?>
                <p>Aucune commande livrée à noter pour le moment.</p>
                <a href="Profil.php" class="btn">Retour au profil</a>
            <?php else: ?>
                <?php foreach ($commandesANoter as $cmd): ?>
                    <?php
                    $totalPrix = 0;
                    foreach (($cmd['articles'] ?? []) as $article) {
                        $totalPrix += (float)($article['prix'] ?? 0);
                    }
                    ?>
                    <div class="form-group" style="border-bottom: 1px solid #444; padding-bottom: 15px;">
                        <p><strong>Commande #<?= htmlspecialchars($cmd['id']) ?></strong></p>
                        <p><?= htmlspecialchars($cmd['date'] ?? '') ?></p>
                        <p><?= number_format($totalPrix, 2, ',', ' ') ?> €</p>
                        <a href="Avis.php?commande_id=<?= urlencode($cmd['id']) ?>" class="btn">Noter cette commande</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
