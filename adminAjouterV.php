<?php 
include 'DB.php';
include 'Vehicule.php';
 
$db = new DB();
$conn = $db->connect();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitAjouterVehicule'])) {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $prixParJour = $_POST['prix'];
    $disponible = $_POST['disponible'] === 'oui' ? 1 : 0;
    $category_id = isset($_POST['category']) ? $_POST['category'] : null;
    $img = '';

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $img = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $img);
    }

    try {
        if ($category_id === null) {
            throw new Exception("Category ID cannot be null.");
        }
        $vehicule = new Vehicule($marque, $modele, $annee, $prixParJour, $disponible, $img, $category_id, $conn);
        $message = "Véhicule ajouté avec succès.";
        // Redirect to espaceAdmin.php
        header('Location: espaceAdmin.php?message=' . urlencode($message));
        exit();
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Formulaire de Véhicule</title>
    <style>
        body {
            background: url('1.jpg') center/cover no-repeat;
            transition: background-image 1s ease;
            animation: changeBackground 16s infinite;
        }
        @keyframes changeBackground {
            0% { background-image: url('1.png'); }
            25% { background-image: url('2.png'); }
            50% { background-image: url('3.png'); }
            75% { background-image: url('4.png'); }
            100% { background-image: url('1.png'); }
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-4 rounded-lg shadow-lg text-center max-w-sm mx-auto">
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form id="vehicle-form" method="POST" action="" enctype="multipart/form-data">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Formulaire de Véhicule</h2>

            <label for="marque" class="block text-left mb-1">Marque :</label>
            <input type="text" id="marque" name="marque" required class="w-full p-1 mb-2 border border-gray-300 rounded">

            <label for="modele" class="block text-left mb-1">Modèle :</label>
            <input type="text" id="modele" name="modele" required class="w-full p-1 mb-2 border border-gray-300 rounded">

            <label for="annee" class="block text-left mb-1">Année :</label>
            <input type="number" id="annee" name="annee" required class="w-full p-1 mb-2 border border-gray-300 rounded">

            <label for="prix" class="block text-left mb-1">Prix par jour :</label>
            <input type="number" id="prix" name="prix" required class="w-full p-1 mb-2 border border-gray-300 rounded">

            <label class="block text-left mb-1">Disponible :</label>
            <div class="flex justify-around mb-2">
                <label for="disponible-oui" class="flex items-center">
                    <input type="radio" id="disponible-oui" name="disponible" value="oui" required class="mr-1">
                    Oui
                </label>
                <label for="disponible-non" class="flex items-center">
                    <input type="radio" id="disponible-non" name="disponible" value="non" required class="mr-1">
                    Non
                </label>
            </div>

            <label for="category" class="block text-left mb-1">Catégorie :</label>
            <select id="category" name="category" required class="w-full p-1 mb-2 border border-gray-300 rounded">
                <option value="" disabled selected>Choisir une catégorie</option>
                <?php
                // Fetch categories from the database
                $result = $conn->query("SELECT id_category, category_name FROM categories");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id_category']}'>{$row['category_name']}</option>";
                }
                ?>
            </select>

            <label for="image" class="block text-left mb-1">Saisir une image :</label>
            <input type="file" id="image" name="image" accept="image/*" required class="w-full p-1 mb-2 border border-gray-300 rounded">

            <button type="submit" name="submitAjouterVehicule" class="w-full bg-blue-500 text-white py-1 rounded hover:bg-blue-700">Soumettre</button>
        </form>
    </div>
</body>
</html>