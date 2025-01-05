<?php
include 'DB.php'; 

if (isset($_POST['espaceAdmin'])) {
    header("Location: espaceAdmin.php");
}

if (isset($_POST['reservations'])) {
    header("Location: reservations.php");
}

$bd = new DB(); 
$Connect = $bd->connect();

if ($Connect->connect_error) {
    die("Connection error: " . $Connect->connect_error);
}

$sql2 = "SELECT id_client, nom, prenom, adresse, numtel, email FROM clients";
$stmt2 = $Connect->prepare($sql2);
$clients = [];

if ($stmt2->execute()) {
    $stmt2->bind_result($id_client, $nom, $prenom, $adresse, $numtel, $email);

    while ($stmt2->fetch()) {
        $clients[] = [
            'id_client' => $id_client,
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse' => $adresse,
            'numtel' => $numtel,
            'email' => $email,
        ];
    }
} else {
    $ch = "Error: " . $stmt2->error;
    echo "<script>alert('$ch')</script>";
}

$stmt2->close();

if (isset($_POST['supprime']) && isset($_POST['id_client'])) {
    $id_client = $_POST['id_client'];
    $sql = "DELETE FROM clients WHERE id_client = ?";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("i", $id_client);
    if ($stmt->execute()) {
        header("Location: Clientss.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$blocModify = "";
$userID = 0;
if (isset($_POST['modifier'])) {
    $userID = $_POST['id_client_modifier'];

    $sql2 = "SELECT id_client, nom, prenom, adresse, numtel, email, mdp FROM clients WHERE id_client = ?";
    $stmt2 = $Connect->prepare($sql2);
    $stmt2->bind_param("i", $userID);

    if ($stmt2->execute()) {
        $stmt2->bind_result($id_client, $nom, $prenom, $adresse, $numtel, $email, $mdp);

        while ($stmt2->fetch()) {
            $blocModify = '<div class="flex justify-center items-center h-screen">
            <form id="inscription-form" class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg" method="post" action="">
            <input type="hidden" name="id_client_modifier" value="' . $id_client . '">

                <h2 class="text-2xl font-bold mb-4">Modifier ses données</h2>
                <label for="nom" class="block text-left mb-2">Nom:</label>
                <input type="text" id="nom" name="nom" class="w-full p-2 mb-4 border border-gray-300 rounded" value="' . $nom . '" required>
        
                <label for="prenom" class="block text-left mb-2">Prénom:</label>
                <input type="text" id="prenom" name="prenom" class="w-full p-2 mb-4 border border-gray-300 rounded" value="' . $prenom . '" required>
        
                <label for="adresse" class="block text-left mb-2">Adresse:</label>
                <input type="text" id="adresse" name="adresse" class="w-full p-2 mb-4 border border-gray-300 rounded" value="' . $adresse . '" required>
        
                <label for="telephone" class="block text-left mb-2">Numéro de téléphone:</label>
                <input type="tel" id="telephone" name="telephone" class="w-full p-2 mb-4 border border-gray-300 rounded" value="' . $numtel . '" required>
        
                <label for="email" class="block text-left mb-2">Email:</label>
                <input type="email" id="email" name="email" class="w-full p-2 mb-4 border border-gray-300 rounded" value="' . $email . '" required>
                <button type="submit" name="submitModification" class="w-full bg-cyan-900 text-white py-2 rounded hover:bg-cyan-500">Modifier</button>
            </form>
        </div>';
        }
    } else {
        $ch = "Error: " . $stmt2->error;
        echo "<script>alert('$ch')</script>";
    }

    $stmt2->close();
}

if (isset($_POST['submitModification'])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $adresse = $_POST["adresse"];
    $numtel = $_POST["telephone"];
    $email = $_POST["email"];
    $mdp = $_POST["mdp"];
    $userID = $_POST["id_client_modifier"];
    $sql = "UPDATE clients SET nom=?, prenom=?, adresse=?, numtel=?, email=?, mdp=? WHERE id_client=?";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("ssssssi", $nom, $prenom, $adresse, $numtel, $email, $mdp, $userID);
    if ($stmt->execute()) {
        header("Location: Clientss.php");
    } else {
        $ch = "Error: " . $stmt->error;
        echo "<script>alert('$ch')</script>";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cyan-100">
<nav class="bg-cyan-900 fixed w-full top-0 left-0 z-50 flex justify-between items-center p-5 shadow-md transition-colors duration-300">
    <!-- <img src="v.png" alt="Logo" class="max-w-[100px] h-auto"> -->
    <div class="text-2xl font-bold text-white">DRIVE & LOC </div>
    <div class="space-x-2">
        <form method="post" action="" class="inline-flex gap-2">
            <button type="submit" class="bg-cyan-500 hover:bg-cyan-950 text-white px-4 py-2 rounded transition-colors duration-300" name="espaceAdmin">Admin</button>
            <button type="submit" class="bg-cyan-500 hover:bg-cyan-950 text-white px-4 py-2 rounded transition-colors duration-300" name="reservations">Réservations</button>
        </form>
    </div>
</nav>

<div class="mt-32 px-4">
    <table class="w-full bg-teal-100 border-collapse mb-8">
        <thead>
            <tr>
                <th class="p-4 border-b">ID</th>
                <th class="p-4 border-b">Nom</th>
                <th class="p-4 border-b">Prénom</th>
                <th class="p-4 border-b">Adresse</th>
                <th class="p-4 border-b">Numéro de téléphone</th>
                <th class="p-4 border-b">Email</th>
                <th class="p-4 text-center border-b">Action</th>
                <th class="p-4 text-center border-b">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client) : ?>
                <tr>
                    <td class="p-4 border-b"><?php echo $client['id_client']; ?></td>
                    <td class="p-4 border-b"><?php echo $client['nom']; ?></td>
                    <td class="p-4 border-b"><?php echo $client['prenom']; ?></td>
                    <td class="p-4 border-b"><?php echo $client['adresse']; ?></td>
                    <td class="p-4 border-b"><?php echo $client['numtel']; ?></td>
                    <td class="p-4 border-b"><?php echo $client['email']; ?></td>
                    <td class="p-4 text-center border-b">
                        <form method="post" action="">
                            <input type="hidden" name="id_client_modifier" value="<?php echo $client['id_client']; ?>">
                            <button type="submit" class="bg-cyan-900 hover:bg-cyan-500 text-white px-4 py-2 rounded transition-colors duration-300" name="modifier">Modifier</button>
                        </form>
                    </td>
                    <td class="p-4 text-center border-b">
                        <form method="post" action="">
                            <input type="hidden" name="id_client" value="<?php echo $client['id_client']; ?>">
                            <button type="submit" class="bg-red-400 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors duration-300" name="supprime">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php 
    if ($blocModify != "") {
        echo $blocModify;
    }
    ?>
</div>

</body>
</html>