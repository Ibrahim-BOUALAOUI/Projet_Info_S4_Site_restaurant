<?php
session_start();
unset($_SESSION['modifying_cmd_id']);
unset($_SESSION['modifying_cmd_amount_paid']);
$_SESSION['panier'] = [];
header("Location: Profil.php");
exit();
