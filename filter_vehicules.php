<?php
include 'DB.php';

$bd = new DB();
$connect = $bd->connect();

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT id_vehicule, marque, modele, prixparjour, img FROM vehicules WHERE disponible = 1";

if ($categoryId > 0) {
    $sql .= " AND id_category = ?";
}

if ($searchQuery) {
    $sql .= " AND (marque LIKE ? OR modele LIKE ? OR annee LIKE ?)";
}

$stmt = $connect->prepare($sql);

if ($categoryId > 0 && $searchQuery) {
    $likeQuery = '%' . $searchQuery . '%';
    $stmt->bind_param("isss", $categoryId, $likeQuery, $likeQuery, $likeQuery);
} elseif ($categoryId > 0) {
    $stmt->bind_param("i", $categoryId);
} elseif ($searchQuery) {
    $likeQuery = '%' . $searchQuery . '%';
    $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
}

$stmt->execute();
$result = $stmt->get_result();

$vehicules = [];
while ($row = $result->fetch_assoc()) {
    $vehicules[] = $row;
}
$stmt->close();

foreach ($vehicules as $vehicule) {
    echo '<div class="bg-white shadow-md rounded-lg overflow-hidden">';
    echo '<img src="' . $vehicule['img'] . '" alt="Image de ' . $vehicule['marque'] . '" class="w-full h-48 object-cover">';
    echo '<div class="p-4">';
    echo '<h2 class="text-xl font-bold">' . $vehicule['marque'] . ' ' . $vehicule['modele'] . '</h2>';
    echo '<p class="text-gray-700">Prix par jour: ' . $vehicule['prixparjour'] . ' £</p>';
    echo '<a href="detail_vehicule.php?id=' . $vehicule['id_vehicule'] . '" class="bg-blue-500 text-white px-4 py-2 mt-4 inline-block rounded">Voir Détails</a>';
    echo '</div>';
    echo '</div>';
}

$connect->close();
?>

<!-- <?php
include 'DB.php';

$bd = new DB();
$connect = $bd->connect();

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT id_vehicule, marque, modele, prixparjour, img FROM vehicules WHERE disponible = 1";

if ($categoryId > 0) {
    $sql .= " AND id_category = ?";
}

if ($searchQuery) {
    $sql .= " AND (marque LIKE ? OR modele LIKE ? OR annee LIKE ?)";
}

$stmt = $connect->prepare($sql);

if ($categoryId > 0 && $searchQuery) {
    $likeQuery = '%' . $searchQuery . '%';
    $stmt->bind_param("isss", $categoryId, $likeQuery, $likeQuery, $likeQuery);
} elseif ($categoryId > 0) {
    $stmt->bind_param("i", $categoryId);
} elseif ($searchQuery) {
    $likeQuery = '%' . $searchQuery . '%';
    $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
}

$stmt->execute();
$result = $stmt->get_result();

$vehicules = [];
while ($row = $result->fetch_assoc()) {
    $vehicules[] = [
        '<img src="' . $row['img'] . '" alt="Image de ' . $row['marque'] . '" class="w-full h-48 object-cover">',
        $row['marque'],
        $row['modele'],
        $row['prixparjour'] . ' £',
        '<a href="detail_vehicule.php?id=' . $row['id_vehicule'] . '" class="bg-blue-500 text-white px-4 py-2 mt-4 inline-block rounded">Voir Détails</a>'
    ];
}
$stmt->close();

echo json_encode(['data' => $vehicules]);

$connect->close();
?> -->