<?php
// Start.php démarre la session et charge les informations communes du site.
require("../include/Start.php");

// Le traitement de connexion se lance uniquement quand le formulaire est envoyé.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $file = '../Folder_Data/ofdbisqfsqf.json';
        $errors = [];

        // On charge la liste des utilisateurs enregistrés dans le fichier JSON.
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $users = json_decode($json, true);
            if ($users === null) {
                $users = array();
            }
            $userFound = false;

            // On cherche un utilisateur dont l'email correspond à celui saisi.
            for ($i = 0; $i < count($users); $i++) {
                if ($users[$i]['email'] === $_POST['email']) {
                    $userFound = true;
                    // password_verify compare le mot de passe saisi avec le mot de passe hashé du JSON.
                    if (password_verify($_POST['password'], $users[$i]['password'])) {
                        $_SESSION['connecte'] = true;
                        $_SESSION['nom'] = $users[$i]['name'];
                        $_SESSION['email'] = $users[$i]['email'];
                        $_SESSION['type'] = $users[$i]['type'];
                        $_SESSION['permission'] = $users[$i]['permission'];
                        $_SESSION['adress'] = $users[$i]['adress'];
                        $userId = $users[$i]['id'];
                        $lastConn = $users[$i]['last_connection'];
                        // Les cookies servent à garder une trace du compte et de la dernière connexion.
                        setcookie("user_id", $userId, time() + (3600 * 24 * 365), "/");
                        setcookie("last_visit", $lastConn, time() + (3600 * 24 * 365), "/");
                        header("Location: index.php");
                        exit();
                    } else {
                        $errors[] = "Le mot de passe saisi est incorrect.";
                    }
                    break;
                }
            }

            if (!$userFound) {
                $errors[] = "Aucun compte n'est associé à cette adresse e-mail.";
            }
        }

        if (!empty($errors)) {
            // Les erreurs sont mises en session pour être affichées dans le HTML plus bas.
            $_SESSION['message_erreur'] = $errors;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" id="style-sombre" href="">
    <script src="../Folder_JS/affichage.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le 129 | Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Folder CSS/connexion.css">

    <script src="../Folder_JS/verification_connexion.js" defer></script>
</head>

<body>


    <div class="login-wrapper">
        <div class="login-card">

            <div class="logo-box">
                <a href="../Folder HTML/index.php"><img src="../Folder img/129.png" alt="Logo Le 129" class="site-logo"></a>
            </div>

            <?php // Si une erreur a été enregistrée en session, on l'affiche au-dessus du formulaire. ?>
            <?php if (isset($_SESSION['message_erreur'])): ?>
                <div id="erreur-client" class="erreur-msg">
                    <?php foreach ($_SESSION['message_erreur'] as $err): ?>
                        <p><?php echo htmlspecialchars($err); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['message_erreur']); ?>
            <?php else: ?>
                <div id="erreur-client" class="erreur-msg" style="display:none;"></div>
            <?php endif; ?>

            <form method="POST" id="login-form" onsubmit="return validerFormulaire()" novalidate>
                <h2 id="form-title">SE CONNECTER</h2>

                <div class="input-group">
                    <?php // Champ email. ?>
                    <label for="email">ADRESSE E-MAIL</label>
                    <input type="email" name="email" id="email" placeholder="nom@exemple.com" required>
                </div>

                <div class="input-group">
                    <?php // Champ mot de passe. ?>
                    <label for="password">MOT DE PASSE</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>

                    <label style="font-size: 11px; cursor: pointer; display: flex; align-items: center; gap: 8px; margin-top: 8px; font-weight: normal; text-transform: none; color: #aaa; width: 100%; max-width: 100%;">
                        <?php // Cette case permet d'afficher ou masquer le mot de passe. ?>
                        <input type="checkbox" onclick="if (this.checked) { document.getElementById('password').type = 'text'; } else { document.getElementById('password').type = 'password'; }" style="margin: 0; width: auto; height: auto; display: inline-block;"> Afficher le mot de passe
                    </label>
                </div>

                <div class="form-helper">
                    <label class="checkbox-label">
                        <input type="checkbox"> Se souvenir de moi
                    </label>
                    <a href="#">Mot de passe oublié ?</a>
                </div>

                <div id="erreur-client" class="erreur-msg" style="display:none;"></div>

                <button type="submit" class="main-btn">SE CONNECTER MAINTENANT</button>
            </form>
            <p class="toggle-text">Vous n'avez pas de compte ? <a href="inscription.php">S'inscrire</a></p>
        </div>
    </div>
</body>




</html>
