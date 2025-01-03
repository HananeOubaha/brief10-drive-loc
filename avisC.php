<?php
include 'DB.php'; 

$bd = new DB();
$connect = $bd->connect();

$id_client = 2; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['supprimerAvis'])) {
        $id_avis = $_POST['id_avis'];
        $sql = "UPDATE Avis SET contenu = '[Avis supprimé par l\'utilisateur]', note = 0 WHERE id_avis = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $id_avis);
        if ($stmt->execute()) {
            echo "<script>alert('Avis supprimé avec succès');</script>";
        }
    } elseif (isset($_POST['modifierAvis'])) {
        $id_avis = $_POST['id_avis'];
        $contenu = $_POST['contenu'];
        $note = $_POST['note'];
        $sql = "UPDATE Avis SET contenu = ?, note = ? WHERE id_avis = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("sii", $contenu, $note, $id_avis);
        if ($stmt->execute()) {
            echo "<script>alert('Avis modifié avec succès');</script>";
        }
    } elseif (isset($_POST['ajouterAvis'])) {
        $id_vehicule = $_POST['id_vehicule'];
        $contenu = $_POST['contenu'];
        $note = $_POST['note'];
        $date = date('Y-m-d');
        $sql = "INSERT INTO Avis (id_client, id_vehicule, contenu, note, date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("iisii", $id_client, $id_vehicule, $contenu, $note, $date);
        if ($stmt->execute()) {
            echo "<script>alert('Avis ajouté avec succès');</script>";
        }
    }
}

$sql = "SELECT Avis.id_avis, clients.nom, clients.prenom, vehicules.marque, vehicules.modele, Avis.contenu, Avis.note, Avis.date 
        FROM Avis 
        JOIN clients ON Avis.id_client = clients.id_client 
        JOIN vehicules ON Avis.id_vehicule = vehicules.id_vehicule";
$result = $connect->query($sql);

$avis = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $avis[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Avis</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6">Gérer les Avis</h1>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Client</th>
                    <th class="py-2 px-4 border-b">Véhicule</th>
                    <th class="py-2 px-4 border-b">Contenu</th>
                    <th class="py-2 px-4 border-b">Note</th>
                    <th class="py-2 px-4 border-b">Date</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis as $avi): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo $avi['id_avis']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $avi['nom'] . ' ' . $avi['prenom']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $avi['marque'] . ' ' . $avi['modele']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $avi['contenu']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $avi['note']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $avi['date']; ?></td>
                        <td class="py-2 px-4 border-b">
                            <form method="post" class="inline-block">
                                <input type="hidden" name="id_avis" value="<?php echo $avi['id_avis']; ?>">
                                <button type="submit" name="supprimerAvis" class="bg-red-500 text-white px-4 py-2 rounded">Supprimer</button>
                            </form>
                            <button onclick="document.getElementById('editForm-<?php echo $avi['id_avis']; ?>').classList.toggle('hidden');" class="bg-yellow-500 text-white px-4 py-2 rounded">Modifier</button>
                            <form method="post" class="mt-2 hidden" id="editForm-<?php echo $avi['id_avis']; ?>">
                                <input type="hidden" name="id_avis" value="<?php echo $avi['id_avis']; ?>">
                                <textarea name="contenu" class="w-full p-2 border border-gray-300 rounded"><?php echo $avi['contenu']; ?></textarea>
                                <input type="number" name="note" value="<?php echo $avi['note']; ?>" class="w-full p-2 border border-gray-300 rounded mt-2">
                                <button type="submit" name="modifierAvis" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Enregistrer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-10">
            <h2 class="text-xl font-bold mb-4">Ajouter un Avis</h2>
            <form method="post" class="bg-white p-6 rounded-lg shadow-md">
                <input type="hidden" name="id_client" value="1"> <!-- Remplacer par la session du client connecté -->
                <div class="mb-4">
                    <label for="id_vehicule" class="block text-gray-700">Véhicule:</label>
                    <select id="id_vehicule" name="id_vehicule" class="w-full p-2 border border-gray-300 rounded">
                        <!-- Remplir avec les véhicules disponibles -->
                        <?php
                        $sql = "SELECT id_vehicule, marque, modele FROM vehicules";
                        $result = $connect->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id_vehicule']}'>{$row['marque']} {$row['modele']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="contenu" class="block text-gray-700">Contenu:</label>
                    <textarea id="contenu" name="contenu" class="w-full p-2 border border-gray-300 rounded" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="note" class="block text-gray-700">Note:</label>
                    <input type="number" id="note" name="note" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" name="ajouterAvis" class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter</button>
            </form>
        </div>
    </div>
</body>
</html>