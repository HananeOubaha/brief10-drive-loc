<?php
session_start(); // Assurez-vous que la session est démarrée pour accéder à l'ID du client
require_once 'C_Commentaires.php';

if (!isset($_POST['contenu'], $_POST['id_article'])) {
    die("Données de formulaire manquantes.");
}

$id_client = $_SESSION['id_client']; // Assurez-vous que l'utilisateur est connecté
$contenu = $_POST['contenu'];
$id_article = intval($_POST['id_article']);

$commentairesClass = new C_Commentaires();
$success = $commentairesClass->ajouterCommentaire($contenu, $id_article, $id_client);

if ($success) {
    header("Location: commentaires.php?id_article=$id_article");
    exit();
} else {
    die("Erreur lors de l'ajout du commentaire.");
}
?>