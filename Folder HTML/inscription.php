<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Utilisation de trim() pour éviter que l'utilisateur mette juste des espaces
    $birthdate = trim($_POST['birthdate'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $adress = trim($_POST['adress'] ?? '');
    $password = $_POST['password'] ?? '';

    $id = bin2hex(random_bytes(16));
    $date_actuelle = date("d/m/Y  H:i");
    $bloquee = false;

    // Sécurité PHP au cas où le JS est contourné
    if (empty($birthdate) || empty($name) || empty($last_name) || empty($email) || empty($phone) || empty($adress) || empty($password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

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
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "type" => 'client',
            "connected" => true,
            "permission" => 'User',
            "bloquee" => false,
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
    <title>Le 129 | Inscription</title>
    <link rel="stylesheet" href="../Folder CSS/connexion.css">
    <script src="../Folder_JS/affichage.js" defer></script>
    <script src="../Folder_JS/verification_inscription.js" defer></script>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">

            <div class="logo-box">
                <img src="../folder img/129.png" alt="Logo Le 129" class="site-logo">
            </div>

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

            <form method="POST" novalidate onsubmit="return validerFormulaire()">
                <h2 class="form-title">VOS INFORMATIONS</h2>
                <p class="subtitle">Pour personnaliser votre expérience sur Le 129.</p>

                <div class="input-group">
                    <label for="birthdate">DATE DE NAISSANCE</label>
                    <input type="date" id="birthdate" name="birthdate">
                </div>

                <div class="input-group">
                    <label for="name">PRÉNOM</label>
                    <input type="text" id="name" name="name" placeholder="Votre prénom">
                </div>

                <div class="input-group">
                    <label for="last_name">NOM</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Votre nom">
                </div>

                <div class="input-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" placeholder="nom@exemple.com">
                </div>

                <div class="input-group">
                    <label for="phone">Numéro de téléphone</label>
                    <input type="text" id="phone" name="phone" placeholder="votre numéro">
                </div>

                <div class="input-group">
                    <label for="address">Adresse</label>
                    <input type="text" id="address" name="adress" placeholder="Votre adresse">
                </div>

                <div class="input-group">
                    <label for="password">MOT DE PASSE</label>
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe">
                </div>

                <button type="submit" class="main-btn">CREER MON COMPTE</button>
            </form>

        </div>
    </div>
</body>

</html>