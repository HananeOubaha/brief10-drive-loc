<?php
require_once 'DB.php';

class C_Tags {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    public function getTags() {
        $sql = "SELECT * FROM tags";
        $result = $this->db->query($sql);

        if ($result === false) {
            die("Erreur de requête SQL : " . $this->db->error);
        }

        $tags = [];
        while ($row = $result->fetch_assoc()) {
            $tags[] = $row;
        }

        return $tags;
    }

    public function getArticlesByTag($id_tag) {
        $sql = "SELECT a.* FROM articles a
                JOIN article_tags at ON a.id_article = at.id_article
                WHERE at.id_tag = ? AND a.statut = 'En attente'";
        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            die("Erreur de préparation de la requête SQL : " . $this->db->error);
        }

        $stmt->bind_param('i', $id_tag);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die("Erreur d'exécution de la requête SQL : " . $stmt->error);
        }

        $articles = [];
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }

        return $articles;
    }
}
?>