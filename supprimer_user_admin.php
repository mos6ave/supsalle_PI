<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id > 0) {
    // Marquer l'utilisateur comme inactif plutôt que de le supprimer
    $stmt = $conn->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Utilisateur désactivé avec succès";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la désactivation: " . $conn->error;
    }
}

header("Location: getios_users_admin.php");
exit;
?>