<?php
session_start();
$json = file_get_contents("../Folder_Data/utilisateur.json");
$users = json_decode($json,true);

if (isset($_POST['action'])) {
    
    if ($_POST['action'] == 'delete' && isset($_POST['user_delete'])) {
        $ids_a_supprimer = $_POST['user_delete']; 

        $users = array_filter($users, function($user) use ($ids_a_supprimer) {
            return !in_array($user['id'], $ids_a_supprimer);
        });
    }

    if ($_POST['action'] == 'update' && isset($_POST['permission'])) {
        foreach ($users as &$user) {
            if (isset($_POST['permission'][$user['id']])) {
                $user['permission'] = $_POST['permission'][$user['id']];
            }
        }
    }

    file_put_contents("../Folder_Data/utilisateur.json", 
                       json_encode(array_values($users), JSON_PRETTY_PRINT));
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../Folder CSS/Admin.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gestion Utilisateurs</title>
</head>
<body>
    
    <header>
        <a href="index.php"> <img src="../Folder img/129.png" alt="Logo" width="200"> </a>
    </header>

    
    <div class="admin-container">
        

        <form method="post">
            
            
            <div class="table-actions">
                <div class="action-left">
                    <button class="delete-btn" type="submit" name="action" value="delete">
                         Supprimer la sélection
                    </button>
                </div>
                <div class="action-right">
                    <span>Gestion des utilisateurs</span>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <caption> Liste des Utilisateurs</caption>
                    <thead>
                        <tr>
                            <th class="col-checkbox">Sélection</th>
                            <th>ID</th>
                            <th>Nom utilisateur</th>
                            <th>Statut</th>
                            <th>Dernière connexion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="col-checkbox">
                                <input type="checkbox" name="user_delete[]" 
                                    value="<?= $user['id'] ?>" id="user<?= $user['id'] ?>">
                                <label for="user<?= $user['id'] ?>"></label>
                            </td>
                            <td><strong><?= $user['id'] ?></strong></td>
                            <td><?= $user['email'] ?></td>
                            <td>
                                <select name="permission[<?= $user['id'] ?>]" class="select-status">
                                    <option value="">-</option>
                                    <?php
                                    $roles = ['User' => 'Client', 'Livreur' => 'Livreur',
                                            'preparateur' => 'preparateur', 'Admin' => 'Admin'];
                                    foreach ($roles as $value => $label):
                                    ?>
                                        <option value="<?= $value ?>"
                                            <?= ($user['permission'] == $value) ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><span class="date-badge"><?= $user['last_connection'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="action-buttons">
                <button class="btn-secondary" type="reset">
                     Annuler
                </button>
                <button class="btn-primary" type="submit" name="action" value="update">
                     Valider les modifications
                </button>
            </div>

        </form>

    </div>

</body>
</html>
