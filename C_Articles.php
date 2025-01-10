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
                $row['tags'] = $this->getTagsByArticle($row['id_article']);
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
                $row['tags'] = $this->getTagsByArticle($row['id_article']);
                $articles[] = $row;
            }
            return $articles;
        } else {
            return [];
        }
    }

    public function getTagsByArticle($id_article) {
        $sql = "SELECT t.nom_tag FROM tags t
                JOIN article_tags at ON t.id_tag = at.id_tag
                WHERE at.id_article = ?";
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

        $tags = [];
        while ($row = $result->fetch_assoc()) {
            $tags[] = $row['nom_tag'];
        }
        return $tags;
    }

    public function getArticleById($id_article) {
        $sql = "SELECT * FROM articles WHERE id_article = ?";
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

        return $result->fetch_assoc();
    }
}
?>