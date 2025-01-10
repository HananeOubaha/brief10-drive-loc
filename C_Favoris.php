<?php
require_once 'DB.php';

class C_Favoris {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    public function ajouterFavori($id_client, $id_article) {
        $sql = "INSERT INTO favoris (id_client, id_article) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('ii', $id_client, $id_article);
        return $stmt->execute();
    }

    public function supprimerFavori($id_client, $id_article) {
        $sql = "DELETE FROM favoris WHERE id_client = ? AND id_article = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('ii', $id_client, $id_article);
        return $stmt->execute();
    }

    public function estFavori($id_client, $id_article) {
        $sql = "SELECT * FROM favoris WHERE id_client = ? AND id_article = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('ii', $id_client, $id_article);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function getFavorisByClient($id_client) {
        $sql = "SELECT a.* FROM articles a JOIN favoris f ON a.id_article = f.id_article WHERE f.id_client = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('i', $id_client);
        $stmt->execute();
        $result = $stmt->get_result();

        $favoris = [];
        while ($row = $result->fetch_assoc()) {
            $favoris[] = $row;
        }

        return $favoris;
    }
}
?>