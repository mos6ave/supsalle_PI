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
                <li><a href="adminstrateur_salle.html" class="active" ><i class="fa-solid fa-building"></i> Gestions des salles</a></li>
                <li><a href="reservations.html"><i class="fa-solid fa-calendar-days"></i>Gestion des réservations</a></li>
                <li><a href="utilisateurs.html"><i class="fa-solid fa-users-gear"></i>Gestion des utilisateurs</a></li>
                <li><a href="compte.html"><i class="fa-solid fa-gears"></i>Mon compte</a></li>
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

            </div>
        </div>
    </div>

    <div class="modale" id="modale-salle">
        <div class="contenu-modale">
            <div class="entete-modale">
                <h3 class="titre-modale" id="titre-modale">Ajouter une salle</h3>
                <button class="bouton-fermer" id="fermer-modale"></button>
            </div>
            <form id="formulaire-salle">
                <input type="hidden" id="id-salle">
                <div class="groupe-formulaire">
                    <label for="nom-salle">Nom de la salle</label>
                    <input type="text" class="controle-formulaire" id="nom-salle" required>
                </div>
                <div class="groupe-formulaire">
                    <label for="type-salle">Type de salle</label>
                    <select class="controle-formulaire" id="type-salle" required>
                        <option value="salle de cours">Salle de cours</option>
                        <option value="salle informatique">Salle informatique</option>
                        <option value="amphithéâtre">Amphithéâtre</option>
                        <option value="laboratoire">Laboratoire</option>
                    </select>
                </div>
                <div class="groupe-formulaire">
                    <label for="capacite-salle">Capacité</label>
                    <input type="number" class="controle-formulaire" id="capacite-salle" min="1" required>
                </div>
                <div class="groupe-formulaire">
                    <label for="equipement-salle">Équipements (séparés par des virgules)</label>
                    <textarea class="controle-formulaire" id="equipement-salle" rows="3"></textarea>
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
                <button class="bouton-fermer" id="fermer-modale-confirmation"></button>
            </div>
            <p>Êtes-vous sûr de vouloir supprimer cette salle ? Cette action est irréversible.</p>
            <div class="actions-formulaire">
                <button type="button" class="bouton bouton-secondaire" id="annuler-suppression">Annuler</button>
                <button type="button" class="bouton bouton-danger" id="confirmer-suppression">Supprimer</button>
            </div>
        </div>
    </div>

    <script>
        let donneesSalles = [
            {
                id: 1,
                nom: "Salle 103",
                type: "salle de cours",
                capacite: 80,
                equipement: "Projecteur, tableau blanc, système audio",
                icone: "chalkboard-teacher"
            },
            {
                id: 2,
                nom: "Salle RSS",
                type: "salle informatique",
                capacite: 25,
                equipement: "Ordinateurs, vidéoprojecteur",
                icone: "desktop"
            },
            {
                id: 3,
                nom: "Salle Namélozine",
                type: "salle de cours",
                capacite: 80,
                equipement: "Tableau interactif, système de visioconférence",
                icone: "chalkboard-teacher"
            },
            {
                id: 4,
                nom: "Salle Khawarami",
                type: "amphithéâtre",
                capacite: 130,
                equipement: "Système audiovisuel complet, climatisation",
                icone: "chalkboard-teacher"
            },
            {
                id: 5,
                nom: "Laboratoire B204",
                type: "laboratoire",
                capacite: 40,
                equipement: "Matériel de laboratoire complet, hottes de sécurité",
                icone: "flask"
            }
        ];

        const conteneurSalles = document.getElementById('conteneur-salles');
        const boutonAjouterSalle = document.getElementById('bouton-ajouter-salle');
        const modaleSalle = document.getElementById('modale-salle');
        const modaleConfirmation = document.getElementById('modale-confirmation');
        const formulaireSalle = document.getElementById('formulaire-salle');
        const titreModale = document.getElementById('titre-modale');
        const idSalleInput = document.getElementById('id-salle');
        const nomSalleInput = document.getElementById('nom-salle');
        const typeSalleInput = document.getElementById('type-salle');
        const capaciteSalleInput = document.getElementById('capacite-salle');
        const equipementSalleInput = document.getElementById('equipement-salle');
        const fermerModaleBtn = document.getElementById('fermer-modale');
        const fermerModaleConfirmationBtn = document.getElementById('fermer-modale-confirmation');
        const boutonAnnuler = document.getElementById('bouton-annuler');
        const annulerSuppressionBtn = document.getElementById('annuler-suppression');
        const confirmerSuppressionBtn = document.getElementById('confirmer-suppression');
        
        let idSalleCourante = null;
        let enModeEdition = false;

        function afficherSalles() {
            conteneurSalles.innerHTML = '';

            if (donneesSalles.length === 0) {
                conteneurSalles.innerHTML = '<p class="aucun-resultat">Aucune salle disponible.</p>';
                return;
            }

            donneesSalles.forEach(salle => {
                const carteSalle = document.createElement('div');
                carteSalle.className = 'carte-salle';
                carteSalle.innerHTML = `
                    <div class="entete-carte">
                        <h3>${salle.nom}</h3>
                        <div class="actions-salle">
                            <button class="bouton-action bouton-modifier" data-salle-id="${salle.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bouton-action bouton-supprimer" data-salle-id="${salle.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="corps-carte">
                        <div class="caracteristique-salle">
                            <i class="fas fa-${salle.icone}"></i>
                            <span>Type: ${salle.type.charAt(0).toUpperCase() + salle.type.slice(1)}</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-users"></i>
                            <span>Capacité: ${salle.capacite} places</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: ${salle.equipement}</span>
                        </div>
                    </div>
                `;
                conteneurSalles.appendChild(carteSalle);
            });

            document.querySelectorAll('.bouton-modifier').forEach(btn => {
                btn.addEventListener('click', function() {
                    const salleId = parseInt(this.getAttribute('data-salle-id'));
                    modifierSalle(salleId);
                });
            });

            document.querySelectorAll('.bouton-supprimer').forEach(btn => {
                btn.addEventListener('click', function() {
                    const salleId = parseInt(this.getAttribute('data-salle-id'));
                    afficherConfirmationSuppression(salleId);
                });
            });
        }

        function afficherModaleAjout() {
            enModeEdition = false;
            titreModale.textContent = 'Ajouter une salle';
            formulaireSalle.reset();
            idSalleInput.value = '';
            modaleSalle.style.display = 'flex';
        }

        function modifierSalle(salleId) {
            const salle = donneesSalles.find(s => s.id === salleId);
            if (!salle) return;

            enModeEdition = true;
            idSalleCourante = salleId;
            titreModale.textContent = 'Modifier la salle';
            idSalleInput.value = salle.id;
            nomSalleInput.value = salle.nom;
            typeSalleInput.value = salle.type;
            capaciteSalleInput.value = salle.capacite;
            equipementSalleInput.value = salle.equipement;
            modaleSalle.style.display = 'flex';
        }

        function enregistrerSalle(e) {
            e.preventDefault();

            const donneesSalle = {
                nom: nomSalleInput.value.trim(),
                type: typeSalleInput.value,
                capacite: parseInt(capaciteSalleInput.value),
                equipement: equipementSalleInput.value.trim(),
                icone: obtenirIconeParType(typeSalleInput.value)
            };

            if (enModeEdition) {
                const index = donneesSalles.findIndex(s => s.id === idSalleCourante);
                if (index !== -1) {
                    donneesSalle.id = idSalleCourante;
                    donneesSalles[index] = donneesSalle;
                }
            } else {
                const nouvelId = donneesSalles.length > 0 ? Math.max(...donneesSalles.map(s => s.id)) + 1 : 1;
                donneesSalle.id = nouvelId;
                donneesSalles.push(donneesSalle);
            }

            afficherSalles();
            fermerModaleSalle();
        }

        function obtenirIconeParType(type) {
            switch(type) {
                case 'salle informatique': return 'desktop';
                case 'laboratoire': return 'flask';
                case 'amphithéâtre': return 'chalkboard-teacher';
                default: return 'chalkboard-teacher';
            }
        }

        function fermerModaleSalle() {
            modaleSalle.style.display = 'none';
        }

        function afficherConfirmationSuppression(salleId) {
            idSalleCourante = salleId;
            modaleConfirmation.style.display = 'flex';
        }

        function supprimerSalle() {
            donneesSalles = donneesSalles.filter(salle => salle.id !== idSalleCourante);
            afficherSalles();
            fermerModaleConfirmation();
        }

        function fermerModaleConfirmation() {
            modaleConfirmation.style.display = 'none';
        }

        boutonAjouterSalle.addEventListener('click', afficherModaleAjout);
        formulaireSalle.addEventListener('submit', enregistrerSalle);
        fermerModaleBtn.addEventListener('click', fermerModaleSalle);
        fermerModaleConfirmationBtn.addEventListener('click', fermerModaleConfirmation);
        boutonAnnuler.addEventListener('click', fermerModaleSalle);
        annulerSuppressionBtn.addEventListener('click', fermerModaleConfirmation);
        confirmerSuppressionBtn.addEventListener('click', supprimerSalle);

      
        document.addEventListener('DOMContentLoaded', afficherSalles);
    </script>
