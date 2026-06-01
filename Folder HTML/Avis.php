    <?php
    // On démarre la session pour identifier le client qui veut laisser un avis.
    session_start();

    // Un client non connecté ne peut pas accéder au formulaire d'avis.
    if (!isset($_SESSION['email'])) {
        header("Location: connexion.php");
        exit();
    }

    // L'id de commande vient du lien choisi dans Choisir_avis.php.
    $idCmd = $_GET['commande_id'] ?? '';

    if ($idCmd === '') {
        header("Location: Choisir_avis.php");
        exit();
    }

    $json_users = file_get_contents("../Folder_Data/ofdbisqfsqf.json");
    $users = json_decode($json_users, true);

    // Si le compte est bloqué, on détruit la session et on renvoie vers la connexion.
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

    $commandes = json_decode(file_get_contents('../Folder_Data/dfsqfiqsoifsvquvfipqf.json'), true) ?? [];
    $avisData = file_exists('../Folder_Data/avis.json')
        ? json_decode(file_get_contents('../Folder_Data/avis.json'), true)
        : [];

    // Si le fichier d'avis est vide ou mal lu, on repart avec un tableau vide.
    if (!is_array($avisData)) {
        $avisData = [];
    }

    $dejaNotee = false;
    // On vérifie si ce client a déjà noté cette commande dans avis.json.
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
    // La commande est notifiable seulement si elle appartient au client, est livrée, et n'a pas déjà été notée.
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

    // Si une des conditions échoue, le client retourne au choix des commandes à noter.
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
                        <?php // On garde l'id de la commande pour que traitement_avis.php sache quel avis enregistrer. ?>
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
                                <?php // Boucle qui génère automatiquement les notes de livraison de 1 à 5. ?>
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
