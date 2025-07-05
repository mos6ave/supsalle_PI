<?php
session_start(); 
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
require_once 'config.php';
$email = $_SESSION['email']; 

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SupSalle - ADMIN-Compte</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        /* Barre latérale */
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
            text-align: center;
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

        .menu-lateral a:hover,
        .menu-lateral a.active {
            background-color: #addaba;
            color: #3A503C;
        }

        /* Contenu principal */
        .contenu-principal {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .titre-page {
            font-size: 1.8rem;
            color: #3A503C;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Formulaire */
        .groupe-formulaire {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .groupe-formulaire i {
            color: #3A503C;
            width: 30px;
            text-align: center;
            font-size: 1.1rem;
        }

        .info-compte {
            flex: 1;
            padding: 8px 10px;
            font-size: 1rem;
        }

        .controle-formulaire {
            flex: 1;
            border: none;
            padding: 8px 10px;
            font-size: 1rem;
            background: none;
        }

        .controle-formulaire:focus {
            outline: none;
        }

        .bouton-modifier {
            color: #3A503C;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .bouton-envoyer {
            background-color: #5fa77c;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .bouton-envoyer:hover {
            background-color: #4e8e68;
        }

        .bouton-contact {
            background: none;
            border: none;
            color: #5fa77c;
            font-size: 1rem;
            cursor: pointer;
            padding: 0;
            margin-left: 35px;
        }

        /* Menu mobile */
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

        @media (max-width: 768px) {
            .barre-laterale {
                left: -250px;
                transition: left 0.3s ease;
            }

            .barre-laterale.ouvert {
                left: 0;
            }

            .bouton-menu-toggle {
                display: block;
            }

            .contenu-principal {
                margin-left: 0;
                padding-top: 70px;
                width: 100%;
            }
        }
           .bouton-deconnexion {
            display: inline-block;
            background-color: #c0392b;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: bold;
            transition: background-color 0.3s;
}
    

        .bouton-deconnexion:hover {
          background-color: #a93226;
}

        
    </style>
</head>
<body>
    <button id="bouton-menu-toggle" class="bouton-menu-toggle">☰</button>
    
    <div class="conteneur">
        <div class="barre-laterale">
            <div class="entete-barre-laterale">
                <h2>SupSalle</h2>
            </div>
            <ul class="menu-lateral">
                <li><a href="adminstrateur_salles.php"><i class="fa-solid fa-building"></i> Gestions des salles</a></li>
                <li><a href="demandes_rev.php" ><i class="fa-solid fa-calendar-days"></i>Gestion des réservations</a></li>
                <li><a href="getios_users_admin.php"><i class="fa-solid fa-users-gear"></i>Gestion des utilisateurs</a></li>
                <li><a href="admin_compte.php"class="active"><i class="fa-solid fa-gears"></i>Mon compte</a></li>
            </ul>
        </div>
       

        <div class="contenu-principal">
            <h1 class="titre-page"><i class="fas fa-cog"></i> Paramètres du Admin-compte</h1>
            <form action="">
                 <div class="groupe-formulaire">
                    <i class="fas fa-envelope"></i>
                    <div class="info-compte"><?php echo htmlspecialchars($email); ?></div>
                </div>
                                
                <a href="logout.php" class="bouton-deconnexion">Se déconnecter</a>


            </form>
            

           
        </div>
    </div>
     

    <script>
        // Gestion du menu mobile
        document.getElementById('bouton-menu-toggle').addEventListener('click', function() {
            document.querySelector('.barre-laterale').classList.toggle('ouvert');
        });

        // // Activation des champs lors du clic sur le bouton modifier
        // document.querySelectorAll('.bouton-modifier').forEach(btn => {
        //     btn.addEventListener('click', function() {
        //         const input = this.previousElementSibling;
        //         input.removeAttribute('readonly');
        //         input.focus();
        //     });
        // });
    </script>
</body>
</html>