</body>
</html>
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
                <li><a href="adminstrateur_salle.html" class="active" ><i class="fa-solid fa-building"></i> Gestions des salles</a></li>
                <li><a href="reservations.html"><i class="fa-solid fa-calendar-days"></i>Gestion des réservations</a></li>
                <li><a href="utilisateurs.html"><i class="fa-solid fa-users-gear"></i>Gestion des utilisateurs</a></li>
                <li><a href="compte.html"><i class="fa-solid fa-gears"></i>Mon compte</a></li>
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

            </div>
        </div>
    </div>

    <div class="modale" id="modale-salle">
        <div class="contenu-modale">
            <div class="entete-modale">
                <h3 class="titre-modale" id="titre-modale">Ajouter une salle</h3>
                <button class="bouton-fermer" id="fermer-modale"></button>
            </div>
            <form id="formulaire-salle">
                <input type="hidden" id="id-salle">
                <div class="groupe-formulaire">
                    <label for="nom-salle">Nom de la salle</label>
                    <input type="text" class="controle-formulaire" id="nom-salle" required>
                </div>
                <div class="groupe-formulaire">
                    <label for="type-salle">Type de salle</label>
                    <select class="controle-formulaire" id="type-salle" required>
                        <option value="salle de cours">Salle de cours</option>
                        <option value="salle informatique">Salle informatique</option>
                        <option value="amphithéâtre">Amphithéâtre</option>
                        <option value="laboratoire">Laboratoire</option>
                    </select>
                </div>
                <div class="groupe-formulaire">
                    <label for="capacite-salle">Capacité</label>
                    <input type="number" class="controle-formulaire" id="capacite-salle" min="1" required>
                </div>
                <div class="groupe-formulaire">
                    <label for="equipement-salle">Équipements (séparés par des virgules)</label>
                    <textarea class="controle-formulaire" id="equipement-salle" rows="3"></textarea>
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
                <button class="bouton-fermer" id="fermer-modale-confirmation"></button>
            </div>
            <p>Êtes-vous sûr de vouloir supprimer cette salle ? Cette action est irréversible.</p>
            <div class="actions-formulaire">
                <button type="button" class="bouton bouton-secondaire" id="annuler-suppression">Annuler</button>
                <button type="button" class="bouton bouton-danger" id="confirmer-suppression">Supprimer</button>
            </div>
        </div>
    </div>

    <script>
        let donneesSalles = [
            {
                id: 1,
                nom: "Salle 103",
                type: "salle de cours",
                capacite: 80,
                equipement: "Projecteur, tableau blanc, système audio",
                icone: "chalkboard-teacher"
            },
            {
                id: 2,
                nom: "Salle RSS",
                type: "salle informatique",
                capacite: 25,
                equipement: "Ordinateurs, vidéoprojecteur",
                icone: "desktop"
            },
            {
                id: 3,
                nom: "Salle Namélozine",
                type: "salle de cours",
                capacite: 80,
                equipement: "Tableau interactif, système de visioconférence",
                icone: "chalkboard-teacher"
            },
            {
                id: 4,
                nom: "Salle Khawarami",
                type: "amphithéâtre",
                capacite: 130,
                equipement: "Système audiovisuel complet, climatisation",
                icone: "chalkboard-teacher"
            },
            {
                id: 5,
                nom: "Laboratoire B204",
                type: "laboratoire",
                capacite: 40,
                equipement: "Matériel de laboratoire complet, hottes de sécurité",
                icone: "flask"
            }
        ];

        const conteneurSalles = document.getElementById('conteneur-salles');
        const boutonAjouterSalle = document.getElementById('bouton-ajouter-salle');
        const modaleSalle = document.getElementById('modale-salle');
        const modaleConfirmation = document.getElementById('modale-confirmation');
        const formulaireSalle = document.getElementById('formulaire-salle');
        const titreModale = document.getElementById('titre-modale');
        const idSalleInput = document.getElementById('id-salle');
        const nomSalleInput = document.getElementById('nom-salle');
        const typeSalleInput = document.getElementById('type-salle');
        const capaciteSalleInput = document.getElementById('capacite-salle');
        const equipementSalleInput = document.getElementById('equipement-salle');
        const fermerModaleBtn = document.getElementById('fermer-modale');
        const fermerModaleConfirmationBtn = document.getElementById('fermer-modale-confirmation');
        const boutonAnnuler = document.getElementById('bouton-annuler');
        const annulerSuppressionBtn = document.getElementById('annuler-suppression');
        const confirmerSuppressionBtn = document.getElementById('confirmer-suppression');
        
        let idSalleCourante = null;
        let enModeEdition = false;

        function afficherSalles() {
            conteneurSalles.innerHTML = '';

            if (donneesSalles.length === 0) {
                conteneurSalles.innerHTML = '<p class="aucun-resultat">Aucune salle disponible.</p>';
                return;
            }

            donneesSalles.forEach(salle => {
                const carteSalle = document.createElement('div');
                carteSalle.className = 'carte-salle';
                carteSalle.innerHTML = `
                    <div class="entete-carte">
                        <h3>${salle.nom}</h3>
                        <div class="actions-salle">
                            <button class="bouton-action bouton-modifier" data-salle-id="${salle.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bouton-action bouton-supprimer" data-salle-id="${salle.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="corps-carte">
                        <div class="caracteristique-salle">
                            <i class="fas fa-${salle.icone}"></i>
                            <span>Type: ${salle.type.charAt(0).toUpperCase() + salle.type.slice(1)}</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-users"></i>
                            <span>Capacité: ${salle.capacite} places</span>
                        </div>
                        <div class="caracteristique-salle">
                            <i class="fas fa-tools"></i>
                            <span>Équipements: ${salle.equipement}</span>
                        </div>
                    </div>
                `;
                conteneurSalles.appendChild(carteSalle);
            });

            document.querySelectorAll('.bouton-modifier').forEach(btn => {
                btn.addEventListener('click', function() {
                    const salleId = parseInt(this.getAttribute('data-salle-id'));
                    modifierSalle(salleId);
                });
            });

            document.querySelectorAll('.bouton-supprimer').forEach(btn => {
                btn.addEventListener('click', function() {
                    const salleId = parseInt(this.getAttribute('data-salle-id'));
                    afficherConfirmationSuppression(salleId);
                });
            });
        }

        function afficherModaleAjout() {
            enModeEdition = false;
            titreModale.textContent = 'Ajouter une salle';
            formulaireSalle.reset();
            idSalleInput.value = '';
            modaleSalle.style.display = 'flex';
        }

        function modifierSalle(salleId) {
            const salle = donneesSalles.find(s => s.id === salleId);
            if (!salle) return;

            enModeEdition = true;
            idSalleCourante = salleId;
            titreModale.textContent = 'Modifier la salle';
            idSalleInput.value = salle.id;
            nomSalleInput.value = salle.nom;
            typeSalleInput.value = salle.type;
            capaciteSalleInput.value = salle.capacite;
            equipementSalleInput.value = salle.equipement;
            modaleSalle.style.display = 'flex';
        }

        function enregistrerSalle(e) {
            e.preventDefault();

            const donneesSalle = {
                nom: nomSalleInput.value.trim(),
                type: typeSalleInput.value,
                capacite: parseInt(capaciteSalleInput.value),
                equipement: equipementSalleInput.value.trim(),
                icone: obtenirIconeParType(typeSalleInput.value)
            };

            if (enModeEdition) {
                const index = donneesSalles.findIndex(s => s.id === idSalleCourante);
                if (index !== -1) {
                    donneesSalle.id = idSalleCourante;
                    donneesSalles[index] = donneesSalle;
                }
            } else {
                const nouvelId = donneesSalles.length > 0 ? Math.max(...donneesSalles.map(s => s.id)) + 1 : 1;
                donneesSalle.id = nouvelId;
                donneesSalles.push(donneesSalle);
            }

            afficherSalles();
            fermerModaleSalle();
        }

        function obtenirIconeParType(type) {
            switch(type) {
                case 'salle informatique': return 'desktop';
                case 'laboratoire': return 'flask';
                case 'amphithéâtre': return 'chalkboard-teacher';
                default: return 'chalkboard-teacher';
            }
        }

        function fermerModaleSalle() {
            modaleSalle.style.display = 'none';
        }

        function afficherConfirmationSuppression(salleId) {
            idSalleCourante = salleId;
            modaleConfirmation.style.display = 'flex';
        }

        function supprimerSalle() {
            donneesSalles = donneesSalles.filter(salle => salle.id !== idSalleCourante);
            afficherSalles();
            fermerModaleConfirmation();
        }

        function fermerModaleConfirmation() {
            modaleConfirmation.style.display = 'none';
        }

        boutonAjouterSalle.addEventListener('click', afficherModaleAjout);
        formulaireSalle.addEventListener('submit', enregistrerSalle);
        fermerModaleBtn.addEventListener('click', fermerModaleSalle);
        fermerModaleConfirmationBtn.addEventListener('click', fermerModaleConfirmation);
        boutonAnnuler.addEventListener('click', fermerModaleSalle);
        annulerSuppressionBtn.addEventListener('click', fermerModaleConfirmation);
        confirmerSuppressionBtn.addEventListener('click', supprimerSalle);

      
        document.addEventListener('DOMContentLoaded', afficherSalles);
    </script>
</body>
</html>
