<?php
session_start();
if (!isset($_SESSION['email'], $_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$id_user = $_SESSION['user_id'];

$reservations = [];
$sql = "SELECT r.id, s.nom AS salle, r.date, r.heure_debut, r.heure_fin, r.statut
        FROM reservations r
        JOIN salles s ON r.id_salle = s.id
        WHERE r.id_user = ?
        ORDER BY r.date DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();


while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}


$stmt->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SupSalle - Mes Réservations</title>
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

        .carte-reservation {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #5fa77c;
        }

        .carte-reservation h3 {
            color: #3A503C;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .caracteristique-reservation {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .caracteristique-reservation i {
            margin-right: 10px;
            color: #3A503C;
            width: 20px;
            text-align: center;
        }

        .aucune-reservation {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            color: #666;
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
                <li><a href="liste_rev.php" class="active"><i class="fas fa-calendar-check"></i> Mes Réservations</a></li>
                <li><a href="Notification.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="compte.php"><i class="fas fa-user-cog"></i> Mon Compte</a></li>
            </ul>
        </div>

        <div class="contenu-principal">
            <h1 class="titre-page"><i class="fas fa-calendar-alt"></i> Mes Réservations</h1>

            <div id="conteneur-reservations" class="conteneur-reservations">
                <?php if (empty($reservations)): ?>
                    <div class="aucune-reservation">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 15px;"></i>
                        <p>Aucune réservation trouvée</p>
                    </div>
                <!-- dgy7huy  -->
                <?php else: ?> 
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="carte-reservation">
                            <h3><i class="fas fa-door-open"></i> <?= htmlspecialchars($reservation['salle']) ?></h3> 
                            <div class="caracteristique-reservation">
                                <i class="fas fa-calendar-day"></i>
                                <span><strong>Date :</strong> <?= htmlspecialchars($reservation['date']) ?></span>
                            </div>
                            <div class="caracteristique-reservation">
                                <i class="fas fa-clock"></i>
                                <span><strong>Heure :</strong> <?= htmlspecialchars($reservation['heure_debut']) ?> - <?= htmlspecialchars($reservation['heure_fin']) ?></span>
                            </div>
                            <div class="caracteristique-reservation">
                                <i class="fas fa-user"></i>
                                <span><strong>Statut :</strong> <?= ucfirst(htmlspecialchars($reservation['statut'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('bouton-menu-toggle').addEventListener('click', function () {
            document.querySelector('.barre-laterale').classList.toggle('ouvert');
        });
    </script>
</body>
</html>














<!-- <//?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SupSalle - Mes Réservations</title>
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
        }

        .titre-page {
            font-size: 1.8rem;
            color: #3A503C;
            margin-bottom: 30px;
        }

        /* Réservations */
        .carte-reservation {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #5fa77c;
        }

        .carte-reservation h3 {
            color: #3A503C;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .caracteristique-reservation {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .caracteristique-reservation i {
            margin-right: 10px;
            color: #3A503C;
            width: 20px;
            text-align: center;
        }

        .bouton-annuler {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .bouton-annuler:hover {
            background-color: #c0392b;
        }

        .aucune-reservation {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            color: #666;
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
                <li><a href="liste_rev.php" class="active"><i class="fas fa-calendar-check"></i> Mes Réservations</a></li>
                <li><a href="Notification.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="compte.php"><i class="fas fa-user-cog"></i> Mon Compte</a></li>
            </ul>
        </div>

        <div class="contenu-principal">
            <h1 class="titre-page"><i class="fas fa-calendar-alt"></i> Mes Réservations</h1>
            
            <div id="conteneur-reservations" class="conteneur-reservations">
                <//!-- Les réservations seront chargées ici par JavaScript -->
                <!-- <div class="aucune-reservation">
                    <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 15px;"></i>
                    <p>Aucune réservation trouvée</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion du menu mobile
        document.getElementById('bouton-menu-toggle').addEventListener('click', function() {
            document.querySelector('.barre-laterale').classList.toggle('ouvert');
        });

        // Fonction pour annuler une réservation
        function annulerReservation(id) {
            if (confirm("Êtes-vous sûr de vouloir annuler cette réservation ?")) {
                // Logique d'annulation ici
                console.log("Annulation de la réservation", id);
                // Actualiser l'affichage
                chargerReservations();
            }
        }

        // Fonction pour charger les réservations
        function chargerReservations() {
            const container = document.getElementById("conteneur-reservations");
            
            // Récupérer depuis localStorage ou API
            const reservations = JSON.parse(localStorage.getItem("reservationsValidees")) || [];
            
            if (reservations.length === 0) {
                container.innerHTML = `
                    <div class="aucune-reservation">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 15px;"></i>
                        <p>Aucune réservation trouvée</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            
            reservations.forEach(reservation => {
                const carte = document.createElement("div");
                carte.className = "carte-reservation";
                carte.innerHTML = `
                    <h3><i class="fas fa-door-open"></i> ${reservation.salle}</h3>
                    <div class="caracteristique-reservation">
                        <i class="fas fa-calendar-day"></i>
                        <span><strong>Date :</strong> ${reservation.date}</span>
                    </div>
                    <div class="caracteristique-reservation">
                        <i class="fas fa-clock"></i>
                        <span><strong>Heure :</strong> ${reservation.heureDebut} - ${reservation.heureFin}</span>
                    </div>
                    <div class="caracteristique-reservation">
                        <i class="fas fa-user"></i>
                        <span><strong>Statut :</strong> Acceptée</span>
                    </div>
                    <button class="bouton-annuler" onclick="annulerReservation('${reservation.id}')">
                        <i class="fas fa-times"></i> Annuler la réservation
                    </button>
                `;
                container.appendChild(carte);
            });
        }

        // Charger les réservations au démarrage
        document.addEventListener('DOMContentLoaded', chargerReservations);
    </script>
</body>
</html> --> 