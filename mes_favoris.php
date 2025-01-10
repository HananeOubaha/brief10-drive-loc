<?php
session_start(); // Assurez-vous que la session est démarrée pour accéder à l'ID du client
require_once 'C_Favoris.php';

$id_client = $_SESSION['id_client'];
$favorisClass = new C_Favoris();

$favoris = $favorisClass->getFavorisByClient($id_client);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris</title>
    <!-- Ajouter Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-green-500 text-white p-4 text-center">
        <h1 class="text-2xl font-bold">Mes Favoris</h1>
        <a href="articles.php" class="text-white hover:underline">Retour aux articles</a>
    </header>

    <main class="p-4">
        <div class="container mx-auto">
            <h2 class="text-xl font-semibold">Articles favoris :</h2>
            <?php if (!empty($favoris)): ?>
                <ul class="space-y-4">
                    <?php foreach ($favoris as $article): ?>
                        <li class="bg-white p-4 shadow rounded">
                            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($article['titre']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($article['contenu'])); ?></p>
                            <a href="commentaires.php?id_article=<?php echo $article['id_article']; ?>" class="text-blue-500 hover:underline">Voir les commentaires</a>
                            <a href="supprimer_favori.php?id_article=<?php echo $article['id_article']; ?>" class="text-red-500 hover:underline">Supprimer des favoris</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-center text-gray-500">Aucun article favori trouvé.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>