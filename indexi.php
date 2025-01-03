<?php
include 'DB.php';

$bd = new DB();
$connect = $bd->connect();

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive & Loc - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header.php'; ?>
    <div class="container mx-auto mt-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-24">
            <?php foreach ($vehicules as $vehicule): ?>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <img src="<?php echo $vehicule['img']; ?>" alt="Image de <?php echo $vehicule['marque']; ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-bold"><?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?></h2>
                        <p class="text-gray-700">Prix par jour: <?php echo $vehicule['prixparjour']; ?> £</p>
                        <a href="detail_vehicule.php?id=<?php echo $vehicule['id_vehicule']; ?>" class="bg-blue-500 text-white px-4 py-2 mt-4 inline-block rounded">Voir Détails</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-10 flex justify-center space-x-4">
            <?php if ($pageActuelle > 1): ?>
                <a href="?page=<?php echo $pageActuelle - 1; ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="px-4 py-2 <?php echo $i == $pageActuelle ? 'bg-blue-500 text-white' : 'bg-gray-300'; ?> rounded hover:bg-gray-400">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($pageActuelle < $totalPages): ?>
                <a href="?page=<?php echo $pageActuelle + 1; ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Suivant</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
