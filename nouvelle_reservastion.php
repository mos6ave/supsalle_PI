<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #3A503C;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .btn-reserver {
            background-color: #3A503C;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .btn-reserver:hover {
            background-color: #2e4031;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            color: #3A503C;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Nouvelle Réservation</h1>

        <div class="form-group">
            <label for="salle">Salle</label>
            <input type="text" id="salle" placeholder="Nom de la salle">
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date">
        </div>

        <div class="form-group">
            <label for="heure-debut">Heure début</label>
            <input type="time" id="heure-debut">
        </div>

        <div class="form-group">
            <label for="heure-fin">Heure fin</label>
            <input type="time" id="heure-fin">
        </div>

        <button class="btn-reserver">Réserver</button>

        <div class="link">
            <a href="demandes_rev.php">Voir les demandes de réservation</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const today = new Date().toISOString().split("T")[0];
            document.getElementById("date").value = today;
        });

        document.querySelector('.btn-reserver').addEventListener('click', function () {
            const salle = document.getElementById('salle').value.trim();
            const date = document.getElementById('date').value;
            const heureDebut = document.getElementById('heure-debut').value;
            const heureFin = document.getElementById('heure-fin').value;

            if (!salle || !date || !heureDebut || !heureFin) {
                alert('Veuillez remplir tous les champs.');
                return;
            }

            if (heureDebut >= heureFin) {
                alert("L'heure de fin doit être après l'heure de début.");
                return;
            }

            const confirmation = confirm(`Confirmer la réservation de ${salle} le ${date} de ${heureDebut} à ${heureFin} ?`);
            if (!confirmation) {
                alert("Réservation annulée.");
                return;
            }


            const reservation = {
                salle,
                date,
                heureDebut,
                heureFin,
                demandeur: "utilisateur1",
                statut: "en attente"
            };


            let reservations = JSON.parse(localStorage.getItem("reservations")) || [];
            reservations.push(reservation);
            localStorage.setItem("reservations", JSON.stringify(reservations));

            alert("✅ Réservation enregistrée !");

            window.location.href = "accueil.php";
        });
    </script>
</body>

</html>