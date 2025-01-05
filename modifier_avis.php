<?php
// modifier_avis.php
session_start();
include 'DB.php';
$bd = new DB();
$connect = $bd->connect();

if (!isset($_SESSION['idclient'])) {
    die("Vous devez être connecté");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_avis = $_POST['id_avis'];
    $contenu = $_POST['contenu'];
    $note = $_POST['note'];
    
    // Vérifier que l'avis appartient bien à l'utilisateur connecté
    $check_sql = "SELECT id_vehicule FROM Avis WHERE id_avis = ? AND id_client = ?";
    $check_stmt = $connect->prepare($check_sql);
    $check_stmt->bind_param("ii", $id_avis, $_SESSION['idclient']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $id_vehicule = $row['id_vehicule'];
        
        $sql = "UPDATE Avis SET contenu = ?, note = ? WHERE id_avis = ? AND id_client = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("siii", $contenu, $note, $id_avis, $_SESSION['idclient']);
        
        if ($stmt->execute()) {
            header("Location: detail_vehicule.php?id=" . $id_vehicule);
        } else {
            echo "Erreur lors de la modification";
        }
    } else {
        echo "Vous n'êtes pas autorisé à modifier cet avis";
    }
}
?>