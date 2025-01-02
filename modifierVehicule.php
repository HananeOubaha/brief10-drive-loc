<?php
include 'DB.php';

$bd = new DB();
$Connect = $bd->connect();

if ($Connect->connect_error) {
    die("Connection error: " . $Connect->connect_error);
}

$idVehicule = $_GET['idV'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $prixparjour = $_POST['prixparjour'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;
    $id_category = $_POST['id_category'];
    
    // Gestion du téléchargement de fichier
    $img = $_POST['existing_img']; // Valeur par défaut pour l'image existante
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img = 'uploads/' . basename($_FILES['img']['name']);
        move_uploaded_file($_FILES['img']['tmp_name'], $img);
    }

    $sql = "UPDATE vehicules SET marque = ?, modele = ?, annee = ?, prixparjour = ?, disponible = ?, img = ?, id_category = ? WHERE id_vehicule = ?";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("ssidsisi", $marque, $modele, $annee, $prixparjour, $disponible, $img, $id_category, $idVehicule);

    if ($stmt->execute()) {
        header("Location: espaceAdmin.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}

$sql = "SELECT marque, modele, annee, prixparjour, disponible, img, id_category FROM vehicules WHERE id_vehicule = ?";
$stmt = $Connect->prepare($sql);
$stmt->bind_param("i", $idVehicule);
$stmt->execute();
$stmt->bind_result($marque, $modele, $annee, $prixparjour, $disponible, $img, $id_category);
$stmt->fetch();
$stmt->close(); 

$categories = [];
$sql = "SELECT id_category, category_name FROM categories";
$result = $Connect->query($sql);
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
$Connect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Modifier Véhicule</title>
    <style>
        @keyframes changeBackground {
            0% { background-image: url('1.png'); }
            25% { background-image: url('2.png'); }
            50% { background-image: url('3.png'); }
            100% { background-image: url('1.png'); }
        }
    </style>
</head>
<body class="h-screen w-screen flex items-center justify-center bg-cover bg-center animate-[changeBackground_16s_infinite] font-sans m-0">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-sm mx-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Modifier Véhicule</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="existing_img" value="<?php echo htmlspecialchars($img); ?>">
            <label class="block text-left mb-2">Marque :</label>
            <input type="text" name="marque" value="<?php echo htmlspecialchars($marque); ?>" required class="w-full p-2 mb-4 border border-gray-300 rounded">

            <label class="block text-left mb-2">Modèle :</label>
            <input type="text" name="modele" value="<?php echo htmlspecialchars($modele); ?>" required class="w-full p-2 mb-4 border border-gray-300 rounded">

            <label class="block text-left mb-2">Année :</label>
            <input type="number" name="annee" value="<?php echo htmlspecialchars($annee); ?>" required class="w-full p-2 mb-4 border border-gray-300 rounded">

            <label class="block text-left mb-2">Prix par jour :</label>
            <input type="number" name="prixparjour" value="<?php echo htmlspecialchars($prixparjour); ?>" required class="w-full p-2 mb-4 border border-gray-300 rounded">

            <label class="block text-left mb-2">Disponible :</label>
            <input type="checkbox" name="disponible" <?php echo $disponible ? 'checked' : ''; ?> class="mb-4">

            <label class="block text-left mb-2">Catégorie :</label>
            <select name="id_category" required class="w-full p-2 mb-4 border border-gray-300 rounded">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id_category']; ?>" <?php echo $category['id_category'] == $id_category ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['category_name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label class="block text-left mb-2">Image :</label>
            <input type="file" name="img" class="w-full p-2 mb-4 border border-gray-300 rounded">

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-700">Enregistrer</button>
        </form>
    </div>
</body>
</html>