<?php 
abstract class Utilisateur {
    protected int $id;
    protected string $nom;
    protected string $prenom;
    protected string $email;
    protected string $mdp;
    protected Role $role;

    public function __construct(int $id, string $nom, string $prenom, string $email, string $mdp, Role $role) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->role = $role;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getRole(): Role {
        return $this->role;
    }

    public function setRole(Role $role): void {
        $this->role = $role;
    }
}
