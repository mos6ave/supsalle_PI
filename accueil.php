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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            min-height: 100vh;
        }

        .conteneur {
            display: flex;
            min-height: 100vh;
        }

        /* Styles de la barre latérale */
        .barre-laterale {
            width: 250px;
            background-color: #3A503C;
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: fixed;
            transition: left 0.3s ease;
            z-index: 1000;
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

        /* Styles du contenu principal */
        .contenu-principal {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
        }

        .entete {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .titre-page {
            font-size: 1.8rem;
            color: #3A503C;
        }

        /* Styles du conteneur des salles */
        .conteneur-salles {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .carte-salle {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .carte-salle:hover {
            transform: translateY(-5px);
        }

        .entete-carte {
            background-color: #3A503C;
            color: white;
            padding: 15px;
        }

        .entete-carte h3 {
            margin: 0;
        }

        .corps-carte {
            padding: 20px;
        }

        .caracteristique-salle {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .caracteristique-salle i {
            margin-right: 10px;
            color: #3A503C;
            width: 20px;
            text-align: center;
        }

        .bouton-reserver {
            background-color: #5fa77c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .bouton-reserver:hover {
            background-color: #4e8e68;
        }

        .filtres-boutons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .bouton-filtre {
            padding: 8px 15px;
            background-color: #3A503C;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .bouton-filtre:hover {
            background-color: #5fa77c;
        }

        /* Menu toggle pour mobile */
        .bouton-menu-toggle {
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
            .barre-laterale {
                left: -250px;
            }

            .barre-laterale.ouvert {
                left: 0;
            }

            .bouton-menu-toggle {
                display: block;
            }

            .contenu-principal {
                margin-left: 0;
                padding-top: 70px;
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <button id="bouton-menu-toggle" class="bouton-menu-toggle">☰</button>
    
    <div class="conteneur">
        <div class="barre-laterale">
            <div class="entete-barre-laterale">
                <h2>SupSalle</h2>
            </div>
            <ul class="menu-lateral">
                <li><a href="accueil.php" class="active"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="liste_rev.php"><i class="fas fa-calendar-check"></i> Mes Réservations</a></li>
                <li><a href="Notification.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="compte.php"><i class="fas fa-user-cog"></i> Mon Compte</a></li>
            </ul>
        </div>

        <div class="contenu-principal">
            <div class="entete">
                <h1 class="titre-page">Les Salles du SupNum</h1>
            </div>
            
            <div class="filtres-boutons">
                <button class="bouton-filtre" data-type="all">Toutes</button>
                <button class="bouton-filtre" data-type="cours">Salles de cours</button>
                <button class="bouton-filtre" data-type="informatique">Salles informatique</button>
                <button class="bouton-filtre" data-type="laboratoire">Laboratoires</button>
                <button class="bouton-filtre" data-type="amphi">Amphithéâtres</button>
            </div>
            
            <div class="conteneur-salles" id="conteneur-salles">
                <div class="carte-salle">
                    <div class="entete-carte">
                        <h3>Salle 103</h3>
                    </div>
                    <div class="corps-carte">
                        <div class="caracteristique-salle">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Type: Salle de cours</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 80 places</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Projecteur, tableau blanc, système audio</span>
                        </div>
                        <button class="bouton-reserver">Réserver cette salle</button>
                    </div>
                </div>
                
                <div class="carte-salle">
                    <div class="entete-carte">
                        <h3>Salle RSS</h3>
                    </div>
                    <div class="corps-carte">
                        <div class="caracteristique-salle">
                            <i class="fas fa-desktop"></i>
                            <span>Type: Salle informatique</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 25 places</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Ordinateurs, vidéoprojecteur</span>
                        </div>
                        <button class="bouton-reserver">Réserver cette salle</button>
                    </div>
                </div>
                
                <div class="carte-salle">
                    <div class="entete-carte">
                        <h3>Salle Namélozine</h3>
                    </div>
                    <div class="corps-carte">
                        <div class="caracteristique-salle">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Type: Salle de cours</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 80 places</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Tableau interactif, système de visioconférence</span>
                        </div>
                        <button class="bouton-reserver">Réserver cette salle</button>
                    </div>
                </div>
                
                <div class="carte-salle">
                    <div class="entete-carte">
                        <h3>Salle Khawarami</h3>
                    </div>
                    <div class="corps-carte">
                        <div class="caracteristique-salle">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Type: Amphithéâtre</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 130 places</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Système audiovisuel complet, climatisation</span>
                        </div>
                        <button class="bouton-reserver">Réserver cette salle</button>
                    </div>
                </div>
                
                <div class="carte-salle">
                    <div class="entete-carte">
                        <h3>Laboratoire B204</h3>
                    </div>
                    <div class="corps-carte">
                        <div class="caracteristique-salle">
                            <i class="fas fa-flask"></i>
                            <span>Type: Laboratoire</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-users"></i>
                            <span>Capacité: 40 places</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: Matériel de laboratoire complet, hottes de sécurité</span>
                        </div>
                        <button class="bouton-reserver">Réserver cette salle</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour rediriger vers la page de réservation
        function reserverSalle(nomSalle) {
            localStorage.setItem('salleSelectionnee', nomSalle);
            window.location.href = "nouvelle_reservation.php";
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des boutons de réservation
            document.querySelectorAll('.bouton-reserver').forEach(bouton => {
                const nomSalle = bouton.closest('.carte-salle').querySelector('.entete-carte h3').textContent;
                bouton.addEventListener('click', () => reserverSalle(nomSalle));
            });
            
            // Gestion du menu mobile
            document.getElementById('bouton-menu-toggle').addEventListener('click', function() {
                document.querySelector('.barre-laterale').classList.toggle('ouvert');
            });
            
            // Gestion des filtres
            document.querySelectorAll('.bouton-filtre').forEach(button => {
                button.addEventListener('click', () => {
                    const type = button.dataset.type;
                    document.querySelectorAll('.carte-salle').forEach(card => {
                        const contenu = card.innerText.toLowerCase();
                        
                        if (type === "all") {
                            card.style.display = "block";
                        } 
                        else if (type === "cours" && contenu.includes("salle de cours")) {
                            card.style.display = "block";
                        } 
                        else if (type === "informatique" && contenu.includes("salle informatique")) {
                            card.style.display = "block";
                        } 
                        else if (type === "laboratoire" && contenu.includes("laboratoire")) {
                            card.style.display = "block";
                        } 
                        else if (type === "amphi" && contenu.includes("amphithéâtre")) {
                            card.style.display = "block";
                        } 
                        else {
                            card.style.display = "none";
                        }
                    });
                    
                    // Mise à jour de l'état actif des boutons
                    document.querySelectorAll('.bouton-filtre').forEach(btn => {
                        btn.style.backgroundColor = btn === button ? '#5fa77c' : '#3A503C';
                    });
                });
            });
            
            // Activer le filtre "Toutes" par défaut
            document.querySelector('.bouton-filtre[data-type="all"]').click();
        });
    </script>
</body>
</html>