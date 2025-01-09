<?php
class Client extends Utilisateur {
    private string $adresse;
    private string $numTel;

    public function __construct(int $id, string $nom, string $prenom, string $email, string $mdp, Role $role, string $adresse, string $numTel) {
        parent::__construct($id, $nom, $prenom, $email, $mdp, $role);
        $this->adresse = $adresse;
        $this->numTel = $numTel;
    }

    public function getAdresse(): string {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): void {
        $this->adresse = $adresse;
    }

    public function getNumTel(): string {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): void {
        $this->numTel = $numTel;
    }
}
?>