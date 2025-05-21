<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SupSalle - Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reprend ton style sidebar */
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #d4d4d4;
        }

        .sidebar {
            width: 250px;
            background-color: #2e4a34;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 15px;
            text-decoration: none;
            color: white;
            background-color: #43654d;
            margin: 10px;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #5fa77c;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        h1 {
            color: #2e4a34;
        }

        .notification {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .accepted {
            background-color: #ccffcc;
            color: #2e7d32;
        }

        .refused {
            background-color: #ffcccc;
            color: #c62828;
        }

        .pending {
            background-color: #ffffcc;
            color: #f9a825;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>SupSalle</h2>
    <a href="accueil.php">Accueil</a>
    <a href="liste_rev.php">Mes Reservation</a>
    <a href="Notification.php">Notification</a>
    <a href="compte.php">Compte</a>
</div>

<div class="main-content">
    <h1>Mes Notifications</h1>

    <?php foreach ($notifications as $notif): ?>
        <?php
            $classe = '';
            switch ($notif['statut']) {
                case 'acceptée': $classe = 'accepted'; break;
                case 'refusée': $classe = 'refused'; break;
                case 'en attente': $classe = 'pending'; break;
            }
        ?>
        <div class="notification <?= $classe ?>">
            Votre réservation pour <strong><?= htmlspecialchars($notif['nom_salle']) ?></strong>
            le <?= date('j F', strtotime($notif['date_reservation'])) ?> à <?= htmlspecialchars($notif['heure_reservation']) ?> 
            a été <strong><?= htmlspecialchars($notif['statut']) ?></strong>.
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>