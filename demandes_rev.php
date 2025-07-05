<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $reservation_id = intval($_POST['reservation_id']);
        $salle_id = intval($_POST['salle_id']);
        
        switch ($_POST['action']) {
            case 'accepter':
                // Mettre à jour le statut de la reservation
                $stmt = $conn->prepare("UPDATE reservations SET statut = 'accepte' WHERE id = ?");
                $stmt->bind_param("i", $reservation_id);
                $stmt->execute();
                
                // Marquer la salle comme indisponible
                $stmt = $conn->prepare("UPDATE salles SET disponibilite = 'indisponible' WHERE id = ?");
                $stmt->bind_param("i", $salle_id);
                $stmt->execute();
                
                
                $message = "Votre reservation #".$reservation_id." a ete acceptee";
                $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) 
                                       SELECT id_user, ? FROM reservations WHERE id = ?");
                $stmt->bind_param("si", $message, $reservation_id);
                $stmt->execute();
                
                $_SESSION['success'] = "Reservation acceptee avec succès";
                break;
                
            case 'refuser':
                $stmt = $conn->prepare("UPDATE reservations SET statut = 'refuse' WHERE id = ?");
                $stmt->bind_param("i", $reservation_id);
                $stmt->execute();
                
                $message = "Votre reservation #".$reservation_id." a ete refusee , consultez voter liste de reservation";
                $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) 
                                       SELECT id_user, ? FROM reservations WHERE id = ?");
                $stmt->bind_param("si", $message, $reservation_id);
                $stmt->execute();
                
                $_SESSION['success'] = "Reservation refusse avec succes ";
                break;
        }
        
        header("Location: demandes_rev.php");
        exit;
    }
}

// Recuperer toutes les reservations en attente
$query = "SELECT r.id, r.date, r.heure_debut, r.heure_fin, r.statut, 
                 s.nom AS salle_nom, s.id AS salle_id,
                 u.email AS user_email, u.fullname AS user_name
          FROM reservations r
          JOIN salles s ON r.id_salle = s.id
          JOIN users u ON r.id_user = u.id
          WHERE r.statut = 'en attente'
          ORDER BY r.date, r.heure_debut";
$reservations = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes de Reservation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
            color: #333;
        }

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
            text-align: center;
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

        main {
            flex: 1;
            padding: 30px;
            margin-left: 250px;
        }

        h1 {
            color: #3A503C;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .reservation-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #3A503C;
        }

        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .reservation-id {
            font-weight: bold;
            color: #3A503C;
            font-size: 1.2rem;
        }

        .reservation-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .detail-item {
            margin-bottom: 8px;
        }

        .detail-label {
            font-weight: 600;
            color: #3A503C;
            display: block;
            margin-bottom: 3px;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #555;
        }

        .reservation-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-accepter {
            background-color: #28a745;
            color: white;
        }

        .btn-accepter:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .btn-refuser {
            background-color: #dc3545;
            color: white;
        }

        .btn-refuser:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .no-reservations {
            background: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .no-reservations i {
            font-size: 2.5rem;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .no-reservations p {
            color: #6c757d;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .barre-laterale {
                width: 100%;
                position: relative;
                height: auto;
            }
            
            main {
                margin-left: 0;
                padding: 20px;
            }
            
            .reservation-details {
                grid-template-columns: 1fr;
            }
            
            .reservation-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="barre-laterale">
        <div class="entete-barre-laterale">
            <h2>SupSalle Admin</h2>
        </div>
        <ul class="menu-lateral">
            <li><a href="adminstrateur_salles.php"><i class="fa-solid fa-building"></i> Gestions des salles</a></li>
            <li><a href="demandes_rev.php" class="active"><i class="fa-solid fa-calendar-days"></i> Gestion des reservations</a></li>
            <li><a href="getios_users_admin.php"><i class="fa-solid fa-users-gear"></i> Gestion des utilisateurs</a></li>
            <li><a href="admin_compte.php"><i class="fa-solid fa-gears"></i> Mon compte</a></li>
        </ul>
    </div>

    <main>
        <h1><i class="fas fa-calendar-check"></i> Demandes de Reservation</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (empty($reservations)): ?>
            <div class="no-reservations">
                <i class="fas fa-calendar-times"></i>
                <p>Aucune demande de reservation en attente</p>
            </div>
        <?php else: ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="reservation-card">
                    <div class="reservation-header">
                        <div class="reservation-id">Demande #<?= $reservation['id'] ?></div>
                        <div class="reservation-status">
                            <span style="color: #ffc107; font-weight: bold;">
                                <i class="fas fa-clock"></i> En attente
                            </span>
                        </div>
                    </div>
                    
                    <div class="reservation-details">
                        <div class="detail-item">
                            <span class="detail-label">Salle</span>
                            <span class="detail-value"><?= htmlspecialchars($reservation['salle_nom']) ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Date</span>
                            <span class="detail-value"><?= date('d/m/Y', strtotime($reservation['date'])) ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Heure</span>
                            <span class="detail-value"><?= substr($reservation['heure_debut'], 0, 5) ?> - <?= substr($reservation['heure_fin'], 0, 5) ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Utilisateur</span>
                            <span class="detail-value">
                                <?= htmlspecialchars($reservation['user_name']) ?> (<?= htmlspecialchars($reservation['user_email']) ?>)
                            </span>
                        </div>
                    </div>
                    
                    <div class="reservation-actions">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="action" value="accepter">
                            <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                            <input type="hidden" name="salle_id" value="<?= $reservation['salle_id'] ?>">
                            <button type="submit" class="btn btn-accepter">
                                <i class="fas fa-check"></i> Accepter
                            </button>
                        </form>
                        
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="action" value="refuser">
                            <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                            <input type="hidden" name="salle_id" value="<?= $reservation['salle_id'] ?>">
                            <button type="submit" class="btn btn-refuser">
                                <i class="fas fa-times"></i> Refuser
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>