<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['panier'])) {
    header("Location: panier.php");
    exit();
}

$action = $_POST['action_quantite'] ?? '';
$nom = $_POST['nom'] ?? '';
$prix = $_POST['prix'] ?? '';

if ($nom === '' || $prix === '') {
    header("Location: panier.php");
    exit();
}

if ($action === 'plus') {
    $_SESSION['panier'][] = [
        "nom" => $nom,
        "prix" => $prix
    ];
}

if ($action === 'moins') {
    for ($i = count($_SESSION['panier']) - 1; $i >= 0; $i--) {
        $memeNom = ($_SESSION['panier'][$i]['nom'] ?? '') === $nom;
        $memePrix = (string)($_SESSION['panier'][$i]['prix'] ?? '') === (string)$prix;

        if ($memeNom && $memePrix) {
            unset($_SESSION['panier'][$i]);
            $_SESSION['panier'] = array_values($_SESSION['panier']);
            break;
        }
    }
}

header("Location: panier.php");
exit();
?>
