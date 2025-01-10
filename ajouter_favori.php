<?php
session_start();
require_once 'C_Favoris.php';

if (!isset($_GET['id_article'])) {
    die("ID de l'article manquant.");
}

$id_client = $_SESSION['id_client'];
$id_article = intval($_GET['id_article']);

$favorisClass = new C_Favoris();
$success = $favorisClass->ajouterFavori($id_client, $id_article);

if ($success) {
    header("Location: commentaires.php?id_article=$id_article");
    exit();
} else {
    die("Erreur lors de l'ajout du favori.");
}
?>