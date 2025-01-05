<?php
include 'DB.php';

$bd = new DB();
$connect = $bd->connect();

// Récupérer toutes les catégories disponibles
$sqlCategories = "SELECT id_category, category_name FROM categories";
$resultCategories = $connect->query($sqlCategories);
$categories = [];
if ($resultCategories->num_rows > 0) {
    while ($row = $resultCategories->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Nombre de véhicules par page
$vehiculesParPage = 6;

// Page actuelle (par défaut à 1 si non définie dans l'URL)
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calcul de l'offset
$offset = ($pageActuelle - 1) * $vehiculesParPage;

// Récupérer le nombre total de véhicules disponibles
$sqlTotal = "SELECT COUNT(*) as total FROM vehicules WHERE disponible = 1";
$resultTotal = $connect->query($sqlTotal);
$totalVehicules = $resultTotal->fetch_assoc()['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalVehicules / $vehiculesParPage);

// Récupérer les véhicules de la page courante
$sql = "SELECT id_vehicule, marque, modele, prixparjour, img FROM vehicules WHERE disponible = 1 LIMIT $vehiculesParPage OFFSET $offset";
$result = $connect->query($sql);

$vehicules = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vehicules[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive & Loc - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-cyan-100">
    <?php include 'header.php'; ?>

    <div class="container mx-auto mt-10">
        <!-- Formulaire de filtrage des catégories et barre de recherche -->
        <div class="flex justify-center mt-24 space-x-4">
            <select id="categoryFilter" class="bg-cyan-800 border p-2 rounded text-white">
                <option value="0">Toutes les catégories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id_category']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="searchInput" placeholder="Rechercher un véhicule..." class="bg-cyan-800 border text-white p-2 rounded w-1/3">
        </div>

        <div id="vehiculesContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <?php foreach ($vehicules as $vehicule): ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <img src="<?php echo $vehicule['img']; ?>" alt="Image de <?php echo $vehicule['marque']; ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-bold"><?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?></h2>
                        <p class="text-cyan-900">Prix par jour: <?php echo $vehicule['prixparjour']; ?> £</p>
                        <a href="detail_vehicule.php?id=<?php echo $vehicule['id_vehicule']; ?>" class="bg-cyan-900 text-white px-4 py-2 mt-4 inline-block rounded">Voir Détails</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-10 flex justify-center space-x-4 text-white">
            <?php if ($pageActuelle > 1): ?>
                <a href="?page=<?php echo $pageActuelle - 1; ?>" class="px-4 py-2 bg-cyan-900 rounded hover:bg-cyan-500">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="px-4 py-2 <?php echo $i == $pageActuelle ? 'bg-cyan-900 text-white' : 'bg-cyan-900'; ?> rounded hover:bg-cyan-500">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($pageActuelle < $totalPages): ?>
                <a href="?page=<?php echo $pageActuelle + 1; ?>" class="px-4 py-2 bg-cyan-900 rounded hover:bg-cyan-500">Suivant</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function fetchVehicules() {
                var categoryId = $('#categoryFilter').val();
                var searchQuery = $('#searchInput').val();
                $.ajax({
                    url: 'filter_vehicules.php',
                    type: 'GET',
                    data: { category_id: categoryId, search: searchQuery },
                    success: function(response) {
                        $('#vehiculesContainer').html(response);
                    }
                });
            }

            $('#categoryFilter').change(fetchVehicules);
            $('#searchInput').keyup(fetchVehicules);
        });
    </script>
</body>
</html>