<?php
include 'DB.php';

$bd = new DB();
$Connect = $bd->connect();

if ($Connect->connect_error) {
    die("Erreur de connexion : " . $Connect->connect_error);
}

$idVehicule = 0;

if (isset($_POST['espaceAdmin'])) {
    header("Location: espaceAdmin.php");
}
if (isset($_POST['logOut'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php");
}
if (isset($_POST['Clients'])) {
    header("Location: Clientss.php");
}
if (isset($_POST['avis'])) {
    header("Location: avis.php");
}
if (isset($_POST['reservations'])) {
    header("Location: reservations.php");
}
if (isset($_POST['ajouter'])) {
    header("Location: adminAjouterV.php");
}
if (isset($_POST['Modifier'])) {
    $idVehicule = $_POST['idV'];
    session_start();
    $_SESSION["IDV"] = $idVehicule;
    header("Location: Modifier.php");
    exit();
}
if (isset($_POST['Supprimer'])) {
    $idVehicule = $_POST['idV'];
    $sql = "DELETE FROM vehicules WHERE id_vehicule = ?";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("i", $idVehicule);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Véhicule supprimé avec succès.')</script>";
    } else {
        echo "<script>alert('Échec de la suppression du véhicule.')</script>";
    }
    $stmt->close();
    header("Location: espaceAdmin.php");
}

// Insertion de catégories et de véhicules
if (isset($_POST['ajouterCategorie'])) {
    $category_name = $_POST['category_name'];
    $sql = "INSERT INTO categories (category_name) VALUES (?)";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['ajouterVehicule'])) {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $prixparjour = $_POST['prixparjour'];
    $disponible = $_POST['disponible'];
    $img = $_POST['img'];
    $id_category = $_POST['id_category'];
    $sql = "INSERT INTO vehicules (marque, modele, annee, prixparjour, disponible, img, id_category) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("ssidsii", $marque, $modele, $annee, $prixparjour, $disponible, $img, $id_category);
    $stmt->execute();
    $stmt->close();
}

// Récupération des catégories et des véhicules
$categories = [];
$sql = "SELECT id_category, category_name FROM categories";
$result = $Connect->query($sql);
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$rows = [];
$sql = "SELECT id_vehicule, marque, modele, annee, prixparjour, disponible, img FROM vehicules";
$stmt = $Connect->prepare($sql);
if (!$stmt) {
    $ch = "Erreur dans la déclaration préparée : " . $Connect->error;
    echo "<script>alert('$ch')</script>";
} else {
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_vehicule, $marque, $modele, $annee, $prixparjour, $disponible, $img);
            while ($stmt->fetch()) {
                $rows[] = [
                    'id_vehicule' => $id_vehicule,
                    'marque' => $marque,
                    'modele' => $modele,
                    'annee' => $annee,
                    'prixparjour' => $prixparjour,
                    'disponible' => $disponible,
                    'img' => $img,
                ];
            }
        } else {
            echo "<script>alert('Aucun véhicule trouvé.')</script>";
        }
    } else {
        $ch = "Erreur : " . $stmt->error;
        echo "<script>alert('$ch')</script>";
    }
    $stmt->close();
}

$Connect->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tableau des Véhicules</title>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            } 
        } 
    </style>
