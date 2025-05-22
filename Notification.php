<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SupSalle - Notifications</title>
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
            margin: 10px;
        }

        .menu-lateral a {
            display: block;
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #698A6C;
            border-radius: 5px;
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

        /* Contenu principal */
        .contenu-principal {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        .titre-page {
            font-size: 1.8rem;
            color: #3A503C;
            margin-bottom: 30px;
        }

        /* Notifications */
        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .notification-acceptee {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }

        .notification-refusee {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }

        .notification-attente {
            background-color: #fff3cd;
            color: #856404;
            border-left: 5px solid #ffc107;
        }

        /* Bouton menu mobile */
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
                <li><a href="accueil.php"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="liste_rev.php"><i class="fas fa-calendar-check"></i> Mes Réservations</a></li>
                <li><a href="Notification.php" class="active"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="compte.php"><i class="fas fa-user-cog"></i> Mon Compte</a></li>
            </ul>
        </div>

        <div class="contenu-principal">
            <h1 class="titre-page">Mes Notifications</h1>

            <!-- <//?php foreach ($notifications as $notif): ?>
                <//?php
                    $classe = '';
                    switch ($notif['statut']) {
                        case 'acceptée': $classe = 'notification-acceptee'; break;
                        case 'refusée': $classe = 'notification-refusee'; break;
                        case 'en attente': $classe = 'notification-attente'; break;
                    }
                ?>
                <div class="notification <?= $classe ?>">
                    <i class="fas <//?= 
                        $notif//['statut'] === 'acceptée' ? 'fa-check-circle' : 
                       // ($notif//['statut'] === 'refusée' ? 'fa-times-circle' : 'fa-clock')
                    ?>"></i>
                    Votre réservation pour <strong><//?= htmlspecialchars($notif['nom_salle']) ?></strong>
                    le <//?= date('j F', strtotime($notif['date_reservation'])) ?> à <//?= htmlspecialchars($notif['heure_reservation']) ?> 
                    a été <strong><//?= htmlspecialchars($notif['statut']) ?></strong>.
                </div>
            <//?php endforeach; ?>
        </div>
    </div>

    <script>
        // Gestion du menu mobile
        document.getElementById('bouton-menu-toggle').addEventListener('click', function() {
            document.querySelector('.barre-laterale').classList.toggle('ouvert');
        });
    </script> -->
</body>
</html>