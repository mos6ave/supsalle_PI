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
        tableau-utilisateurs td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }
         .bouton-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 24px;
            background-color: #3A503C;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 1001;
            cursor: pointer;
        }

        .bouton-menu-toggle {
                display: block;
            }
             @media (max-width: 768px) {
            .barre-laterale {
                left: -250px;
                transition: left 0.3s ease;
            }
            
            .barre-laterale.ouvert {
                left: 0;
            }
            
            .contenu-principal {
                margin-left: 0;
                width: 100%;
            }
            
            .bouton-menu-toggle {
                display: block;
            }
        }

        @media (min-width: 769px) {
            .bouton-menu-toggle {
                display: none !important;
            }
        }
        .statut-actif {
    color: #28a745; /* Vert pour actif */
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 12px;
    background-color: rgba(40, 167, 69, 0.1);
    display: inline-block;
}

.statut-inactif {
    color: #dc3545; /* Rouge pour inactif */
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 12px;
    background-color: rgba(220, 53, 69, 0.1);
    display: inline-block;
    text-decoration: line-through;
}
.statut-verifie {
    color: #2ecc71;
    font-weight: bold;
}
.statut-non-verifie {
    color: #e74c3c;
    font-weight: bold;
}



        
         
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <button id="bouton-menu-toggle" class="bouton-menu-toggle">☰</button>
    <div class="conteneur">
        <div class="barre-laterale">
            <div class="entete-barre-laterale">
                <h2>SupSalle Admin</h2>
            </div>
            <ul class="menu-lateral">
                <li><a href="adminstrateur_salles.php" ><i class="fa-solid fa-building"></i> Gestions des salles</a></li>
                <li><a href="demandes_rev.php"><i class="fa-solid fa-calendar-days"></i>Gestion des réservations</a></li>
                <li><a href="getios_users_admin.php" class="active"><i class="fa-solid fa-users-gear"></i>Gestion des utilisateurs</a></li>
                <li><a href="admin_compte.php"><i class="fa-solid fa-gears"></i>Mon compte</a></li>
            </ul>
        </div>
        
        <div class="contenu-principal">
            <h1>Gestion des utilisateurs</h1>
            
            <table class="tableau-utilisateurs">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Verification</th>
                        <th>Rôle</th>
                        <th>Statut</th>
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
                                   <i class="fas <?php echo $user['is_verified'] ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                               <?php echo $user['is_verified'] ? 'Vérifié' : 'Non vérifié'; ?>
               </span>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                             <span class="statut-<?php echo $user['is_active'] ? 'actif' : 'inactif'; ?>">
                                 <?php echo $user['is_active'] ? 'Actif' : 'Inactif'; ?>
                               </span>
                            
                      </td>
                        <td>
                             <a href="Modifier_user_admin.php?id=<?= $user['id'] ?> "><i class="fas fa-edit"></i>Modifier</a> 

                               <a href="desactivee_user_admin.php?id=<?=$user['id']?>" onclick="return confirm('Confirmer la désactivation de cet utilisateur ?');">
                                        <i class="fas fa-user-slash"></i> Désactiver</a>
                                      

                        </td>
                        
                           
                            
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Gestion du menu mobile
        document.getElementById('bouton-menu-toggle').addEventListener('click', function() {
            document.querySelector('.barre-laterale').classList.toggle('ouvert');
        });
      
      </script>
   
</body>
</html>