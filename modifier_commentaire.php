<?php
session_start(); // Assurez-vous que la session est démarrée pour accéder à l'ID du client
require_once 'C_Commentaires.php';

if (!isset($_GET['id_commentaire'])) {
    die("ID du commentaire manquant.");
}

$id_commentaire = intval($_GET['id_commentaire']);
$commentairesClass = new C_Commentaires();
$commentaire = $commentairesClass->getCommentaireById($id_commentaire);

if ($commentaire['id_client'] != $_SESSION['id_client']) {
    die("Vous n'êtes pas autorisé à modifier ce commentaire.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Commentaire</title>
    <!-- Ajouter Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-green-500 text-white p-4 text-center">
        <h1 class="text-2xl font-bold">Modifier le Commentaire</h1>
    </header>

    <main class="p-4">
        <div class="container mx-auto">
            <form method="POST" action="modifier_commentaire_action.php">
                <textarea name="contenu" class="p-2 border border-gray-300 rounded w-full" required><?php echo htmlspecialchars($commentaire['contenu']); ?></textarea>
                <input type="hidden" name="id_commentaire" value="<?php echo $id_commentaire; ?>">
                <input type="hidden" name="id_article" value="<?php echo $commentaire['id_article']; ?>">
                <button type="submit" class="mt-2 p-2 bg-blue-500 text-white rounded">Modifier</button>
            </form>
        </div>
    </main>
</body>
</html>