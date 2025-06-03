<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
require_once 'config.php';


// Récupérer toutes les salles
$query = "SELECT * FROM salles";
$result = $conn->query($query);
$salles = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $salles[] = $row;
    }
}

// Gestion des actions (ajout, modification, suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'ajouter':
                $nom = $conn->real_escape_string($_POST['nom']);
                $type = $conn->real_escape_string($_POST['type']);
                $capacite = intval($_POST['capacite']);
                $equipement = $conn->real_escape_string($_POST['equipement']);
                
                $stmt = $conn->prepare("INSERT INTO salles (nom, type, capacité, équipements) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $nom, $type, $capacite, $equipement);
                $stmt->execute();
                $stmt->close();
                break;
                
            case 'modifier':
                $id = intval($_POST['id']);
                $nom = $conn->real_escape_string($_POST['nom']);
                $type = $conn->real_escape_string($_POST['type']);
                $capacite = intval($_POST['capacite']);
                $equipement = $conn->real_escape_string($_POST['equipement']);
                
                $stmt = $conn->prepare("UPDATE salles SET nom=?, type=?, capacité=?, équipements=? WHERE id=?");
                $stmt->bind_param("ssisi", $nom, $type, $capacite, $equipement, $id);
                $stmt->execute();
                $stmt->close();
                break;
                
            case 'supprimer':
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("DELETE FROM salles WHERE id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
                break;
                case 'changer_disponibilite':
                    $id = intval($_POST['id']);
                    // Récupérer la valeur actuelle
                    $res = $conn->query("SELECT disponibilite FROM salles WHERE id = $id");
                    if ($res && $row = $res->fetch_assoc()) {
                        $nouvelle_dispo = ($row['disponibilite'] === 'disponible') ? 'indisponinle' : 'disponible';
                        $stmt = $conn->prepare("UPDATE salles SET disponibilite=? WHERE id=?");
                        $stmt->bind_param("si", $nouvelle_dispo, $id);
                        $stmt->execute();
                        $stmt->close();
                    }
                    header("Location: adminstrateur_salles.php");
                    exit;
        }
        
        // Rafraîchir la page après modification
        header("Location: adminstrateur_salles.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SupSalle Admin - Gestion des salles</title>
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

        .bouton-ajouter-salle {
            background-color: #5fa77c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .bouton-ajouter-salle:hover {
            background-color: #4e8e68;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .entete-carte h3 {
            margin: 0;
        }

        .actions-salle {
            display: flex;
            gap: 10px;
        }

        .bouton-action {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .bouton-action:hover {
            color: #addaba;
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

        /* Styles des modales */
        .modale {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .contenu-modale {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .entete-modale {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .titre-modale {
            font-size: 1.5rem;
            color: #3A503C;
        }

        .bouton-fermer {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .groupe-formulaire {
            margin-bottom: 15px;
        }

        .groupe-formulaire label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #3A503C;
        }

        .controle-formulaire {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .actions-formulaire {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .bouton {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .bouton-primaire {
            background-color: #5fa77c;
            color: white;
        }

        .bouton-primaire:hover {
            background-color: #4e8e68;
        }

        .bouton-secondaire {
            background-color: #6c757d;
            color: white;
        }

        .bouton-secondaire:hover {
            background-color: #5a6268;
        }

        .bouton-danger {
            background-color: #dc3545;
            color: white;
        }

        .bouton-danger:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .conteneur {
                flex-direction: column;
            }
            
            .barre-laterale {
                width: 100%;
                position: relative;
                height: auto;
            }
            
            .contenu-principal {
                margin-left: 0;
                width: 100%;
            }
            
            .actions-salle {
                flex-direction: column;
                gap: 5px;
            }
        }
        .btn-disponibilite:hover {
    opacity: 0.85;
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="conteneur">
        <div class="barre-laterale">
            <div class="entete-barre-laterale">
                <h2>SupSalle Admin</h2>
            </div>
            <ul class="menu-lateral">
                <li><a href="adminstrateur_salles.php" class="active"><i class="fa-solid fa-building"></i> Gestions des salles</a></li>
                <li><a href="demandes_rev.php"><i class="fa-solid fa-calendar-days"></i>Gestion des réservations</a></li>
                <li><a href="getios_users_admin.php"><i class="fa-solid fa-users-gear"></i>Gestion des utilisateurs</a></li>
                <li><a href="admin_compte.php"><i class="fa-solid fa-gears"></i>Mon compte</a></li>
            </ul>
        </div>

        <div class="contenu-principal">
            <div class="entete">
                <h1 class="titre-page">Gestion des salles</h1>
                <button class="bouton-ajouter-salle" id="bouton-ajouter-salle">
                    <i class="fas fa-plus"></i> Ajouter une salle
                </button>
            </div>
            
            <div class="conteneur-salles" id="conteneur-salles">
                <?php foreach ($salles as $salle): ?>
                <div class="carte-salle">
                    <div class="entete-carte">
                        <h3><?= htmlspecialchars($salle['nom']) ?></h3>
                        <div class="actions-salle">
                            <button class="bouton-action bouton-modifier" data-salle-id="<?= $salle['id'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bouton-action bouton-supprimer" data-salle-id="<?= $salle['id'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="corps-carte">
                        <div class="caracteristique-salle">
                            <i class="fas fa-<?= 
                                ($salle['type'] === 'salle informatique') ? 'desktop' : 
                                (($salle['type'] === 'laboratoire') ? 'flask' : 'chalkboard-teacher') 
                            ?>"></i>
                            <span>Type: <?= ucfirst($salle['type']) ?></span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-users"></i>
                            <span>Capacité: <?= $salle['capacité'] ?> places</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: <?= htmlspecialchars($salle['équipements']) ?></span>
                        </div>
                        <div class="caracteristique-salle">
    <form method="post" class="form-disponibilite">
        <input type="hidden" name="action" value="changer_disponibilite">
        <input type="hidden" name="id" value="<?= $salle['id'] ?>">
        <button type="submit" class="btn-disponibilite" style="background-color: <?= ($salle['disponibilite'] === 'disponible') ? '#28a745' : '#dc3545' ?>; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
            <?= ($salle['disponibilite'] === 'disponible') ? 'Disponible' : 'Indisponible' ?>
        </button>
    </form>
</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="modale" id="modale-salle">
        <div class="contenu-modale">
            <div class="entete-modale">
                <h3 class="titre-modale" id="titre-modale">Ajouter une salle</h3>
                <button class="bouton-fermer" id="fermer-modale">&times;</button>
            </div>
            <form id="formulaire-salle" method="post">
                <input type="hidden" id="id-salle" name="id">
                <input type="hidden" name="action" id="form-action" value="ajouter">
                <div class="groupe-formulaire">
                    <label for="nom-salle">Nom de la salle</label>
                    <input type="text" class="controle-formulaire" id="nom-salle" name="nom" required>
                </div>
                <div class="groupe-formulaire">
                    <label for="type-salle">Type de salle</label>
                    <select class="controle-formulaire" id="type-salle" name="type" required>
                        <option value="salle de cours">Salle de cours</option>
                        <option value="salle informatique">Salle informatique</option>
                        <option value="amphithéâtre">Amphithéâtre</option>
                        <option value="laboratoire">Laboratoire</option>
                    </select>
                </div>
                <div class="groupe-formulaire">
                    <label for="capacite-salle">Capacité</label>
                    <input type="number" class="controle-formulaire" id="capacite-salle" name="capacite" min="1" required>
                </div>
                <div class="groupe-formulaire">
                    <label for="equipement-salle">Équipements (séparés par des virgules)</label>
                    <textarea class="controle-formulaire" id="equipement-salle" name="equipement" rows="3"></textarea>
                </div>
                <div class="actions-formulaire">
                    <button type="button" class="bouton bouton-secondaire" id="bouton-annuler">Annuler</button>
                    <button type="submit" class="bouton bouton-primaire" id="bouton-enregistrer">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modale" id="modale-confirmation">
        <div class="contenu-modale">
            <div class="entete-modale">
                <h3 class="titre-modale">Confirmer la suppression</h3>
                <button class="bouton-fermer" id="fermer-modale-confirmation">&times;</button>
            </div>
            <p>Êtes-vous sûr de vouloir supprimer cette salle ? Cette action est irréversible.</p>
            <form id="formulaire-suppression" method="post">
                <input type="hidden" name="action" value="supprimer">
                <input type="hidden" id="id-suppression" name="id">
                <div class="actions-formulaire">
                    <button type="button" class="bouton bouton-secondaire" id="annuler-suppression">Annuler</button>
                    <button type="submit" class="bouton bouton-danger" id="confirmer-suppression">Supprimer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const boutonAjouterSalle = document.getElementById('bouton-ajouter-salle');
            const modaleSalle = document.getElementById('modale-salle');
            const modaleConfirmation = document.getElementById('modale-confirmation');
            const formulaireSalle = document.getElementById('formulaire-salle');
            const formulaireSuppression = document.getElementById('formulaire-suppression');
            const titreModale = document.getElementById('titre-modale');
            const idSalleInput = document.getElementById('id-salle');
            const idSuppressionInput = document.getElementById('id-suppression');
            const formActionInput = document.getElementById('form-action');
            const nomSalleInput = document.getElementById('nom-salle');
            const typeSalleInput = document.getElementById('type-salle');
            const capaciteSalleInput = document.getElementById('capacite-salle');
            const equipementSalleInput = document.getElementById('equipement-salle');
            const fermerModaleBtn = document.getElementById('fermer-modale');
            const fermerModaleConfirmationBtn = document.getElementById('fermer-modale-confirmation');
            const boutonAnnuler = document.getElementById('bouton-annuler');
            const annulerSuppressionBtn = document.getElementById('annuler-suppression');
            
            let enModeEdition = false;

            // Gestion de l'ajout/modification de salle
            boutonAjouterSalle.addEventListener('click', function() {
                enModeEdition = false;
                titreModale.textContent = 'Ajouter une salle';
                formActionInput.value = 'ajouter';
                formulaireSalle.reset();
                idSalleInput.value = '';
                modaleSalle.style.display = 'flex';
            });

            // Gestion des boutons modifier
            document.querySelectorAll('.bouton-modifier').forEach(btn => {
                btn.addEventListener('click', function() {
                    const salleId = this.getAttribute('data-salle-id');
                    const salleCard = this.closest('.carte-salle');
                    
                    enModeEdition = true;
                    titreModale.textContent = 'Modifier la salle';
                    formActionInput.value = 'modifier';
                    idSalleInput.value = salleId;
                    nomSalleInput.value = salleCard.querySelector('h3').textContent;
                    
                    const typeText = salleCard.querySelector('.caracteristique-salle:nth-child(1) span').textContent.replace('Type: ', '');
                    typeSalleInput.value = typeText.toLowerCase();
                    
                    const capaciteText = salleCard.querySelector('.caracteristique-salle:nth-child(2) span').textContent.replace('Capacité: ', '').replace(' places', '');
                    capaciteSalleInput.value = capaciteText;
                    
                    const equipementText = salleCard.querySelector('.caracteristique-salle:nth-child(3) span').textContent.replace('Équipements: ', '');
                    equipementSalleInput.value = equipementText;
                    
                    modaleSalle.style.display = 'flex';
                });
            });

            // Gestion des boutons supprimer
            document.querySelectorAll('.bouton-supprimer').forEach(btn => {
                btn.addEventListener('click', function() {
                    const salleId = this.getAttribute('data-salle-id');
                    idSuppressionInput.value = salleId;
                    modaleConfirmation.style.display = 'flex';
                });
            });

            // Fermeture des modales
            fermerModaleBtn.addEventListener('click', function() {
                modaleSalle.style.display = 'none';
            });

            fermerModaleConfirmationBtn.addEventListener('click', function() {
                modaleConfirmation.style.display = 'none';
            });

            boutonAnnuler.addEventListener('click', function() {
                modaleSalle.style.display = 'none';
            });

            annulerSuppressionBtn.addEventListener('click', function() {
                modaleConfirmation.style.display = 'none';
            });
        });
    </script>
</body>
</html>