


<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !=='user')) {
//     header("Location: login.php");
//     exit;
// }

// if (!isset($_SESSION['email'],$_SESSION['user_id'])) {
//     header("Location: login.php"); 
//     exit();
// }

require_once 'config.php';


// abdellahi---
$id_salle_selectionnee = $_GET['id_salle'] ?? $_POST['salle'] ?? null;
if (!$id_salle_selectionnee) {
    die("Aucune salle sélectionnée. Veuillez retourner à l'accueil et choisir une salle.");
}

$errors = [];
$success = '';

// Charger les salles
$salles = [];
$result = $conn->query("SELECT id, nom FROM salles");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $salles[] = $row;
    }
} 
else {
    $errors[] = "Erreur de chargement des salles : " . $conn->error;
}
// / abdellahi

// $id_salle_selectionnee = filter_input(INPUT_GET, 'id_salle', FILTER_VALIDATE_INT);
// if (!$id_salle_selectionnee) {
//     header("Location: accueil.php?error=invalid_room");
//     exit;
// }

// // Vérification que la salle existe et est disponible
// $stmt = $conn->prepare("SELECT id, nom FROM salles WHERE id = ? AND disponibilite = 'disponible'");
// $stmt->bind_param("i", $id_salle_selectionnee);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows === 0) {
//     header("Location: accueil.php?error=invalid_room");
//     exit;
// }

// $salle_info = $result->fetch_assoc();
// $stmt->close();

