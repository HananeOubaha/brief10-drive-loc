<?php 
include 'DB.php';

$bd = new DB();
$Connect = $bd->connect();

if ($Connect->connect_error) {
    die("Connection error: " . $Connect->connect_error);
}

// Supprimer un avis
if (isset($_POST['supprimerAvis']) && isset($_POST['id_avis'])) {
    $id_avis = $_POST['id_avis'];
    $sql = "DELETE FROM Avis WHERE id_avis = ?";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("i", $id_avis);
    if ($stmt->execute()) {
        header("Location: avis.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
if (isset($_POST['reservations'])) {
    header("Location: reservations.php");
}
if (isset($_POST['espaceAdmin'])) {
    header("Location: espaceAdmin.php");
}

// Récupérer les avis
$sql2 = "SELECT Avis.id_avis, clients.nom, clients.prenom, vehicules.marque, vehicules.modele, Avis.contenu, Avis.note, Avis.date 
         FROM Avis 
         JOIN clients ON Avis.id_client = clients.id_client 
         JOIN vehicules ON Avis.id_vehicule = vehicules.id_vehicule";
$stmt2 = $Connect->prepare($sql2);
$avis = [];

if ($stmt2->execute()) {
    $stmt2->bind_result($id_avis, $nom, $prenom, $marque, $modele, $contenu, $note, $date);

    while ($stmt2->fetch()) {
        $avis[] = [
            'id_avis' => $id_avis,
            'nom' => $nom,
            'prenom' => $prenom,
            'marque' => $marque,
            'modele' => $modele,
            'contenu' => $contenu,
            'note' => $note,
            'date' => $date,
        ];
    }
} else {
    $ch = "Error: " . $stmt2->error;
    echo "<script>alert('$ch')</script>";
}

$stmt2->close();
$Connect->close();

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

<nav class="bg-blue-500 hover:bg-blue-600 fixed w-full top-0 left-0 z-50 flex justify-between items-center p-5 shadow-md transition-colors duration-300">
    <img src="v.png" alt="Logo" class="max-w-[100px] h-auto">
    <div class="text-2xl font-bold text-white">DRIDI CARS</div>
    <div class="space-x-2">
        <form method="post" action="" class="inline-flex gap-2">
            <button type="submit" class="bg-red-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors duration-300" name="espaceAdmin">Admin</button>
            <button type="submit" class="bg-red-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors duration-300" name="reservations">Réservations</button>
        </form>
    </div>
</nav>

<div class="mt-32 px-4">
    <table class="w-full bg-[#c1c4bc] border-collapse mb-8">
        <thead>
            <tr>
                <th class="p-4 border-b">ID</th>
                <th class="p-4 border-b">Nom</th>
                <th class="p-4 border-b">Prénom</th>
                <th class="p-4 border-b">Véhicule</th>
                <th class="p-4 border-b">Contenu</th>
                <th class="p-4 border-b">Note</th>
                <th class="p-4 border-b">Date</th>
                <th class="p-4 text-center border-b">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($avis as $avi) : ?>
                <tr>
                    <td class="p-4 border-b"><?php echo $avi['id_avis']; ?></td>
                    <td class="p-4 border-b"><?php echo $avi['nom']; ?></td>
                    <td class="p-4 border-b"><?php echo $avi['prenom']; ?></td>
                    <td class="p-4 border-b"><?php echo $avi['marque'] . ' ' . $avi['modele']; ?></td>
                    <td class="p-4 border-b"><?php echo $avi['contenu']; ?></td>
                    <td class="p-4 border-b"><?php echo $avi['note']; ?></td>
                    <td class="p-4 border-b"><?php echo $avi['date']; ?></td>
                    <td class="p-4 text-center border-b">
                        <form method="post" action="">
                            <input type="hidden" name="id_avis" value="<?php echo $avi['id_avis']; ?>">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors duration-300" name="supprimerAvis">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>