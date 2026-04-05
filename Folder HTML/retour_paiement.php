<?php
session_start();

$status = $_GET['status'] ?? 'error';
$id_trans = $_GET['transaction'] ?? 'Inconnu';

if ($status == 'accepted' && !empty($_SESSION['panier'])) {
    // 1. CHERCHER L'ADRESSE
    $email = $_SESSION['email'] ?? "Client Invité";
    $adr = "Non renseignée";
    $users = json_decode(file_get_contents('../Folder_Data/utilisateur.json'), true) ?? [];
    foreach ($users as $u) {
        if ($u['email'] === $email) {
            $adr = $u['adress'];
            break;
        }
    }


    $cmd = [
        "id" => $id_trans,
        "client" => $email,
        "adresse" => $adr,
        "articles" => $_SESSION['panier'],
        "statut" => "a preparer",
        "date" => date("Y-m-d H:i:s")
    ];

    $file = '../Folder_Data/commandes.json';
    $all_cmds = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $all_cmds[] = $cmd;
    file_put_contents($file, json_encode($all_cmds, JSON_PRETTY_PRINT));


    $_SESSION['panier'] = array();
    $message = "✅ Paiement accepté ! Commande $id_trans enregistrée.";
    $css = "success";
} else {
    $message = "❌ Paiement refusé (Status : $status).";
    $css = "error";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <title>Résultat du paiement</title>
    <style>
        .result-container {
            text-align: center;
            margin-top: 100px;
            font-family: sans-serif;
        }

        .success {
            color: #2ecc71;
        }

        .error {
            color: #e74c3c;
        }

        .btn-home {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #FE9301;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="result-container">
        <h1 class="<?= $css ?>"><?= $message ?></h1>
        <a href="index.php" class="btn-home">Retour à l'accueil</a>
    </div>
</body>

</html>