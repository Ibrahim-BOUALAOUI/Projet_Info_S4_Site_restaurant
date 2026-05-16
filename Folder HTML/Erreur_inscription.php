<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" id="style-sombre" href="">
    <script src="../Folder_JS/affichage.js" defer></script>
    <meta charset="UTF-8">
    <title>Erreur d'inscription</title>
    <link rel="stylesheet" href="../folder CSS/Erreur.css">

</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            <h2 class="form-title">OUPS !</h2>

            <?php if (isset($_SESSION['message_erreur'])): ?>
                <div class="error-box">
                    <?php foreach ($_SESSION['message_erreur'] as $erreur): ?>
                        <p> <?php echo $erreur; ?> </p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['message_erreur']); ?>
            <?php else: ?>
                <p>Aucune erreur trouvée.</p>
            <?php endif; ?>


            <a href="inscription.php" class="main-btn" style="text-decoration: none; display: block; text-align: center;">
                RÉESSAYER
            </a>
        </div>
    </div>

</body>

</html>
</body>