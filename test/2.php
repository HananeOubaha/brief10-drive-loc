<?php
session_start();
require_once 'DB.php';
require_once 'Client.php';
require_once 'Reservation.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit();
}

$db = new DB();
$conn = $db->connect();

$client = new Client($conn);
$clientInfo = $client->getClientInfo($_SESSION['client_id']);

$reservation = new Reservation($conn);
$reservations = $reservation->getReservationsByClient($_SESSION['client_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client - Drive & Loc</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="1.php">Drive & Loc</a>
        <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link" href="logout.php">Déconnexion</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Bienvenue, <?php echo $clientInfo['prenom'] . ' ' . $clientInfo['nom']; ?></h1>
        
        <h2 class="mt-4">Vos réservations</h2>
        <?php if (empty($reservations)): ?>
            <p>Vous n'avez pas encore de réservations.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Statut</th>
                        <th>Prix total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo $reservation['marque'] . ' ' . $reservation['modele']; ?></td>
                            <td><?php echo $reservation['datedebut']; ?></td>
                            <td><?php echo $reservation['datefin']; ?></td>
                            <td><?php echo $reservation['statut']; ?></td>
                            <td><?php echo $reservation['prixtotal']; ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <a href="vehicules.php" class="btn btn-primary mt-3">Réserver un véhicule</a>
    </div>
</body>
</html>