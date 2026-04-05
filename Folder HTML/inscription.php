<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];


    $birthdate = $_POST['birthdate'];
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $adress = $_POST['adress'];
    $id = bin2hex(random_bytes(16));
    $date_actuelle = date("d/m/Y à H:i");

    $file = "../Folder_Data/utilisateur.json";
    $users = [];
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $users = json_decode($json, true) ?? [];
    }


    for ($i = 0; $i < count($users); $i++) {
        if ($users[$i]['email'] === $email) {
            $errors[] = "Cet email est déjà utilisé par un autre compte.";
            break;
        }
    }

    $Year = date('Y', strtotime($birthdate));
    if ($Year < 1900 || $Year > 2026) {
        $errors[] = "Date de naissance incorrecte.";
    }
    if (strpos($email, '@') === false) {
        $errors[] = "L'email doit contenir un @.";
    }
    if (preg_match('/[0-9]/', $name) || preg_match('/[0-9]/', $last_name)) {
        $errors[] = "Le nom/prénom ne doit pas contenir de chiffres.";
    }
    if (strlen($adress) < 5) {
        $errors[] = "L'adresse est trop courte.";
    }


    if (!empty($errors)) {
        $_SESSION['message_erreur'] = $errors;
        header("Location: Erreur_inscription.php");
        exit();
    } else {



        $newUser = [
            "id" => $id,
            "last_connection" => $date_actuelle,
            "birthdate" => $birthdate,
            "name" => $name,
            "last_name" => $last_name,
            "email" => $email,
            "phone" => $phone,
            "adress" => $adress,
            "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
            "type" => 'client',
            "connected" => true,
        ];


        $users[] = $newUser;
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

        header("Location: connexion.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../folder CSS/connexion.css">
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-box">
                <img src="../folder img/129.png" alt="Logo Le 129" class="site-logo">


                <form method="POST">
                    <h2 class="form-title">VOS INFORMATIONS</h2>

                    <p class="subtitle">Pour personnaliser votre expérience sur Le 129.</p>
                    <div class="input-group">
                        <label for="birthdate">DATE DE NAISSANCE</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>

                    <div class="input-group">
                        <label for="prenom">PRÉNOM</label>
                        <input type="text" id="name" name="name" placeholder="Votre prénom" required>
                    </div>

                    <div class="input-group">
                        <label for="nom">NOM</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Votre nom" required>
                    </div>

                    <div class="input-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" placeholder="nom@exemple.com" required>
                    </div>

                    <div class="input-group">
                        <label for="numéro">Numéro de téléphone</label>
                        <input type="phone" id="phone" name="phone" placeholder="votre numéro" required>
                    </div>

                    <div class="input-group">
                        <label for="adresse">Adresse</label>
                        <input type="adress" id="adress" name="adress" placeholder="Votre adresse" required>
                    </div>

                    <div class="input-group">
                        <label for="password">MOT DE PASSE</label>
                        <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                    </div>


                    <button type="submit" class="main-btn">CREER MON COMPTE</button>
                </form>
            </div>
        </div>
</body>


</html>