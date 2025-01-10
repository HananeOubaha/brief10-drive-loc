<?php
require_once 'DB.php';

class C_Commentaires {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    public function getCommentairesByArticle($id_article) {
        $sql = "SELECT * FROM commentaires WHERE id_article = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('i', $id_article);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die("Erreur d'exécution de la requête SQL : " . $stmt->error);
        }

        $commentaires = [];
        while ($row = $result->fetch_assoc()) {
            $commentaires[] = $row;
        }

        return $commentaires;
    }

    public function ajouterCommentaire($contenu, $id_article, $id_client) {
        $sql = "INSERT INTO commentaires (contenu, id_article, id_client) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('sii', $contenu, $id_article, $id_client);
        return $stmt->execute();
    }

    public function modifierCommentaire($id_commentaire, $contenu, $id_client) {
        $sql = "UPDATE commentaires SET contenu = ? WHERE id_commentaire = ? AND id_client = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('sii', $contenu, $id_commentaire, $id_client);
        return $stmt->execute();
    }

    public function supprimerCommentaire($id_commentaire, $id_client) {
        $sql = "DELETE FROM commentaires WHERE id_commentaire = ? AND id_client = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('ii', $id_commentaire, $id_client);
        return $stmt->execute();
    }

    // Nouvelle méthode pour récupérer un commentaire par son ID
    public function getCommentaireById($id_commentaire) {
        $sql = "SELECT * FROM commentaires WHERE id_commentaire = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('i', $id_commentaire);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die("Erreur d'exécution de la requête SQL : " . $stmt->error);
        }

        return $result->fetch_assoc();
    }
}
?>