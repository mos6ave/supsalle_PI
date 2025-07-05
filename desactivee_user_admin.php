<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        
        $message = "Votre compte a été désactivé par l'administrateur ,vous pouvez nous contactez sur ****** ";
        $notification_stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $notification_stmt->bind_param("is", $user_id, $message);
        $notification_stmt->execute();
        
        $_SESSION['success'] = "Utilisateur désactivé avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la désactivation";
    }
    
    header("Location: getios_users_admin.php");
    exit;
}
?>