// $errors = [];
// $success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //  $salle = filter_input(INPUT_POST, 'salle', FILTER_VALIDATE_INT);
    $salle = $_POST['salle'] ?? '';
    $date = date('Y-m-d');
    $debut = $_POST['heure_debut'] ?? '';
   
    $fin = $_POST['heure_fin'] ?? '';
    
    $statut = 'en attente';

    if (!$debut || !$fin) {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif ($debut >= $fin) {
        $errors[] = "L'heure de fin doit être après l'heure de début.";
    
    }

    if (empty($errors)) {
         $id_user= $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO reservations (id_salle, id_user, date, heure_debut, heure_fin, statut) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iissss", $salle, $id_user, $date, $debut, $fin, $statut);
            if ($stmt->execute()) {
                 echo "ID user utilisé : $id_user";
    echo "<br>Réservation insérée !";
                $duree = strtotime($fin) - strtotime($debut);
                $heures = floor($duree / 3600);
                $minutes = floor(($duree % 3600) / 60);

                $success = "Réservation réussie pour une durée de " . 
                           ($heures > 0 ? $heures . "h " : "") . 
                           $minutes . "min.";

                $_SESSION['success_message'] = $success;
                // header("Location: accueil.php");
                // exit;
            } else {
                $errors[] = "Erreur d'enregistrement : " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Erreur de préparation : " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #3A503C;
        }
        .reservation-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        input[type="time"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-reserver {
            background-color: #3A503C;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
            margin-left:10px;
        }
        .btn-reserver:hover {
            background-color: #2980b9;
        }
        .link {
            text-align: center;
            margin-top: 20px;
        }
        .link a {
            color: rgb(50, 153, 56);
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>Nouvelle réservation</h1>

<form method="POST" class="reservation-form" onsubmit="return validerFormulaire();">
    <input type="hidden" name="salle" value="<?= htmlspecialchars($id_salle_selectionnee) ?>">

    <p><strong>Salle sélectionnée : </strong>
        <?php
        foreach ($salles as $salle) {
            if ($salle['id'] == $id_salle_selectionnee) {
                echo htmlspecialchars($salle['nom']);
                break;
            }
        }
        ?>
    </p>

    <div class="form-group">
        <label for="date_reservation">Date :</label>
        <input type="date" name="date_reservation" id="date_reservation" value="<?= date('Y-m-d') ?>" readonly>
    </div>
    <div class="form-group">
        <label for="heure_debut">Heure de début :</label>
        <input type="time" name="heure_debut" id="heure_debut" required>
    </div>
    <div class="form-group">
        <label for="heure_fin">Heure de fin :</label>
        <input type="time" name="heure_fin" id="heure_fin" required>
    </div>
    <div style="text-align:center;">
        <button type="submit" class="btn-reserver">Réserver</button>
    </div>

    <div class="link">
        <a href="accueil.php">Retour</a><br>
        <a href="liste_rev.php">Voir mes demandes de réservation</a>
    </div>
</form>

<script>
    function validerFormulaire() {
        const debut = document.getElementById('heure_debut').value;
        const fin = document.getElementById('heure_fin').value;

        if (!debut || !fin) {
            alert("Veuillez remplir toutes les heures.");
            return false;
        }

        if (debut >= fin) {
            alert("L'heure de fin doit être après l'heure de début.");
            return false;
        }
        return true;
    }

    <?php if ($errors): ?>
        alert("<?= implode('\\n', array_map('htmlspecialchars', $errors)) ?>");
    <?php endif; ?>

    <?php
    if (isset($_SESSION['success_message'])):
    ?>
        alert("<?= htmlspecialchars($_SESSION['success_message']) ?>");
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
</script>

</body>
</html>






















<!-- <//?php
session_start(); // Démarre la session

$host = "localhost";
$dbname = "gestion_salles";
$user = "root";
$pass = "";

// Connexion à la base
try {
    $pdo = new PDO("mysql:host=localhost;dbname=gestion_salles", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$errors = [];
$success = '';

// Récupérer les salles
$salles = [];
try {
    $salles = $pdo->query("SELECT id, nom FROM salles")->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Erreur de chargement des salles : " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salle = $_POST['salle'] ?? '';
    $date = $_POST['date_reservation'] ?? '';
    $debut = $_POST['heure_debut'] ?? '';
    $fin = $_POST['heure_fin'] ?? '';
    $id_user = $_SESSION['user_id'] ?? null; // Nécessite que l'utilisateur soit connecté
    $statut = 'en attente';

    if (!$salle || !$date || !$debut || !$fin) {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif ($debut >= $fin) {
        $errors[] = "L'heure de fin doit être après l'heure de début.";
    } elseif (!$id_user) {
        $errors[] = "Utilisateur non connecté.";
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("INSERT INTO reservations (id_salle, id_user, date, heure_debut, heure_fin, statut) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$salle, $id_user, $date, $debut, $fin, $statut]);

            header("Location: accueil.php");
            exit;

        } catch (PDOException $e) {
            $errors[] = "Erreur d'enregistrement : " . $e->getMessage();
        }
    }
}

// Récupérer l'historique des réservations
$reservations = [];
try {
    $reservations = $pdo->query("
        SELECT r.*, s.nom AS nom_salle
        FROM reservations r
        JOIN salles s ON r.id_salle = s.id
        ORDER BY r.date DESC
    ")->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Erreur de chargement des réservations : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation de Salle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #3A503C;
        }

        .reservation-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        input[type="time"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-reserver {
            background-color: #3A503C;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
            margin-left:10px
           
        }

        .btn-reserver:hover {
            background-color: #2980b9;
        }

        hr {
            border: 0;
            height: 1px;
            background-color: #ddd;
            margin: 30px 0;
        }

        h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .reservations-list {
            margin-top: 20px;
        }
 .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            color:rgb(50, 153, 56);
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

    <h1>Réservation de Salle</h1>

    <//?php if ($errors): ?>
        <div>
            <//?php foreach ($errors as $error): ?>
                <p style="color:red;"><//?= htmlspecialchars($error) ?></p>
            <?//php endforeach; ?>
        </div>
    <//?php endif; ?>

    <//?php if ($success): ?>
        <p style="color:green;"><//?= htmlspecialchars($success) ?></p>
    <//?php endif; ?>

    <form method="POST" class="reservation-form">
        <div class="form-group">
            <label for="salle">Nom de la salle :</label>
            <select name="salle" id="salle" required>
                <option value="">-- Choisissez une salle --</option>
                <//?php foreach ($salles as $s): ?>
                    <option value="</?= $s['id'] ?>"></?= htmlspecialchars($s['nom']) ?></option>
                <//?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="date_reservation">Date :</label>
            <input type="date" name="date_reservation" id="date_reservation" required>
        </div>
        <div class="form-group">
            <label for="heure_debut">Heure de début :</label>
            <input type="time" name="heure_debut" id="heure_debut" required>
        </div>
        <div class="form-group">
            <label for="heure_fin">Heure de fin :</label>
            <input type="time" name="heure_fin" id="heure_fin" required>
        </div>
        <div style="text-align:center;">
            <button type="submit" class="btn-reserver">Réserver</button>
     </div>

        
          <div class="link">
            <a href="demandes_rev.php">Voir les demandes de réservation</a>
        </div>
    </form>
       
    </div>

    

    <script>
        // Pré-remplit la date avec aujourd'hui
        document.getElementById('date_reservation').value = new Date().toISOString().split('T')[0];
       

    </script>

</body>
</html>



















 -->
