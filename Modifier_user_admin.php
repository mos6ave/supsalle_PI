<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = [];

if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $fullname = $_POST['fullname'];
    // $email = $_POST['email'];
    $is_verified = isset($_POST['is_verified']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0; 
    $role = $_POST['role'];
    
    $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, is_verified = ?, is_active = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssiisi", $fullname, $email, $is_verified, $is_active, $role, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Utilisateur mis à jour avec succès";

        $message = "Votre compte a été activé par l'administrateur  ";
        $notification_stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $notification_stmt->bind_param("is", $user_id, $message);
        $notification_stmt->execute();
        header("Location: getios_users_admin.php");
        exit;
    } else {
        $error = "Erreur lors de la mise à jour: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
        background-color: #f8f9fa;
        padding: 20px;
        color: #333;
    }
    
    .container {
        max-width: 800px;
        margin: 30px auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    h1 {
        color: #3A503C;
        margin-bottom: 25px;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #3A503C;
        font-size: 0.95rem;
    }
    
    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        transition: border 0.3s;
    }
    
    input[type="text"]:focus,
    input[type="email"]:focus,
    select:focus {
        border-color: #5fa77c;
        outline: none;
        box-shadow: 0 0 0 3px rgba(95, 167, 124, 0.2);
    }
    
    .status-container {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        background: #f9f9f9;
        padding: 15px;
        border-radius: 6px;
    }
    
    .status-label {
        font-weight: 500;
        color: #555;
        width: 120px;
    }
    
    /* Toggle Switch Modern */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e0e0e0;
        transition: .4s;
        border-radius: 34px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    
    input:checked + .slider {
        background-color: #5fa77c;
    }
    
    input:checked + .slider:before {
        transform: translateX(24px);
    }
    
    /* Boutons */
    .btn-group {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary {
        background-color: #5fa77c;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #4e8e68;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background-color: #f0f0f0;
        color: #555;
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background-color: #e0e0e0;
    }
    
    /* Messages d'erreur/succès */
    .error {
        color: #dc3545;
        background-color: #f8d7da;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }
    
    .success {
        color: #28a745;
        background-color: #d4edda;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
            margin: 15px;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }

        /* [Conservez vos styles existants] */
        
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-left: 15px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #5fa77c;
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        .status-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .status-label {
            font-weight: 500;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-user-edit"></i> Modifier Utilisateur</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- <div class="form-group">
                <label for="fullname">Nom complet</label>
                <input type="text" id="fullname" name="fullname" 
                       value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>
             -->
            <div class="form-group">
                <label>Statut du compte</label>
                <div class="status-container">
                    <span class="status-label">Vérifié:</span>
                    <label class="switch">
                        <input type="checkbox" name="is_verified" <?php echo ($user['is_verified'] ?? 0) ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="status-container">
                    <span class="status-label">Actif:</span>
                    <label class="switch">
                        <input type="checkbox" name="is_active" <?php echo ($user['is_active'] ?? 1) ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="role">Rôle</label>
                <select id="role" name="role" required>
                    <option value="user" <?php echo ($user['role'] ?? '') === 'user' ? 'selected' : ''; ?>>Utilisateur</option>
                    <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                </select>
            </div>
            
            <div class="form-group" style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="getios_users_admin.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>