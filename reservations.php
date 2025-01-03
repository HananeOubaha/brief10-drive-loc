<?php
include 'DB.php';

$bd = new DB();
$Connect = $bd->connect();

if ($Connect->connect_error) {
    die("Connection error: " . $Connect->connect_error);
}

// Supprimer une réservation
if (isset($_POST['supprimerReservation']) && isset($_POST['id_reservation'])) {
    $id_reservation = $_POST['id_reservation'];
    $sql = "DELETE FROM reservations WHERE id_reservation = ?";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("i", $id_reservation);
    if ($stmt->execute()) {
        header("Location: reservations.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    } 
    $stmt->close();
}
if (isset($_POST['avis'])) {
    header("Location: avis.php");
}
if (isset($_POST['espaceAdmin'])) {
    header("Location: espaceAdmin.php");
}
// Mettre à jour le statut d'une réservation
if (isset($_POST['modifierStatut']) && isset($_POST['id_reservation']) && isset($_POST['nouveauStatut'])) {
    $id_reservation = $_POST['id_reservation'];
    $nouveauStatut = $_POST['nouveauStatut'];
    $sql = "UPDATE reservations SET statut = ? WHERE id_reservation = ?";
    $stmt = $Connect->prepare($sql);
    $stmt->bind_param("si", $nouveauStatut, $id_reservation);
    if ($stmt->execute()) {
        header("Location: reservations.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Récupérer les réservations
$sql2 = "SELECT reservations.id_reservation, clients.nom, clients.prenom, vehicules.marque, vehicules.modele, reservations.datedebut, reservations.datefin, reservations.lieuPriseCharge, reservations.statut, reservations.prixtotal 
         FROM reservations 
         JOIN clients ON reservations.id_client = clients.id_client 
         JOIN vehicules ON reservations.id_vehicule = vehicules.id_vehicule";
$stmt2 = $Connect->prepare($sql2);
$reservations = [];
 
if ($stmt2->execute()) {
    $stmt2->bind_result($id_reservation, $nom, $prenom, $marque, $modele, $datedebut, $datefin, $lieuPriseCharge, $statut, $prixtotal);

    while ($stmt2->fetch()) {
        $reservations[] = [
            'id_reservation' => $id_reservation,
            'nom' => $nom,
            'prenom' => $prenom,
            'marque' => $marque,
            'modele' => $modele,
            'datedebut' => $datedebut,
            'datefin' => $datefin,
            'lieuPriseCharge' => $lieuPriseCharge,
            'statut' => $statut,
            'prixtotal' => $prixtotal,
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
    <title>Gestion des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<nav class="bg-blue-500 hover:bg-blue-600 fixed w-full top-0 left-0 z-50 flex justify-between items-center p-5 shadow-md transition-colors duration-300">
    <img src="v.png" alt="Logo" class="max-w-[100px] h-auto">
    <div class="text-2xl font-bold text-white">DRIDI CARS</div>
    <div class="space-x-2">
        <form method="post" action="" class="inline-flex gap-2">
            <button type="submit" class="bg-red-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors duration-300" name="espaceAdmin">Admin</button>
            <button type="submit" class="bg-red-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors duration-300" name="avis">Avis</button>
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
                <th class="p-4 border-b">Début</th>
                <th class="p-4 border-b">Fin</th>
                <th class="p-4 border-b">Lieu</th>
                <th class="p-4 border-b">Statut</th>
                <th class="p-4 border-b">Prix Total</th>
                <th class="p-4 text-center border-b">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation) : ?>
                <tr>
                    <td class="p-4 border-b"><?php echo $reservation['id_reservation']; ?></td>
                    <td class="p-4 border-b"><?php echo $reservation['nom']; ?></td>
                    <td class="p-4 border-b"><?php echo $reservation['prenom']; ?></td>
                    <td class="p-4 border-b"><?php echo $reservation['marque'] . ' ' . $reservation['modele']; ?></td>
                    <td class="p-4 border-b"><?php echo $reservation['datedebut']; ?></td>
                    <td class="p-4 border-b"><?php echo $reservation['datefin']; ?></td>
                    <td class="p-4 border-b"><?php echo $reservation['lieuPriseCharge']; ?></td>
                    <td class="p-4 border-b"><?php echo $reservation['statut']; ?></td>
                    <td class="p-4 border-b"><?php echo $reservation['prixtotal']; ?></td>
                    <td class="p-4 text-center border-b">
                        <form method="post" action="" class="inline-block">
                            <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                            <input type="hidden" name="nouveauStatut" value="Confirmee">
                            <button type="submit" class="text-green-500 hover:text-green-700 text-xl" name="modifierStatut" title="Confirmer">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                        <form method="post" action="" class="inline-block">
                            <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                            <input type="hidden" name="nouveauStatut" value="Annulee">
                            <button type="submit" class="text-yellow-500 hover:text-yellow-700 text-xl" name="modifierStatut" title="Annuler">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </form>
                        <form method="post" action="" class="inline-block">
                            <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xl" name="supprimerReservation" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
