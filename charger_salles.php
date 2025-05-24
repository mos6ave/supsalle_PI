<?php
require_once 'conf.php';

header('Content-Type: application/json');

$query = "SELECT * FROM salles";
$result = $conn->query($query);

$salles = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $salles[] = $row;
    }
}

echo json_encode($salles);
$conn->close();
?>