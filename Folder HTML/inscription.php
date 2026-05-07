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
    $date_actuelle = date("d/m/Y  H:i");

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
    <link rel="stylesheet" href="../Folder CSS/connexion.css">
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-box">
                <img src="../folder CSS/129.png" alt="Logo Le 129" class="site-logo">

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

                    <button type="submit" class="main-btn">CREER MON COMPTE</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validerFormulaire() {
            const birthdate = document.getElementById('birthdate').value;
            const first_name = document.getElementById('name').value.trim();
            const last_name = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const address = document.getElementById('address').value.trim();
            const password = document.getElementById('password').value.trim();
            const erreurDiv = document.getElementById('erreur-client');

            const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const regexTelephone = /^0[1-9]\d{8}$/;

            let message = '';

            if (!birthdate) {
                message = "La date de naissance est obligatoire.";
            } else {
                const year = new Date(birthdate).getFullYear();
                if (year < 1900) {
                    message = "Date de naissance incorrecte.";
                }
            }

            if (!message && !first_name) {
                message = "Le prénom est obligatoire.";
            } else if (!message && !last_name) {
                message = "Le nom est obligatoire.";
            } else if (!message && !email) {
                message = "L'adresse e-mail est obligatoire.";
            } else if (!message && !regexEmail.test(email)) {
                message = "L'adresse e-mail n'est pas valide.";
            } else if (!message && !regexTelephone.test(phone)) {
                message = "Le numéro est invalide (ex: 0612345678).";
            } else if (!message && !address) {
                message = "L'adresse est obligatoire.";
            } else if (!message && !password) {
                message = "Le mot de passe est obligatoire.";
            }

            if (message) {
                erreurDiv.textContent = message;
                erreurDiv.style.display = 'block';
                return false;
            }

            erreurDiv.style.display = 'none';
            return true;
        }
    </script>
</body>

</html>