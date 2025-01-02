<?php
session_start();
require_once 'DB.php';
require_once 'Vehicule.php';
require_once 'Reservation.php';

if (!isset($_SESSION['client_id']) || !isset($_GET['id'])) {
    header('Location: 1.php');
    exit();
}

$db = new DB();
$conn = $db->connect();

$vehicule = new Vehicule($conn);
$vehiculeDetails = $vehicule->getVehiculeById($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation = new Reservation($conn);
    $datedebut = $_POST['datedebut'];
    $datefin = $_POST['datefin'];
    $lieuPriseCharge = $_POST['lieuPriseCharge'];
    
    $jours = (strtotime($datefin) - strtotime($datedebut)) / (60 * 60 * 24);
    $prixtotal = $jours * $vehiculeDetails['prixparjour'];

    if ($reservation->createReservation($_GET['id'], $_SESSION['client_id'], $datedebut, $datefin, $lieuPriseCharge, $prixtotal)) {
        header('Location: 2.php');
        exit();
    } else {
        $error = "Une erreur est survenue lors de la réservation";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Drive & Loc</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="1.php">Drive & Loc</a>
        <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link" href="2.php">Espace Client</a>
            <a class="nav-item nav-link" href="logout.php">Déconnexion</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Réservation de <?php echo $vehiculeDetails['marque'] . ' ' . $vehiculeDetails['modele']; ?></h1>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="datedebut">Date de début</label>
                <input type="date" class="form-control" id="datedebut" name="datedebut" required>
            </div>
            <div class="form-group">
                <label for="datefin">Date de fin</label>
                <input type="date" class="form-control" id="datefin" name="datefin" required>
            </div>
            <div class="form-group">
                <label for="lieuPriseCharge">Lieu de prise en charge</label>
                <input type="text" class="form-control" id="lieuPriseCharge" name="lieuPriseCharge" required>
            </div>
            <button type="submit" class="btn btn-primary">Réserver</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>