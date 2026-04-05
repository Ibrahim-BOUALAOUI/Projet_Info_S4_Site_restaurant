<?php
session_start();

if (!isset($_SESSION['connecte'])) {
    header("Location: index.php");
    exit();
}

$message =  "";


if (isset($_POST['nom'])) {
    $file = '../Folder_Data/utilisateur.json';
    $users = json_decode(file_get_contents($file), true);

    foreach ($users as &$user) {
        if ($user['email'] === $_SESSION['email']) {


            $user['name'] = $_POST['nom'];
            $user['last_name'] = $_POST['prenom'];
            $user['email'] = $_POST['email'];
            $user['adress'] = $_POST['adress'];
            $user['birthdate'] = $_POST['birthdate'];


            if (!empty($_POST['password'])) {
                $user['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }


            $_SESSION['nom']   = $_POST['nom'];
            $_SESSION['email'] = $_POST['email'];

            break;
        }
    }


    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $message = "✅ Informations mises à jour avec succès !";
}


$file = '../Folder_Data/utilisateur.json';
$users = json_decode(file_get_contents($file), true);
$userActuel = null;

foreach ($users as $user) {
    if ($user['email'] === $_SESSION['email']) {
        $userActuel = $user;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Folder CSS/Profil.css">
    <title>Modification mon Profil</title>
</head>

<body>
    <header>
        <a href="index.php">
            <img src="../Folder img/129.png" alt="Logo" width="200">
        </a>
    </header>

    <main class="rect-mid">
        <section class="informations">
            <h2 class="section-title">✏️ Modifier mes informations</h2>

            <?php if ($message): ?>
                <p class="message-succes"><?= $message ?></p>
            <?php endif; ?>

            <form method="POST" action="modifier_profil.php">

                <div class="info-item">
                    <label class="info-label">Nom :</label>
                    <input type="text" name="nom" class="input-modifier"
                        value="<?= htmlspecialchars($userActuel['name']) ?>" required>
                </div>

                <div class="info-item">
                    <label class="info-label">Prénom :</label>
                    <input type="text" name="prenom" class="input-modifier"
                        value="<?= htmlspecialchars($userActuel['last_name']) ?>" required>
                </div>

                <div class="info-item">
                    <label class="info-label"> Adresse : </label>
                    <input type="text" name="adress" class="input-modifier"
                        value="<?= htmlspecialchars($userActuel['adress']) ?>" required>
                </div>

                <div class="info-item">
                    <label class="info-label">Email :</label>
                    <input type="email" name="email" class="input-modifier"
                        value="<?= htmlspecialchars($userActuel['email']) ?>" required>
                </div>

                <div class="info-item">
                    <label class="info-label">Nouveau mot de passe :</label>
                    <input type="password" name="password" class="input-modifier" placeholder="Laisser vide pour ne pas changer">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-modifier">💾 Sauvegarder</button>
                    <a href="Profil.php" class="btn-annuler">Annuler</a>
                </div>

            </form>
        </section>
    </main>
</body>

</html>