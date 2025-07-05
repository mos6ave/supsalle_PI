<?php
// Configuration de la connexion à la base de données
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'supsalle_bd';
//Connexion à la base de données avec mysqli (objet)
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}
?>