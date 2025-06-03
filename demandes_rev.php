<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Demandes de Réservation</title>
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

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .demande {
            background-color: white;
            border-left: 5px solid #3A503C;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .demande p {
            margin: 5px 0;
        }

        .actions {
            margin-top: 10px;
        }

        .actions button {
            margin-right: 10px;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .accept {
            background-color: green;
        }

        .refuse {
            background-color: red;
        }

        #liste-demandes p.no-data {
            font-style: italic;
            color: #777;
        }

        
        .conteneur {
            display: flex;
            min-height: 100vh;
            position: fixed;
            /* rend la sidebar fixe */
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            /* pleine hauteur de la fenêtre */
            background-color: #3A503C;
            color: white;
            padding: 20px 0;
            z-index: 1000;
        }

        main {
            flex: 1;
            padding: 30px;
            margin-left: 250px;
            /* espace réservé à la sidebar fixe */
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
                <li><a href="adminstrateur_salles.php"><i class="fa-solid fa-building"></i> Gestions des salles</a></li>
                <li><a href="demandes_rev.php" class="active"><i class="fa-solid fa-calendar-days"></i>Gestion des réservations</a></li>
                <li><a href="getios_users_admin.php"><i class="fa-solid fa-users-gear"></i>Gestion des utilisateurs</a></li>
                <li><a href="admin_compte.php"><i class="fa-solid fa-gears"></i>Mon compte</a></li>
            </ul>
        </div>

    </div>

    <main>
        <h1>Demandes de Réservation</h1>
        <div id="liste-demandes"></div>
    </main>
    <script>
        const demandes = JSON.parse(localStorage.getItem("reservations")) || [];

        const container = document.getElementById("liste-demandes");

        demandes.forEach((demande, index) => {
            const div = document.createElement("div");
            div.className = "demande";
            div.innerHTML = `
                <strong>${demande.salle}</strong><br>
                <p><strong>Date</strong> : ${demande.date}</p>
                <p><strong>Heure</strong> : ${demande.heureDebut} - ${demande.heureFin}</p>
                <p><strong>Demandeur</strong> : utilisateur</p>
                <div class="actions">
                    <button class="accept" onclick="accepter(${index})">Accepter</button>
                    <button class="refuse" onclick="refuser(${index})">Refuser</button>
                </div>
            `;
            container.appendChild(div);
        });

        function accepter(index) {
            const reservations = JSON.parse(localStorage.getItem("reservations")) || [];
            const valides = JSON.parse(localStorage.getItem("reservationsValidees")) || [];

            valides.push(reservations[index]); // on ajoute la réservation validée
            localStorage.setItem("reservationsValidees", JSON.stringify(valides));

            alert("✅ Réservation acceptée !");
            supprimer(index); // puis on la supprime de la liste des demandes
        }

        function refuser(index) {
            alert("❌ Réservation refusée !");
            supprimer(index);
        }

        function supprimer(index) {
            const demandes = JSON.parse(localStorage.getItem("reservations")) || [];
            demandes.splice(index, 1);
            localStorage.setItem("reservations", JSON.stringify(demandes));
            location.reload();
        }
    </script>

</body>

</html>