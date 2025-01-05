<?php
session_start();
include 'DB.php';
$bd = new DB();
$connect = $bd->connect();

if (!isset($_SESSION['idclient'])) {
    die("Vous devez être connecté");
}

if (isset($_GET['id'])) {
    $id_avis = $_GET['id'];
    
    // Vérifier que l'avis appartient bien à l'utilisateur connecté
    $check_sql = "SELECT id_vehicule FROM Avis WHERE id_avis = ? AND id_client = ?";
    $check_stmt = $connect->prepare($check_sql);
    $check_stmt->bind_param("ii", $id_avis, $_SESSION['idclient']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $id_vehicule = $row['id_vehicule'];
        
        // Soft delete - mettre à jour le statut plutôt que de supprimer
        $sql = "UPDATE Avis SET is_deleted = 1 WHERE id_avis = ? AND id_client = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ii", $id_avis, $_SESSION['idclient']);
        
        if ($stmt->execute()) {
            header("Location: detail_vehicule.php?id=" . $id_vehicule);
        } else {
            echo "Erreur lors de la suppression";
        }
    } else {
        echo "Vous n'êtes pas autorisé à supprimer cet avis";
    }
}
?>