</head>
<body class="m-0 p-0 bg-cyan-100 font-sans">
    <nav class="fixed top-0 left-0 w-full bg-cyan-900 px-5 py-3 shadow-md z-50 flex justify-between items-center transition-colors duration-300">
        <!-- <img src="v.png" alt="Logo" class="max-w-[100px] h-auto"> -->
        <div class="text-white text-2xl font-bold mr-auto">Drive&Loc</div>
        <div class="space-x-2">
            <form method="post" action="" class="inline-flex gap-2">
                <button type="submit" name="espaceAdmin" class="bg-cyan-500 hover:bg-cyan-950 text-white px-4 py-2 rounded transition-colors duration-300">Admin</button>
                <button type="submit" name="Clients" class="bg-cyan-500 hover:bg-cyan-950 text-white px-4 py-2 rounded transition-colors duration-300">Clients</button>
                <button type="submit" name="avis" class="bg-cyan-500 hover:bg-cyan-950 text-white px-4 py-2 rounded transition-colors duration-300">Avis</button>
                <button type="submit" name="reservations" class="bg-cyan-500 hover:bg-cyan-950 text-white px-4 py-2 rounded transition-colors duration-300">Réservations</button>
                <button type="submit" name="logOut" class="bg-cyan-500 hover:bg-cyan-950 text-white px-4 py-2 rounded transition-colors duration-300">Déconnexion</button>
            </form>
        </div>
    </nav>

    <h2 class="text-xl font-bold mb-4 text-center mt-24">Ajouter une Catégorie</h2>
        <form action="" method="post" class="text-center mb-8">
            <input type="text" name="category_name" placeholder="Nom de la catégorie"class="bg-white text-black px-6 py-2 " required>
            <button type="submit" name="ajouterCategorie" class="bg-cyan-900 hover:bg-cyan-500 text-black px-6 py-2 rounded transition-colors durée-300">Ajouter la catégorie</button>
        </form>

    <div class="mt-10">
        <div class="bg-teal-200 shadow-lg p-6 rounded-lg text-center text-black mb-8" style="animation: fadeInUp 0.5s ease-out">
            <h2 class="text-xl font-bold mb-4">Ajouter une nouvelle véhicule...</h2>
            <form method="post" action="">
                <button type="submit" name="ajouter" class="bg-cyan-900 hover:bg-cyan-500 text-white px-6 py-2 rounded transition-colors durée-300">Ajouter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full bg-teal-100 mb-8">
                <thead>
                    <tr class="text-black">
                        <th class="p-4 text-left border-b">Image</th>
                        <th class="p-4 text-left border-b">Marque</th>
                        <th class="p-4 text-left border-b">Modèle</th>
                        <th class="p-4 text-left border-b">Année</th>
                        <th class="p-4 text-left border-b">Prix par Jour</th>
                        <th class="p-4 text-left border-b">Disponible</th>
                        <th class="p-4 text-center border-b">Modifier</th>
                        <th class="p-4 text-center border-b">Supprimer</th>
                    </tr>
                </thead>
                <tbody class="text-cyan-900 font-semibold">
                    <?php
                    foreach ($rows as $row) {
                        $idVehicule = $row['id_vehicule'];
                        $img = $row['img'];
                        $marque = $row['marque'];
                        $modele = $row['modele'];
                        $annee = $row['annee'];
                        $prixparjour = $row['prixparjour'];
                        $disponible = $row['disponible'];
                    ?>
                    <tr>
                        <td class="p-4 border-b"><img src='<?php echo $img; ?>' alt='Product Image' class="max-w-[30%] h-auto mx-auto rounded-full"></td>
                        <td class="p-4 border-b"><?php echo $marque; ?></td>
                        <td class="p-4 border-b"><?php echo $modele; ?></td>
                        <td class="p-4 border-b"><?php echo $annee; ?></td>
                        <td class="p-4 border-b"><?php echo $prixparjour; ?></td>
                        <td class="p-4 border-b"><?php echo ($disponible == 1 ? 'disponible' : 'non disponible'); ?></td>
                        <td class="p-4 text-center border-b">
                            <a href="modifierVehicule.php?idV=<?php echo $idVehicule; ?>" class="bg-cyan-900 hover:bg-cyan-950 text-white px-4 py-2 rounded transition-colors durée-300">Modifier</a>
                        </td>
                        <td class="p-4 text-center border-b">
                            <form method="post" action="">
                                <input type="hidden" name="idV" value="<?php echo $idVehicule; ?>">
                                <button type="submit" name="Supprimer" class="bg-red-400 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors durée-300">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>