<?php
session_start();
$id = $_GET['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suivi de commande</title>
    <link rel="stylesheet" href="../Folder CSS/suivi.css">
    <script src="../Folder_JS/suivi.js" defer></script>
</head>
<body>
    <header class="top_bar">
        <a href="index.php"><img src="../Folder img/129.png" alt="Logo" width="200"></a>
    </header>

    <div class="suivi-container">
        <h1>🛵 Suivi de votre commande</h1>
        <p class="suivi-id">Commande n° <strong><?= htmlspecialchars($id) ?></strong></p>

        <!-- La barre de progression : on stocke l'id dans data-id -->
        <div class="suivi-etapes" id="suivi-etapes" data-id="<?= htmlspecialchars($id) ?>">
            <div class="etape" data-statut="a preparer">
                <span class="etape-icone">👨‍🍳</span>
                <span class="etape-texte">À préparer</span>
            </div>
            <div class="etape" data-statut="en cours de livraison">
                <span class="etape-icone">🛵</span>
                <span class="etape-texte">En livraison</span>
            </div>
            <div class="etape" data-statut="livree">
                <span class="etape-icone">✅</span>
                <span class="etape-texte">Livrée</span>
            </div>
        </div>

        <p class="suivi-message" id="suivi-message">Chargement...</p>
    </div>
</body>
</html>