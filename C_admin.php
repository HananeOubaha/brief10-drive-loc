<?php
class Admin extends Utilisateur {
    public function __construct(int $id, string $nom, string $prenom, string $email, string $mdp, Role $role) {
        parent::__construct($id, $nom, $prenom, $email, $mdp, $role);
    }
}
?>