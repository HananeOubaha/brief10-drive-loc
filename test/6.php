<?php
session_start();
require_once 'DB.php';
require_once 'Avis.php';

if (!isset($_SESSION['client_id']) || !isset($_GET['id'])) {
    header('Location: 1.php');
    exit();
}

$db = new DB();
$conn = $db->connect();

$avis = new Avis($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contenu = $_POST['contenu'];
    $note = $_POST['note'];
    
    if ($avis->createAvis($_SESSION['client_id'], $_GET['id'], $contenu, $note)) {
        header('Location: details_vehicule.php?id=' . $_GET['id']);
        exit();
    } else {
        $error = "Une erreur est survenue lors de l'ajout de l'avis";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un avis - Drive & Loc</title>
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
        <h1>Ajouter un avis</h1>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="contenu">Votre avis</label>
                <textarea class="form-control" id="contenu" name="contenu" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="note">Note</label>
                <select class="form-control" id="note" name="note" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter l'avis</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>