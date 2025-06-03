<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
require_once 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['action'])){
        $id=intval($_POST['id']);


        switch($_POST['action'])
        {
            case 'modifier':
                $fulname=$conn->real_escape_string($_POST['fullname']);
                $email=$conn->real_escape_string($_POST['email']);
                $is_verified=isset($_POST['is_verified'])? 1 :0;
                $stmt=$conn->prepare("UPDATE users SET fullname=?, email=?, is_verified=? WHERE id=?");
                $stmt->bind_param("ssii",$fullname, $email, $is_verified, $id);
                $stmt->execute();
                $_SESSION['message'] = "Utilisateur modifié avec succès";
                break;
            case 'supprimer':
                $stmt=$conn->prepare("DELETE FROM users WHERE id=? AND role='user'");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                 $_SESSION['message'] = "Utilisateur supprimé avec succès";
                break;
        }
        header("Location:getios_users_admin.php");
        exit;
    }
}

//requper tout les userse 
$query="SELECT * FROM `users` WHERE role='user'";
// Exécuter la requête avec la méthode query()
$result = $conn->query($query);
$users=[];
if($result->num_rows>0){
    while($row=$result->fetch_assoc()){
        $users[]=$row;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SupSalle Admin - Gestion des utilisateurs</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            min-height: 100vh;
        }

        .conteneur {
            display: flex;
            min-height: 100vh;
        }

        /* Styles de la barre latérale */
        .barre-laterale {
            width: 250px;
            background-color: #3A503C;
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
        }

        .entete-barre-laterale {
            padding: 0 20px 20px;
            border-bottom: 1px solid #4a6350;
            margin-bottom: 20px;
        }

        .entete-barre-laterale h2 {
            color: #ecf0f1;
        }

        .menu-lateral {
            list-style: none;
        }

        .menu-lateral li {
            margin-bottom: 5px;
            background-color: #698A6C;
        }

        .menu-lateral a {
            display: block;
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 20px;
            transition: all 0.3s;
        }

        .menu-lateral a:hover {
            background-color: #addaba;
            color: #3A503C;
        }

        .menu-lateral a.active {
            background-color: #addaba;
            color: #3A503C;
            font-weight: bold;
        }
        
        .contenu-principal {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        
        .tableau-utilisateurs {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .tableau-utilisateurs th, .tableau-utilisateurs td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .tableau-utilisateurs th {
            background-color: #3A503C;
            color: white;
        }
        
        .tableau-utilisateurs tr:hover {
            background-color: #f5f5f5;
        }
        
        .btn-modifier {
            background-color: #698A6C;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 5px;
        }
        
        .btn-supprimer {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .btn-modifier:hover {
            background-color: #5a7961;
        }
        
        .btn-supprimer:hover {
            background-color: #c9302c;
        }
        
        .statut-verifie {
            color: #5cb85c;
            font-weight: bold;
        }
        
        .statut-non-verifie {
            color: #d9534f;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .message-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        
        .formulaire-modification {
            display: none;
            background-color: #f9f9f9;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .formulaire-modification input[type="text"],
        .formulaire-modification input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .formulaire-modification label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .formulaire-modification .checkbox-container {
            margin-bottom: 10px;
        }
        
        .btn-enregistrer {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-annuler {
            background-color: #f0ad4e;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .btn-enregistrer:hover {
            background-color: #4cae4c;
        }
        
        .btn-annuler:hover {
            background-color: #ec971f;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="conteneur">
        <div class="barre-laterale">
            <div class="entete-barre-laterale">
                <h2>SupSalle Admin</h2>
            </div>
            <ul class="menu-lateral">
                <li><a href="adminstrateur_salle.php" ><i class="fa-solid fa-building"></i> Gestions des salles</a></li>
                <li><a href="demandes_rev.php"><i class="fa-solid fa-calendar-days"></i>Gestion des réservations</a></li>
                <li><a href="getios_users_admin.php" class="active"><i class="fa-solid fa-users-gear"></i>Gestion des utilisateurs</a></li>
                <li><a href="admin_compte.php"><i class="fa-solid fa-gears"></i>Mon compte</a></li>
            </ul>
        </div>
        
        <div class="contenu-principal">
            <h1>Gestion des utilisateurs</h1>
            
            <?php if(isset($_SESSION['message'])): ?>
                <div class="message message-success">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <table class="tableau-utilisateurs">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="<?php echo $user['is_verified'] ? 'statut-verifie' : 'statut-non-verifie'; ?>">
                                <?php echo $user['is_verified'] ? 'Vérifié' : 'Non vérifié'; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <button class="btn-modifier" onclick="afficherFormulaireModification(<?php echo $user['id']; ?>)">
                                Modifier
                            </button>
                            <form method="POST" action="getios_users_admin.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action" value="supprimer">
                                <button type="submit" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    Supprimer
                                </button>
                            </form>
                            
                            <div id="formulaire-modification-<?php echo $user['id']; ?>" class="formulaire-modification">
                                <form method="POST" action="getios_users_admin.php">
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="action" value="modifier">
                                    
                                    <label for="fullname-<?php echo $user['id']; ?>">Nom complet:</label>
                                    <input type="text" id="fullname-<?php echo $user['id']; ?>" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                                    
                                    <label for="email-<?php echo $user['id']; ?>">Email:</label>
                                    <input type="email" id="email-<?php echo $user['id']; ?>" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    
                                    <div class="checkbox-container">
                                        <input type="checkbox" id="is_verified-<?php echo $user['id']; ?>" name="is_verified" <?php echo $user['is_verified'] ? 'checked' : ''; ?>>
                                        <label for="is_verified-<?php echo $user['id']; ?>">Compte vérifié</label>
                                    </div>
                                    
                                    <button type="submit" class="btn-enregistrer">Enregistrer</button>
                                    <button type="button" class="btn-annuler" onclick="cacherFormulaireModification(<?php echo $user['id']; ?>)">Annuler</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        function afficherFormulaireModification(id) {
            // Cacher tous les formulaires de modification d'abord
            var formulaires = document.querySelectorAll('.formulaire-modification');
            formulaires.forEach(function(form) {
                form.style.display = 'none';
            });
            
            // Afficher le formulaire correspondant à l'ID
            document.getElementById('formulaire-modification-' + id).style.display = 'block';
        }
        
        function cacherFormulaireModification(id) {
            document.getElementById('formulaire-modification-' + id).style.display = 'none';
        }
    </script>
</body>
</html>