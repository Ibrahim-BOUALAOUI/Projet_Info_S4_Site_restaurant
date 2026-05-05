<?php
session_start();
$connecte = isset($_SESSION['email']);
$data = json_decode($json, true);
$plat = $data['plats'];
