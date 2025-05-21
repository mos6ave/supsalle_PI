<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SupSalle - Réservation de salles SupNum</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        thead{
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        tbody{
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
            color: #22a36f;
        }

        .sidebar-menu a.active {
            background-color: #addaba;
            color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
        }

        h1 {
            color: #3A503C; 
            margin-bottom: 25px;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 1.8rem;
            color: #3A503C;
        }
        
        .rooms-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .room-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
        }
        
        .room-header {
            background-color: #3A503C;
            color: white;
            padding: 15px;
        }
        
        .room-body {
            padding: 20px;
        }
        
        .room-feature {
            display: flex;
            margin-bottom: 10px;
        }
        
        .room-feature i {
            margin-right: 10px;
            color: #3A503C;
        }
        
        .btn-reserve {
            background-color: #5fa77c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn-reserve:hover {
            background-color: #27ae60;
        }
        
        .new-reservation {
            margin-top: 40px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
        }
        .menu-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    font-size: 24px;
    background-color: #3A503C;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    z-index: 1001;
    cursor: pointer;
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        left: -250px;
        top: 0;
        height: 100%;
        transition: left 0.3s ease;
        z-index: 1000;
    }

    .sidebar.open {
        left: 0;
    }

    .menu-toggle {
        display: block;
    }

    .main-content {
        margin-left: 0;
        padding-top: 60px;
        width: 100%;
    }
}.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-btn {
    padding: 8px 15px;
    background-color: #3A503C;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.filter-btn:hover {
    background-color: #5fa77c;
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<button id="menu-toggle" class="menu-toggle">☰</button>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>SupSalle</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="accueil.php">Accueil</a></li>
            <li><a href="liste_rev.php">Mes Reservation</a></li>
            <li><a href="Notification.php">Notification</a></li>
            <li><a href="compte.php">Compte</a></li>
        </ul>
    </div>

        <div class="main-content">
            <div class="header">
                <h1 class="page-title">Les Salles du SupNum</h1>
            </div>
            <div class="filter-buttons" style="margin-bottom: 20px;">
    <button class="filter-btn" data-type="all">Toutes</button>
    <button class="filter-btn" data-type="cours">Cours</button>
    <button class="filter-btn" data-type="informatique">Informatique</button>
    <button class="filter-btn" data-type="laboratoire">Laboratoire</button>
    <button class="filter-btn" data-type="amphi">Amphithéâtre</button>
</div>
            <div class="rooms-container">
                
                <div class="room-card">
                    <div class="room-header">
                        <h3>Salle 103</h3>
                    </div>
                    <div class="room-body">
                        <div class="room-feature">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Type: Salle de cours</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 80 places</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Projecteur, tableau blanc, système audio</span>
                        </div>
                        <button class="btn-reserve">Réserver cette salle</button>
                    </div>
                </div>
                
               
                <div class="room-card">
                    <div class="room-header">
                        <h3>Salle RSS</h3>
                    </div>
                    <div class="room-body">
                        <div class="room-feature">
                            <i class="fas fa-desktop"></i>
                            <span>Type: Salle informatique</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 25 places</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Ordinateurs, vidéoprojecteur</span>
                        </div>
                        <button class="btn-reserve">Réserver cette salle</button>
                    </div>
                </div>
                
               
                <div class="room-card">
                    <div class="room-header">
                        <h3>Salle Namélozine</h3>
                    </div>
                    <div class="room-body">
                        <div class="room-feature">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Type: Salle de cours</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 80 places</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Tableau interactif, système de visioconférence</span>
                        </div>
                        <button class="btn-reserve">Réserver cette salle</button>
                    </div>
                </div>
             
                <div class="room-card">
                    <div class="room-header">
                        <h3>Salle Khawarami</h3>
                    </div>
                    <div class="room-body">
                        <div class="room-feature">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Type: Amphithéâtre</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 130 places</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Système audiovisuel complet, climatisation</span>
                        </div>
                        <button class="btn-reserve">Réserver cette salle</button>
                    </div>
                </div>
                
              
                <div class="room-card">
                    <div class="room-header">
                        <h3>Laboratoire B204</h3>
                    </div>
                    <div class="room-body">
                        <div class="room-feature">
                            <i class="fas fa-flask"></i>
                            <span>Type: Laboratoire</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 40 places</span>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Matériel de laboratoire complet, hottes de sécurité</span>
                        </div>
                        <button class="btn-reserve">Réserver cette salle</button>
                    </div>
                </div>
            </div>
            
        
                </form>
            </div>
        </div>
    </div>
        <script>
        function reserverSalle(nomSalle) {
            localStorage.setItem('salleSelectionnee', nomSalle);
            window.location.href = "nouvelle_reservastion.php";
        }

        document.addEventListener('DOMContentLoaded', function() {
            const boutons = document.querySelectorAll('.btn-reserve');
            
            boutons.forEach(bouton => {
                const nomSalle = bouton.closest('.room-card').querySelector('.room-header h3').textContent;
                bouton.addEventListener('click', () => reserverSalle(nomSalle));
            });
        });
        document.getElementById('menu-toggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
});
document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', () => {
        const type = button.dataset.type;
        document.querySelectorAll('.room-card').forEach(card => {
            const contenu = card.innerText.toLowerCase();
            if (type === "all") {
                card.style.display = "block";
            } else if (type === "cours" && contenu.includes("salle de cours")) {
                card.style.display = "block";
            } else if (type === "informatique" && contenu.includes("salle informatique")) {
                card.style.display = "block";
            } else if (type === "laboratoire" && contenu.includes("laboratoire")) {
                card.style.display = "block";
            } else if (type === "amphi" && contenu.includes("amphithéâtre")) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
});
    </script>
</body>
</html>


