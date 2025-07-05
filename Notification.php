<?php
session_start();
if (!isset($_SESSION['email'], $_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

// Marquer les notifications comme lues
$user_id = $_SESSION['user_id'];
$conn->query("UPDATE notifications SET is_read = 1 WHERE user_id = $user_id");

// Récupérer les notifications
$notifications = $conn->query("SELECT * FROM notifications 
                              WHERE user_id = $user_id 
                              ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

ss
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SupSalle - Mon Compte</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color:  #f5f5f5;
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
.notification {
            background: white;
            border-left: 4px solid #5fa77c;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .notification.unread {
            border-left-color: #3A503C;
            background-color: #f8f9fa;
        }
        
        .notification-date {
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 5px;
        }
                .notification.refusee {
            border-left: 4px solid #c0392b;
            background-color: #fdf0f0;
        }
        
        .notification.desactivee {
            border-left: 4px solid #c0392b;
            background-color: #fdf0f0;
        }
        
        .notification.acceptee {
            border-left: 4px solid #5fa77c;
            background-color: #f0f9f0;
        }
        
        .notification.attente {
            border-left: 4px solid #f39c12;
            background-color: #fff8e6;
        }
        
        .notification-icon {
            margin-right: 10px;
        }
        
        .refusee .notification-icon,
        .desactivee .notification-icon {
            color: #c0392b;
        }
        
        .acceptee .notification-icon {
            color: #5fa77c;
        }
        
        .attente .notification-icon {
            color: #f39c12;
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
                <li><a href="Notification.php"class="active"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="compte.php" ><i class="fas fa-user-cog"></i> Mon Compte</a></li>
            </ul>
        </div>


 

        <div class="contenu-principal">
            <h1 class="titre-page">Mes Notifications</h1>
            <?php if (empty($notifications)): ?>
            <p>Aucune notification</p>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                  <?php
                    // Déterminer la classe CSS selon le contenu du message
                    $message = strtolower($notification['message']);
                    $classe = '';
                    $icon = 'fa-bell'; // Icône par défaut
                    
                    if (strpos($message, 'refus') !== false) {
                        $classe = 'refusee';
                        $icon = 'fa-times-circle';
                    } elseif (strpos($message, 'désactiv') !== false || strpos($message, 'desactiv') !== false) {
                        $classe = 'desactivee';
                        $icon = 'fa-user-slash';
                    } elseif (strpos($message, 'accept') !== false) {
                        $classe = 'acceptee';
                        $icon = 'fa-check-circle';
                    } elseif (strpos($message, 'attente') !== false) {
                        $classe = 'attente';
                        $icon = 'fa-clock';
                    }
                ?>
                <div class="notification <?= $classe ?> <?= $notification['is_read'] ? '' : 'unread' ?>">
                    <i class="fas <?= $icon ?> notification-icon"></i>
                    <p><?= htmlspecialchars($notification['message']) ?></p>
                    <div class="notification-date">
                        <?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

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