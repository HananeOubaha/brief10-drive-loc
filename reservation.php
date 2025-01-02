<?php
class Reservation {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createReservation($idVehicule, $idClient, $dateDebut, $dateFin, $lieuPriseCharge, $prixTotal) {
        $sql = "INSERT INTO reservations (id_vehicule, id_client, datedebut, datefin, lieuPriseCharge, prixtotal) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idVehicule, $idClient, $dateDebut, $dateFin, $lieuPriseCharge, $prixTotal]);
    }

    public function getReservationsByClient($clientId) {
        $sql = "SELECT r.*, v.marque, v.modele FROM reservations r JOIN vehicules v ON r.id_vehicule = v.id_vehicule WHERE r.id_client = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 