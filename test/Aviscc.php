<?php
class Avis {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createAvis($idClient, $idVehicule, $contenu, $note) {
        $sql = "INSERT INTO Avis (id_client, id_vehicule, contenu, note, date) VALUES (?, ?, ?, ?, CURDATE())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idClient, $idVehicule, $contenu, $note]);
    }

    public function getAvisByVehicule($idVehicule) {
        $sql = "SELECT a.*, c.nom, c.prenom FROM Avis a JOIN clients c ON a.id_client = c.id_client WHERE a.id_vehicule = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idVehicule]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAvis($idAvis, $contenu, $note) {
        $sql = "UPDATE Avis SET contenu = ?, note = ? WHERE id_avis = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$contenu, $note, $idAvis]);
    }

    public function deleteAvis($idAvis) {
        $sql = "DELETE FROM Avis WHERE id_avis = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idAvis]);
    }
}
?>