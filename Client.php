<?php
class Client {
    private $conn; 
    public function __construct($nom, $prenom, $adresse, $numtel, $email, $mdp, $conn) {
        $this->conn = $conn;
        $this->insererClient($nom, $prenom, $adresse, $numtel, $email, $mdp);
    }

    private function insererClient($nom, $prenom, $adresse, $numtel, $email, $mdp) {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
        $role_default = 2; // Assuming 2 is the client role ID

        $sql = "INSERT INTO clients (nom, prenom, adresse, numtel, email, mdp, id_role) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erreur de préparation : " . $this->conn->error);
        }

        $stmt->bind_param("ssssssi", $nom, $prenom, $adresse, $numtel, $email, $mdp_hash, $role_default);
        
        if (!$stmt->execute()) {
            if ($stmt->errno == 1062) {
                throw new Exception("Cet email est déjà utilisé");
            }
            throw new Exception("Erreur d'insertion : " . $stmt->error);
        }
        
        $stmt->close();
    }
}

?>


