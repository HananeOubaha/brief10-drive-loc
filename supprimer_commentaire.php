<?php
session_start();
require_once 'C_Commentaires.php';

if (!isset($_GET['id_commentaire'], $_GET['id_article'])) {
    die("ID du commentaire ou de l'article manquant.");
}

$id_client = $_SESSION['id_client'];
$id_commentaire = intval($_GET['id_commentaire']);
$id_article = intval($_GET['id_article']);

$commentairesClass = new C_Commentaires();
$success = $commentairesClass->supprimerCommentaire($id_commentaire, $id_client);

if ($success) {
    header("Location: commentaires.php?id_article=$id_article");
    exit();
} else {
    die("Erreur lors de la suppression du commentaire.");
}
?>