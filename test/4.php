<?php
session_start();
require_once 'DB.php';
require_once 'Vehicule.php';
require_once 'Avis.php';

if (!isset($_GET['id'])) {
    header('Location: vehicules.php');
    exit();
}

$db = new DB();
$conn = $db->connect();

$vehicule = new Vehicule($conn);
$vehiculeDetails = $vehicule->getVehiculeById($_GET['id']);

$avis = new Avis($conn);
$avisVehicule = $avis->getAvisByVehicule($_GET['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $vehiculeDetails['marque'] . ' ' . $vehiculeDetails['modele']; ?> - Drive & Loc</title>
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
        <h1><?php echo $vehiculeDetails['marque'] . ' ' . $vehiculeDetails['modele']; ?></h1>
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $vehiculeDetails['img']; ?>" class="img-fluid" alt="<?php echo $vehiculeDetails['marque'] . ' ' . $vehiculeDetails['modele']; ?>">
            </div>
            <div class="col-md-6">
                <p><strong>Année :</strong> <?php echo $vehiculeDetails['annee']; ?></p>
                <p><strong>Prix par jour :</strong> <?php echo $vehiculeDetails['prixparjour']; ?> €</p>
                <p><strong>Disponibilité :</strong> <?php echo $vehiculeDetails['disponible'] ? 'Disponible' : 'Non disponible'; ?></p>
                <p><strong>Catégorie :</strong> <?php echo $vehiculeDetails['category_name']; ?></p>
                <?php if(isset($_SESSION['client_id']) && $vehiculeDetails['disponible']): ?>
                    <a href="reservation.php?id=<?php echo $vehiculeDetails['id_vehicule']; ?>" class="btn btn-primary">Réserver</a>
                <?php endif; ?>
            </div>
        </div>

        <h2 class="mt-4">Avis</h2>
        <?php if (empty($avisVehicule)): ?>
            <p>Aucun avis pour ce véhicule.</p>
        <?php else: ?>
            <?php foreach ($avisVehicule as $avis): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $avis['nom'] . ' ' . $avis['prenom']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Note : <?php echo $avis['note']; ?>/5</h6>
                        <p class="card-text"><?php echo $avis['contenu']; ?></p>
                        <p class="card-text"><small class="text-muted">Date : <?php echo $avis['date']; ?></small></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['client_id'])): ?>
            <a href="avis.php?id=<?php echo $vehiculeDetails['id_vehicule']; ?>" class="btn btn-secondary">Ajouter un avis</a>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>