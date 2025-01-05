# Drive & Loc - Gestion de location de voitures

Bienvenue dans le module de gestion de location de voitures pour le site web **Drive & Loc**. Ce projet vise à fournir une plateforme créative et fonctionnelle permettant aux clients de parcourir et réserver des véhicules selon leurs besoins.

---

## Fonctionnalités principales

### Client

1. **Connexion**
   - Les clients doivent se connecter pour accéder à la plateforme de location.

2. **Exploration des véhicules**
   - Parcourir les différentes catégories de véhicules disponibles.

3. **Détails des véhicules**
   - Afficher les informations détaillées d'un véhicule (modèle, prix, disponibilité, etc.).

4. **Réservation**
   - Réserver un véhicule en précisant les dates et lieux de prise en charge.

5. **Recherche et filtres**
   - Rechercher un véhicule spécifique par son modèle ou ses caractéristiques.
   - Filtrer les véhicules disponibles par catégorie sans rafraîchir la page.

6. **Avis et évaluations**
   - Ajouter un avis ou une évaluation sur un véhicule loué.
   - Modifier ou supprimer ses propres avis (Soft Delete).

7. **Pagination**
   - Lister les véhicules disponibles par pages.
     - **Version 1 :** Pagination à l'aide de PHP.
     - **Version 2 :** Utilisation de DataTable pour une gestion interactive et dynamique.

---

### Administrateur

1. **Gestion des véhicules et catégories**
   - Ajouter plusieurs véhicules ou catégories à la fois (insertion en masse).

2. **Gestion avancée**
   - Gérer les réservations, les véhicules, les avis et les catégories via un dashboard avec statistiques.

---

### Extras Backend

1. **Vue SQL : Liste des véhicules**
   - Une vue SQL "ListeVehicules" combine les informations nécessaires pour afficher la liste des véhicules, y compris les détails des catégories, les évaluations associées et leur disponibilité.

2. **Procédure stockée : Ajouter une réservation**
   - Une procédure stockée "AjouterReservation" prend en compte les paramètres nécessaires pour effectuer une réservation sur un véhicule spécifique.

---

## Technologies utilisées

- **Backend :** PHP (POO), SQL
- **Frontend :** HTML, CSS, JavaScript
- **Base de données :** MySQL
- **Dynamique :** DataTable (optionnel pour pagination interactive)

---

## Installation et configuration

1. **Clonez le projet :**
   ```bash
   git clone https://github.com/HananeOubaha/brief10-drive-loc.git
   ```

2. **Importez la base de données :**
   - Utilisez le fichier SQL fourni pour créer les tables nécessaires, y compris la vue "ListeVehicules" et la procédure stockée "AjouterReservation".

3. **Configurez le projet :**
   - Mettez à jour les informations de connexion à la base de données dans le fichier `config.php`.

4. **Lancez le serveur local :**
   ```bash
   php -S localhost:8000
   ```

5. **Accédez au site :**
   - Ouvrez [http://localhost:8000](http://localhost:8000) dans votre navigateur.

---

## Contributeurs

- **Hanane OUBAHA** - Développeur backend
- **HANANE OUBAHA** - Développeur frontend
- **HANANE OUBAHA** - Analyste de données

---
