<?php
session_start();
require_once 'DB.php';
require_once 'Vehicule.php';

$db = new DB();
$conn = $db->connect();

$vehicule = new Vehicule($conn);
$vehicules = $vehicule->getAllVehicules();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;
$total = count($vehicules);
$pages = ceil($total / $perPage);
$start = ($page - 1) * $perPage;
$vehicules = array_slice($vehicules, $start, $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos véhicules - Drive & Loc</title>
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
        <h1>Nos véhicules</h1>
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
        
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for($i = 1; $i <= $pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>