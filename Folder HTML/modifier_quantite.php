<?php
// Ce fichier traite les boutons + et - du panier.
session_start();

// On accepte uniquement les formulaires POST et seulement si le panier existe.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['panier'])) {
    header("Location: panier.php");
    exit();
}

// Données de l'article concerné par le changement de quantité.
$action = $_POST['action_quantite'] ?? '';
$nom = $_POST['nom'] ?? '';
$prix = $_POST['prix'] ?? '';

// Si les informations de l'article manquent, on retourne au panier sans modifier.
if ($nom === '' || $prix === '') {
    header("Location: panier.php");
    exit();
}

// Le bouton + ajoute un nouvel exemplaire du même article.
if ($action === 'plus') {
    $_SESSION['panier'][] = [
        "nom" => $nom,
        "prix" => $prix
    ];
}

// Le bouton - retire un seul exemplaire correspondant au nom et au prix.
if ($action === 'moins') {
    for ($i = count($_SESSION['panier']) - 1; $i >= 0; $i--) {
        $memeNom = ($_SESSION['panier'][$i]['nom'] ?? '') === $nom;
        $memePrix = (string)($_SESSION['panier'][$i]['prix'] ?? '') === (string)$prix;

        if ($memeNom && $memePrix) {
            unset($_SESSION['panier'][$i]);
            // On réindexe le tableau pour éviter les trous après suppression.
            $_SESSION['panier'] = array_values($_SESSION['panier']);
            break;
        }
    }
}

// Après la modification, on revient toujours sur le panier.
header("Location: panier.php");
exit();
?>
