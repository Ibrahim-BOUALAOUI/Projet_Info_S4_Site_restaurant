<?php
session_start();

// 1. On récupère le "status" au lieu de "resultat"
$status = isset($_GET['status']) ? $_GET['status'] : 'error';
$id_trans = isset($_GET['transaction']) ? $_GET['transaction'] : 'Inconnu';

// 2. On vérifie si le status est "accepted"
if ($status == 'accepted') {
    // LE PAIEMENT EST RÉUSSI
    $_SESSION['panier'] = array(); // On vide le panier
    $message = "✅ Merci ! Votre paiement a été accepté (Commande : $id_trans).";
    $css_class = "success";
} else {
    // LE PAIEMENT A ÉCHOUÉ OU ÉTÉ ANNULÉ
    $message = "❌ Le paiement a été refusé ou annulé (Status : $status).";
    $css_class = "error";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat du paiement</title>
    <link rel="stylesheet" href="../Folder CSS/Sous_page.css">
    <style>
        .result-container { text-align: center; margin-top: 100px; font-family: sans-serif; }
        .success { color: #2ecc71; }
        .error { color: #e74c3c; }
        .btn-home { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #FE9301; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="result-container">
        <h1 class="<?php echo $css_class; ?>"><?php echo $message; ?></h1>
        <p>Vous allez recevoir un mail de confirmation prochainement.</p>
        <a href="index.php" class="btn-home">Retour à l'accueil</a>
    </div>
</body>
</html>