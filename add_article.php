<?php
require_once 'DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $id_theme = intval($_POST['id_theme']);
    $id_client = intval($_POST['id_client']);
    $tags = explode(',', $_POST['tags']); // Assume tags are sent as a comma-separated string

    echo "Titre: $titre<br>";
    echo "Contenu: $contenu<br>";
    echo "ID Thème: $id_theme<br>";
    echo "ID Client: $id_client<br>";
    echo "Tags: " . implode(', ', $tags) . "<br>";

    $db = (new DB())->connect();

    // Insert the article
    $stmt = $db->prepare("INSERT INTO articles (titre, contenu, statut, id_client, id_theme) VALUES (?, ?, 'En attente', ?, ?)");
    if ($stmt === false) {
        die("Erreur de préparation de la requête SQL : " . $db->error);
    }

    $stmt->bind_param('ssii', $titre, $contenu, $id_client, $id_theme);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Article ajouté avec succès.<br>";
        $article_id = $stmt->insert_id;

        // Insert the tags
        foreach ($tags as $tag) {
            $tag = trim($tag);
            if ($tag) {
                // Check if the tag already exists
                $stmt = $db->prepare("SELECT id_tag FROM tags WHERE nom_tag = ?");
                $stmt->bind_param('s', $tag);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Tag already exists
                    $row = $result->fetch_assoc();
                    $tag_id = $row['id_tag'];
                } else {
                    // Insert new tag
                    $stmt = $db->prepare("INSERT INTO tags (nom_tag) VALUES (?)");
                    $stmt->bind_param('s', $tag);
                    $stmt->execute();
                    $tag_id = $stmt->insert_id;
                }

                // Associate tag with article
                $stmt = $db->prepare("INSERT INTO article_tags (id_article, id_tag) VALUES (?, ?)");
                $stmt->bind_param('ii', $article_id, $tag_id);
                $stmt->execute();
            }
        }

        // Redirect back to themes.php
        header('Location: themes.php');
        exit();
    } else {
        echo "Erreur lors de l'ajout de l'article.<br>";
    }
}
?>