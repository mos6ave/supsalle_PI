<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres | SupSalle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
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
        }

        .sidebar {
            width: 250px;
            background-color: #3A503C;
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
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
          background-color: #698A6C;

        }

        .sidebar-menu a {
            display: block;
            color: #bdc3c7;
            text-decoration: none;
            padding: 10px 20px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover {
            background-color: #addaba;
            color: #ecf0f1;
        }

        .sidebar-menu a.active {
            background-color: #addaba;
            color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            background-color: white;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .form-group i {
            color: #666;
            font-size: 18px;
        }

        input, select {
            flex: 1;
            border: none;
            padding: 8px 0;
            background: none;
            font-size: 16px;
        }

        select:focus, input:focus {
            outline: none;
        }

        .bi-pencil {
            color: #666;
            cursor: pointer;
        }

        button[type="submit"] {
            background: #27ae60;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
        }

        .contact-btn {
            background: none;
            border: none;
            color: #27ae60;
            font-size: 16px;
            cursor: pointer;
            padding: 0;
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
            <li><a href="compte.php" class="active">Compte</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Paramètres <span>🔧</span></h2>
        
        <form action="" method="post">
            <div class="form-group">
                <i class="bi bi-envelope"></i>
                <input type="email" name="email" placeholder="Adresse e-mail">
                <i class="bi bi-pencil"></i>
            </div>

            <div class="form-group">
                <i class="bi bi-lock"></i>
                <input type="password" name="password" placeholder="Mot de passe">
                <i class="bi bi-pencil"></i>
            </div>

            <div class="form-group">
                <i class="bi bi-globe"></i>
                <label for="langue">Langue</label>
                <select name="langue" id="langue">
                    <option value="fr">Français</option>
                    <option value="ar">Arabe</option>
                    <option value="en">Anglais</option>
                </select>
                <i class="bi bi-pencil"></i>
            </div>

            <div class="form-group">
                <i class="bi bi-person"></i>
                <button type="button" class="contact-btn">Contactez-nous</button>
            </div>

            
        </form>
    </div>
</body>
</html>
