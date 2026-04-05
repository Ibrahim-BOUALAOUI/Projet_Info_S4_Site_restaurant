<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Erreur de Connexion</title>
    <link rel="stylesheet" href="../folder CSS/Erreur.css">
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-box">
                <img src="../Folder img/129.png" alt="Logo" class="site-logo" style="width:200px;">
            </div>

            <h2 class="form-title">ERREUR</h2>

            <?php if (isset($_SESSION['message_erreur'])): ?>
                <div class="error-box">
                    <?php
                    $errs = $_SESSION['message_erreur'];
                    for ($i = 0; $i < count($errs); $i++): ?>
                        <p> <?php echo htmlspecialchars($errs[$i]); ?> </p>
                    <?php endfor; ?>
                </div>
                <?php unset($_SESSION['message_erreur']); ?>
            <?php else: ?>
                <p>Une erreur inconnue est survenue.</p>
            <?php endif; ?>

            <a href="connexion.php" class="main-btn" style="text-decoration: none; display: block; text-align: center; margin-top: 20px;">
                RETOUR À LA CONNEXION
            </a>
        </div>
    </div>
</body>

</html>