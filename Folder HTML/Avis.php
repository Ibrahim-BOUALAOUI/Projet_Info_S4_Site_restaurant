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
                <input type="hidden" name="commande_id" value="<?= htmlspecialchars($_GET['commande_id'] ?? '') ?>">

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