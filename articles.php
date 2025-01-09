<?php
require_once 'C_Articles.php';

if (!isset($_GET['id_theme'])) {
    die("ID du thème manquant.");
}

$id_theme = intval($_GET['id_theme']);
$articlesClass = new C_Articles();
$articles = $articlesClass->getArticlesByTheme($id_theme);

// Ajout d'un message de débogage pour vérifier l'ID du thème et le nombre d'articles
echo "ID du thème: $id_theme<br>";
echo "Nombre d'articles trouvés: " . count($articles) . "<br>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles du Thème</title>
    <!-- Ajouter Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-green-500 text-white p-4 text-center">
        <h1 class="text-2xl font-bold">Articles du Thème</h1>
        <a href="themes.php" class="text-white hover:underline">Retour aux thèmes</a>
    </header>

    <main class="p-4">
        <div class="container mx-auto">
            <?php if (!empty($articles)): ?>
                <ul class="space-y-4">
                    <?php foreach ($articles as $article): ?>
                        <li class="bg-white p-4 shadow rounded">
                            <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($article['titre']); ?></h2>
                            <p><?php echo nl2br(htmlspecialchars($article['contenu'])); ?></p>
                            <p class="text-sm text-gray-500">Statut: <?php echo htmlspecialchars($article['statut']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-center text-gray-500">Aucun article trouvé pour ce thème.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>