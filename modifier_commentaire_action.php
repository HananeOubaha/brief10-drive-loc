<?php
session_start();
require_once 'C_Commentaires.php';

if (!isset($_POST['contenu'], $_POST['id_commentaire'], $_POST['id_article'])) {
    die("Données de formulaire manquantes.");
}

$id_client = $_SESSION['id_client'];
$contenu = $_POST['contenu'];
$id_commentaire = intval($_POST['id_commentaire']);
$id_article = intval($_POST['id_article']);

$commentairesClass = new C_Commentaires();
$success = $commentairesClass->modifierCommentaire($id_commentaire, $contenu, $id_client);

if ($success) {
    header("Location: commentaires.php?id_article=$id_article");
    exit();
} else {
    die("Erreur lors de la modification du commentaire.");
}
?>