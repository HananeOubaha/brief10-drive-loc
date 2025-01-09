<?php
require_once 'DB.php';

class C_Articles {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    public function getArticlesByTheme($id_theme) {
        $sql = "SELECT * FROM articles WHERE id_theme = ? AND statut = 'En attente'";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $stmt->bind_param('i', $id_theme);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die("Erreur d'exécution de la requête SQL : " . $stmt->error);
        }

        if ($result->num_rows > 0) {
            $articles = [];
            while ($row = $result->fetch_assoc()) {
                $articles[] = $row;
            }
            return $articles;
        } else {
            return [];
        }
    }

    public function searchArticlesByTitle($title) {
        $sql = "SELECT * FROM articles WHERE titre LIKE ? AND statut = 'En attente'";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }
        $searchTerm = "%" . $title . "%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die("Erreur d'exécution de la requête SQL : " . $stmt->error);
        }

        if ($result->num_rows > 0) {
            $articles = [];
            while ($row = $result->fetch_assoc()) {
                $articles[] = $row;
            }
            return $articles;
        } else {
            return [];
        }
    }
}
?>