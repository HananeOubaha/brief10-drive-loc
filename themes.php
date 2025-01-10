<?php
session_start(); // Assurez-vous que la session est démarrée pour accéder à l'ID du client
require_once 'C_themes.php';
require_once 'C_Articles.php';
require_once 'C_Tags.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id_client'])) {
    header('Location: index.php'); // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

$id_client = $_SESSION['id_client'];

$themesClass = new C_themes();
$themes = $themesClass->getThemes();

$tagsClass = new C_Tags();
$tags = $tagsClass->getTags();

$articles = [];
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $articlesClass = new C_Articles();
    $articles = $articlesClass->searchArticlesByTitle($searchTerm);
} elseif (isset($_GET['tag'])) {
    $id_tag = intval($_GET['tag']);
    $articles = $tagsClass->getArticlesByTag($id_tag);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorer les Thèmes</title>
    <!-- Ajouter Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-green-500 text-white p-4 text-center">
        <h1 class="text-2xl font-bold">Explorer les Thèmes du Blog</h1>
    </header>

    <!-- Formulaire de recherche d'article -->
    <div class="p-4">
        <form method="GET" action="themes.php" class="flex justify-center mb-4">
            <input type="text" name="search" placeholder="Rechercher un article par titre" class="p-2 border border-gray-400 rounded-l" required>
            <button type="submit" class="p-2 bg-blue-500 text-white rounded-r">Rechercher</button>
        </form>
        <!-- Bouton pour ouvrir le modal d'ajout d'article -->
        <div class="flex justify-center mb-4">
            <button onclick="document.getElementById('addArticleModal').classList.remove('hidden');" class="p-2 bg-green-500 text-white rounded">Ajouter un Article</button>
        </div>
        <!-- Champ de saisie avec suggestions automatiques pour les tags -->
        <div class="flex justify-center mb-4 relative">
            <input type="text" id="tagInput" placeholder="Rechercher un tag" class="p-2 border border-gray-300 rounded w-full">
            <div id="suggestions" class="absolute bg-white border border-gray-300 rounded shadow-lg max-h-40 overflow-y-auto w-full hidden"></div>
        </div>
    </div>

    <main class="p-4">
        <div class="container mx-auto">
            <?php if (!empty($articles)): ?>
                <h2 class="text-xl font-semibold">Articles :</h2>
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
                <p class="text-center text-gray-500">Aucun article trouvé.</p>
            <?php endif; ?>

            <h2 class="text-xl font-semibold">Thèmes :</h2>
            <?php if (!empty($themes)): ?>
                <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($themes as $theme): ?>
                        <li class="bg-white p-4 shadow rounded">
                            <a href="articles.php?id_theme=<?php echo $theme['id_theme']; ?>" class="text-xl font-semibold text-blue-500 hover:underline">
                                <?php echo htmlspecialchars($theme['nom']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-center text-gray-500">Aucun thème trouvé.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal d'ajout d'article -->
    <div id="addArticleModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded shadow-lg w-1/2">
            <h2 class="text-2xl font-bold mb-4">Ajouter un Article</h2>
            <form id="addArticleForm" method="POST" action="add_article.php" onsubmit="prepareTags()">
                <div class="mb-4">
                    <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                    <input type="text" id="titre" name="titre" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu</label>
                    <textarea id="contenu" name="contenu" class="mt-1 p-2 border border-gray-300 rounded w-full" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="id_theme" class="block text-sm font-medium text-gray-700">Thème</label>
                    <select id="id_theme" name="id_theme" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
                        <?php foreach ($themes as $theme): ?>
                            <option value="<?php echo $theme['id_theme']; ?>"><?php echo htmlspecialchars($theme['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                    <div id="tagsContainer" class="flex flex-wrap gap-2"></div>
                    <input type="text" id="tagInputModal" class="mt-1 p-2 border border-gray-300 rounded w-full" placeholder="Ajouter un tag">
                    <button type="button" onclick="addTag()" class="mt-2 p-2 bg-blue-500 text-white rounded">Ajouter Tag</button>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="document.getElementById('addArticleModal').classList.add('hidden');" class="mr-2 p-2 bg-gray-500 text-white rounded">Annuler</button>
                    <button type="submit" class="p-2 bg-green-500 text-white rounded">Ajouter</button>
                </div>
                <input type="hidden" name="tags" id="tags">
                <input type="hidden" name="id_client" value="<?php echo $id_client; ?>">
            </form>
        </div>
    </div>

    <script>
        // Liste des tags (chargée depuis la base de données)
        const tags = <?php echo json_encode($tags); ?>;

        document.addEventListener('DOMContentLoaded', function() {
            const tagInput = document.getElementById('tagInput');
            const suggestions = document.getElementById('suggestions');

            tagInput.addEventListener('input', function() {
                const query = tagInput.value.toLowerCase();
                suggestions.innerHTML = '';
                if (query.length > 0) {
                    const filteredTags = tags.filter(tag => tag.nom_tag.toLowerCase().includes(query));
                    filteredTags.forEach(tag => {
                        const div = document.createElement('div');
                        div.className = 'p-2 cursor-pointer hover:bg-gray-200';
                        div.textContent = tag.nom_tag;
                        div.dataset.id = tag.id_tag;
                        div.addEventListener('click', function() {
                            window.location.href = 'themes.php?tag=' + tag.id_tag;
                        });
                        suggestions.appendChild(div);
                    });
                    suggestions.classList.remove('hidden');
                } else {
                    suggestions.classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (!tagInput.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.classList.add('hidden');
                }
            });
        });

        function addTag() {
            const tagInput = document.getElementById('tagInputModal');
            const tag = tagInput.value.trim();
            if (tag) {
                const tagContainer = document.getElementById('tagsContainer');
                const tagElement = document.createElement('span');
                tagElement.className = 'inline-block bg-gray-200 text-gray-800 p-2 rounded';
                tagElement.textContent = tag;
                tagContainer.appendChild(tagElement);
                tagInput.value = '';
            }
        }

        function prepareTags() {
            const tags = [];
            const tagElements = document.getElementById('tagsContainer').children;
            for (const tagElement of tagElements) {
                tags.push(tagElement.textContent);
            }
            document.getElementById('tags').value = tags.join(',');
        }
    </script>
</body>
</html>