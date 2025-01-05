<?php
session_start();
include 'DB.php';
$bd = new DB();
$connect = $bd->connect();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idclient'])) {
    echo "<script>alert('Veuillez vous connecter pour effectuer une réservation'); 
          window.location.href='index.php';</script>";
    exit;
}

$id_vehicule = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_client = $_SESSION['idclient'];
    $datedebut = $_POST['datedebut'];
    $datefin = $_POST['datefin'];
    $lieuPriseCharge = $_POST['lieuPriseCharge'];
    $prixtotal = $_POST['prixtotal'];

    // Vérifications des données soumises
    if (empty($datedebut) || empty($datefin) || empty($lieuPriseCharge) || empty($prixtotal)) {
        echo "<script>alert('Tous les champs sont obligatoires');</script>";
    } else {
        // Vérifier si le client existe
        $check_client = "SELECT id_client FROM clients WHERE id_client = ?";
        $stmt_check = $connect->prepare($check_client);
        $stmt_check->bind_param("i", $id_client);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows === 0) {
            echo "<script>alert('Client non trouvé dans la base de données');</script>";
            exit;
        }

        // Vérifier si les dates sont valides
        $date_debut = strtotime($datedebut);
        $date_fin = strtotime($datefin);
        $date_aujourdhui = strtotime(date('Y-m-d'));

        if ($date_debut < $date_aujourdhui) {
            echo "<script>alert('La date de début ne peut pas être dans le passé');</script>";
            exit;
        }

        if ($date_fin <= $date_debut) {
            echo "<script>alert('La date de fin doit être postérieure à la date de début');</script>";
            exit;
        }

        // Vérifier si le véhicule est disponible pour ces dates
        $check_disponibilite = "SELECT id_reservation FROM reservations 
            WHERE id_vehicule = ? AND 
            ((datedebut BETWEEN ? AND ?) OR 
            (datefin BETWEEN ? AND ?) OR 
            (datedebut <= ? AND datefin >= ?))";
        
        $stmt_dispo = $connect->prepare($check_disponibilite);
        $stmt_dispo->bind_param("issssss", $id_vehicule, $datedebut, $datefin, $datedebut, $datefin, $datedebut, $datefin);
        $stmt_dispo->execute();
        $result_dispo = $stmt_dispo->get_result();

        if ($result_dispo->num_rows > 0) {
            echo "<script>alert('Ce véhicule n'est pas disponible pour les dates sélectionnées');</script>";
            exit;
        }

        // Insérer la réservation
        $sql = "INSERT INTO reservations (id_vehicule, id_client, datedebut, datefin, lieuPriseCharge, prixtotal, statut) 
                VALUES (?, ?, ?, ?, ?, ?, 'En attente')";
        $stmt = $connect->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("iisssd", $id_vehicule, $id_client, $datedebut, $datefin, $lieuPriseCharge, $prixtotal);
            if ($stmt->execute()) {
                echo "<script>alert('Réservation effectuée avec succès'); window.location.href='indexi.php';</script>";
            } else {
                echo "<script>alert('Erreur lors de la réservation : " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Erreur de préparation de la requête : " . $connect->error . "');</script>";
        }
    }
}

// Récupérer les informations du véhicule
$sql = "SELECT * FROM vehicules WHERE id_vehicule = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $id_vehicule);
$stmt->execute();
$result = $stmt->get_result();
$vehicule = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
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
            <h2 class="text-2xl font-bold mb-4">Réserver <?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?></h2>
            
            <form method="post" action="" class="space-y-4">
                <div class="mb-4">
                    <label for="datedebut" class="block text-gray-700">Date de début:</label>
                    <input type="date" id="datedebut" name="datedebut" 
                           min="<?php echo date('Y-m-d'); ?>"
                           class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                
                <div class="mb-4">
                    <label for="datefin" class="block text-gray-700">Date de fin:</label>
                    <input type="date" id="datefin" name="datefin"
                           min="<?php echo date('Y-m-d'); ?>"
                           class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                
                <div class="mb-4">
                    <label for="lieuPriseCharge" class="block text-gray-700">Lieu de prise en charge:</label>
                    <input type="text" id="lieuPriseCharge" name="lieuPriseCharge"
                           class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                
                <div class="mb-4">
                    <label for="prixtotal" class="block text-gray-700">Prix total:</label>
                    <input type="number" step="0.01" id="prixtotal" name="prixtotal"
                           min="0"
                           class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Confirmer la Réservation
                </button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateDebut = document.getElementById('datedebut');
        const dateFin = document.getElementById('datefin');

        // Mettre à jour la date minimale de fin lorsque la date de début change
        dateDebut.addEventListener('change', function() {
            dateFin.min = this.value;
            if (dateFin.value && dateFin.value < this.value) {
                dateFin.value = this.value;
            }
        });
    });
    </script>
</body>
</html>