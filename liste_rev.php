<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
            color: #333;
        }

        .sidebar {
            width: 250px;
            background-color: #3A503C;
            color: white;
            padding: 20px 0;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #3A503C;
            margin-bottom: 20px;
        }

        .sidebar-header h2 {
            color: #ecf0f1;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: block;
            color: #bdc3c7;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #698A6C;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #addaba;
            color: #22a36f;
        }

        main {
            flex: 1;
            padding: 30px;
        }

        h1 {
            color: #2c3e50;
        }

        .reservation {
            background-color: white;
            border-left: 5px solid #5fa77c;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .reservation p {
            margin: 5px 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #3A503C;
            color: white;
            padding: 20px 0;
            z-index: 1000;
        }

        main {
            flex: 1;
            padding: 30px;
            margin-left: 250px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>SupSalle</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="accueil.php">Accueil</a></li>
            <li><a href="liste_rev.php">Mes Réservations</a></li>
            <li><a href="Notification.php">Notification</a></li>
            <li><a href="compte.php">Compte</a></li>
        </ul>
    </div>

    <main>
        <h1>Réservations Acceptées</h1>
        <div id="liste-reservations"></div>
    </main>

    <script>
        const reservations = JSON.parse(localStorage.getItem("reservationsValidees")) || [];
        const container = document.getElementById("liste-reservations");

        if (reservations.length === 0) {
            container.innerHTML = "<p>Aucune réservation acceptée pour le moment.</p>";
        } else {
            reservations.forEach(reservation => {
                const div = document.createElement("div");
                div.className = "reservation";
                div.innerHTML = `
          <strong>${reservation.salle}</strong><br>
          <p><strong>Date</strong> : ${reservation.date}</p>
          <p><strong>Heure</strong> : ${reservation.heureDebut} - ${reservation.heureFin}</p>
          <p><strong>Demandeur</strong> : ${reservation.nom || "Utilisateur"}</p>
        `;
                container.appendChild(div);
            });
        }
    </script>

</body>

</html>