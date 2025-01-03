<?php 
include 'DB.php';

$bd = new DB();
$connect = $bd->connect();

$id_vehicule = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_client = $_POST['id_client'];
    $datedebut = $_POST['datedebut'];
    $datefin = $_POST['datefin'];
    $lieuPriseCharge = $_POST['lieuPriseCharge'];
    $prixtotal = $_POST['prixtotal'];

    $sql = "INSERT INTO reservations (id_vehicule, id_client, datedebut, datefin, lieuPriseCharge, prixtotal) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("iisssd", $id_vehicule, $id_client, $datedebut, $datefin, $lieuPriseCharge, $prixtotal);
    if ($stmt->execute()) {
        echo "<script>alert('Réservation effectuée avec succès'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la réservation');</script>";
    } 
}

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
    <title>Drive & Loc - Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header.php'; ?>
    <div class="container mx-auto mt-10">
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
            <h2 class="text-2xl font-bold mb-4">Réserver <?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?></h2>
            <form method="post" action="">
                <input type="hidden" name="id_client" value="1"> <!-- ID client, à remplacer par la session du client connecté -->
                <div class="mb-4">
                    <label for="datedebut" class="block text-gray-700">Date de début:</label>
                    <input type="date" id="datedebut" name="datedebut" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="datefin" class="block text-gray-700">Date de fin:</label>
                    <input type="date" id="datefin" name="datefin" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="lieuPriseCharge" class="block text-gray-700">Lieu de prise en charge:</label>
                    <input type="text" id="lieuPriseCharge" name="lieuPriseCharge" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="prixtotal" class="block text-gray-700">Prix total:</label>
                    <input type="number" id="prixtotal" name="prixtotal" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Confirmer la Réservation</button>
            </form>
        </div>
    </div>
</body>
</html> 