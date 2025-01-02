<?php
class LogIn {
    private $email;
    private $motDePasse;

    public function __construct($email, $motDePasse, $Connecte) {
        $this->email = $email;
        $this->motDePasse = $motDePasse;

        // Admin check
        if($email === "hanane@gmail.com" && $motDePasse === "hanane123456") {
            session_start();
            $_SESSION["admin"] = true;
            header("Location: espaceAdmin.php");
            exit();
        }

        // Regular user login
        session_start();
        
        $sql = "SELECT id_client, email, mdp, id_role FROM clients WHERE email = ?";
        $stmt = $Connecte->prepare($sql);
        
        if (!$stmt) {
            die("Erreur de préparation : " . $Connecte->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($motDePasse, $user["mdp"])) {
            $_SESSION["idclient"] = $user["id_client"];
            $_SESSION["role"] = $user["id_role"];
            $_SESSION["rowsF"] = [];
            header("Location: indexi.php");
            exit();
        } else {
            echo "<script>alert('Email ou mot de passe incorrect.');</script>";
        }

        $stmt->close();
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getMotDePasse() {
        return $this->motDePasse;
    }

    public function setMotDePasse($motDePasse) {
        $this->motDePasse = $motDePasse;
    }
}
?> 