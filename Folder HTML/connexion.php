<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $file = '../Folder_Data/utilisateur.json';
        $errors = [];

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $users = json_decode($json, true) ?? array();
            $userFound = false;

            for ($i = 0; $i < count($users); $i++) {
                if ($users[$i]['email'] === $_POST['email']) {
                    $userFound = true;
                    if (password_verify($_POST['password'], $users[$i]['password'])) {
                        $_SESSION['connecte'] = true;
                        $_SESSION['nom'] = $users[$i]['name'];
                        $_SESSION['email'] = $users[$i]['email'];
                        $_SESSION['type'] = $users[$i]['type'];
                        $_SESSION['permission'] = $users[$i]['permission'];
                        $_SESSION['adress'] = $users[$i]['adress'];

                        $userId = $users[$i]['id'];
                        $lastConn = $users[$i]['last_connection'];
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
            $_SESSION['message_erreur'] = $errors;
            header("Location: erreur_connexion.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le 129 | Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../folder CSS/connexion.css">
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="logo-box">
                <img src="../Folder img/129.png" alt="Logo Le 129" class="site-logo">
            </div>

            <form method="POST">
                <h2 id="form-title">SE CONNECTER</h2>

                <div class="input-group">
                    <label for="email">ADRESSE E-MAIL</label>
                    <input type="email" name="email" id="email" placeholder="nom@exemple.com" required>
                </div>

                <div class="input-group">
                    <label for="password">MOT DE PASSE</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>

                <div class="form-helper">
                    <label class="checkbox-label">
                        <input type="checkbox"> Se souvenir de moi
                    </label>
                    <a href="#">Mot de passe oublié ?</a>
                </div>


                <button type="submit" class="main-btn">SE CONNECTER MAINTENANT</button>
            </form>
            <p class="toggle-text">Vous n'avez pas de compte ? <a href="inscription.php">S'inscrire</a></p>
        </div>
    </div>
</body>




</html>