<?php
session_start(); // Assurez-vous que la session est démarrée pour accéder à l'ID du client
require_once 'C_Articles.php';
require_once 'C_Commentaires.php';
require_once 'C_Favoris.php';

if (!isset($_GET['id_article'])) {
    die("ID de l'article manquant.");
}

$id_article = intval($_GET['id_article']);
$articlesClass = new C_Articles();
$commentairesClass = new C_Commentaires();
$favorisClass = new C_Favoris();

$article = $articlesClass->getArticleById($id_article);
if (!$article) {
    die("Article introuvable.");
}
$commentaires = $commentairesClass->getCommentairesByArticle($id_article);
$id_client = $_SESSION['id_client'];
$estFavori = $favorisClass->estFavori($id_client, $id_article);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires sur l'Article</title>
    <!-- Ajouter Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-green-500 text-white p-4 text-center">
        <h1 class="text-2xl font-bold">Commentaires sur l'Article</h1>
        <a href="articles.php?id_theme=<?php echo htmlspecialchars($article['id_theme']); ?>" class="text-white hover:underline">Retour à l'article</a>
    </header>

    <main class="p-4">
        <div class="container mx-auto">
            <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($article['titre']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($article['contenu'])); ?></p>

            <div class="mt-4">
                <?php if ($estFavori): ?>
                    <a href="supprimer_favori.php?id_article=<?php echo $id_article; ?>" class="text-red-500 hover:underline">Supprimer des favoris</a>
                <?php else: ?>
                    <a href="ajouter_favori.php?id_article=<?php echo $id_article; ?>" class="text-blue-500 hover:underline">Ajouter aux favoris</a>
                <?php endif; ?>
            </div>

            <h3 class="text-lg font-semibold mt-4">Commentaires :</h3>
            <?php if (!empty($commentaires)): ?>
                <ul class="space-y-4">
                    <?php foreach ($commentaires as $commentaire): ?>
                        <li class="bg-white p-4 shadow rounded">
                            <p><?php echo nl2br(htmlspecialchars($commentaire['contenu'])); ?></p>
                            <?php if ($commentaire['id_client'] == $id_client): ?>
                                <a href="modifier_commentaire.php?id_commentaire=<?php echo $commentaire['id_commentaire']; ?>&id_article=<?php echo $id_article; ?>" class="text-blue-500 hover:underline">Modifier</a>
                                <a href="supprimer_commentaire.php?id_commentaire=<?php echo $commentaire['id_commentaire']; ?>&id_article=<?php echo $id_article; ?>" class="text-red-500 hover:underline">Supprimer</a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-center text-gray-500">Aucun commentaire trouvé pour cet article.</p>
            <?php endif; ?>

            <h3 class="text-lg font-semibold mt-4">Ajouter un commentaire :</h3>
            <form method="POST" action="ajouter_commentaire.php" class="mt-4">
                <textarea name="contenu" class="p-2 border border-gray-300 rounded w-full" placeholder="Votre commentaire" required></textarea>
                <input type="hidden" name="id_article" value="<?php echo $id_article; ?>">
                <button type="submit" class="mt-2 p-2 bg-blue-500 text-white rounded">Ajouter</button>
            </form>
        </div>
    </main>
</body>
</html>