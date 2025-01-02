<?php
class Vehicule {
    private $marque;
    private $modele;
    private $annee;
    private $prixParJour;
    private $disponible;
    private $img;
    private $category_id;
    private $conn;

    public function __construct($marque, $modele, $annee, $prixParJour, $disponible, $img, $category_id, $conn) {
        $this->conn = $conn;
        $this->insererVehicule($marque, $modele, $annee, $prixParJour, $disponible, $img, $category_id);
    }

    private function insererVehicule($marque, $modele, $annee, $prixParJour, $disponible, $img, $category_id) {
        $sql = "INSERT INTO vehicules (marque, modele, annee, prixparjour, disponible, img, id_category) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erreur de préparation : " . $this->conn->error);
        }

        $stmt->bind_param("ssiissi", 
            $marque,
            $modele,
            $annee,
            $prixParJour,
            $disponible,
            $img,
            $category_id
        ); 
        
        if (!$stmt->execute()) {
            throw new Exception("Erreur d'insertion : " . $stmt->error);
        }
        
        $stmt->close();
    }
}
?>