<?php
session_start();


if (isset($_GET['index']) && isset($_SESSION['panier'])) {
    $index = $_GET['index'];

    if (isset($_SESSION['panier'][$index])) {

        unset($_SESSION['panier'][$index]);


        $_SESSION['panier'] = array_values($_SESSION['panier']);
    }
}


header("Location: panier.php");
exit();
?>
