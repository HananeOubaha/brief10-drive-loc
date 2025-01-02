<?php
session_start();
require_once 'DB.php';
require_once 'Vehicule.php';

$db = new DB();
$conn = $db->connect();

$vehicule = new Vehicule($conn);
$vehicules = $vehicule->getAllVehicules();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive & Loc - Location de véhicules</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="1.php">Drive & Loc</a>
        <div class="navbar-nav ml-auto">
            <?php if(isset($_SESSION['client_id'])): ?>
                <a class="nav-item nav-link" href="2.php">Espace Client</a>
                <a class="nav-item nav-link" href="logout.php">Déconnexion</a>
            <?php else: ?>
                <a class="nav-item nav-link" href="login.php">Connexion</a>
                <a class="nav-item nav-link" href="signup.php">Inscription</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Bienvenue chez Drive & Loc</h1>
        <div class="row">
            <?php foreach($vehicules as $vehicule): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $vehicule['img']; ?>" class="card-img-top" alt="<?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?></h5>
                            <p class="card-text">Prix par jour : <?php echo $vehicule['prixparjour']; ?> €</p>
                            <a href="details_vehicule.php?id=<?php echo $vehicule['id_vehicule']; ?>" class="btn btn-primary">Voir détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>