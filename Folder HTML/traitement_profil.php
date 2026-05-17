<?php
session_start();

// Protection de la route
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Session expirée. Veuillez vous reconnecter.']);
    exit();
}

// Vérification de la méthode de réception
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $adress = trim($_POST['adress'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Validation basique
    if (empty($name) || empty($adress) || empty($phone)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires.']);
        exit();
    }

    $filePath = '../Folder_Data/utilisateur.json';

    // Lecture du fichier JSON existant
    if (file_exists($filePath)) {
        $users = json_decode(file_get_contents($filePath), true) ?? [];
    } else {
        $users = [];
    }

    $updateSuccess = false;

    // Parcours et modification de l'utilisateur concerné
    foreach ($users as &$user) {
        if ($user['email'] === $_SESSION['email']) {
            $user['name'] = $name;
            $user['adress'] = $adress;
            $user['phone'] = $phone;
            $updateSuccess = true;
            break;
        }
    }

    // Sauvegarde si l'utilisateur a été trouvé et modifié
    if ($updateSuccess) {
        if (file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès !']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'écriture du fichier de données.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}
