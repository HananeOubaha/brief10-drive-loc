<?php
include 'DB.php';

$bd = new DB();
$connect = $bd->connect();

$id_vehicule = $_GET['id'];

$sql = "SELECT * FROM vehicules WHERE id_vehicule = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $id_vehicule);
$stmt->execute();
$result = $stmt->get_result();
$vehicule = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive & Loc - Détails du Véhicule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cyan-100 ">
    <?php include 'header.php'; ?>
    <div class="container mx-auto mt-24">
        <div class="bg-white shadow-md rounded-lg overflow-hidden px-4 mx-64">
            <img src="<?php echo $vehicule['img']; ?>" alt="Image de <?php echo $vehicule['marque']; ?>" class="w-full h-80 object-cover">
            <div class="p-6">
                <h2 class="text-2xl font-bold"><?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?></h2>
                <p class="text-gray-700">Année: <?php echo $vehicule['annee']; ?></p>
                <p class="text-gray-700">Prix par jour: <?php echo $vehicule['prixparjour']; ?> €</p>
                <p class="text-gray-700">Disponible: <?php echo $vehicule['disponible'] ? 'Oui' : 'Non'; ?></p>
                <a href="reservationc.php?id=<?php echo $vehicule['id_vehicule']; ?>" class="bg-cyan-900 hover:bg-cyan-500 text-white px-4 py-2 mt-4 inline-block rounded text-white px-4 py-2 mt-4 inline-block rounded">Réserver</a>
                <a href="ajouter_avis.php?id=<?php echo $vehicule['id_vehicule']; ?>" class ="bg-cyan-900 hover:bg-cyan-500 text-white px-4 py-2 mt-4 inline-block rounded">Donner votre avis</a>
            </div>
        </div>
    </div>
</body>
</html>