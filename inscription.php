<?php
session_start();
include 'DB.php';

$message = '';
$bd = new DB();
$Connecte = $bd->connect();

require_once 'Client.php';

if(isset($_POST['submit_inscription'])) {
    try { 
        // Validate inputs
        if(empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }
        
        if(strlen($_POST["mdp"]) < 6) {
            throw new Exception("Le mot de passe doit contenir au moins 6 caractères");
        } 

        $client = new Client(
            $_POST["nom"],
            $_POST["prenom"],
            $_POST["adresse"],
            $_POST["telephone"],
            $_POST["email"],
            $_POST["mdp"],
            $Connecte
        );
        
        header("Location: index.php");
        exit();
    } catch(Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Inscription</title>
    <style>
        @keyframes changeBackground {
            0% { background-image: url('1.png'); }
            25% { background-image: url('2.png'); }
            50% { background-image: url('3.png'); }
            100% { background-image: url('1.png'); }
        }
    </style>
</head> 
<body class="h-screen w-screen flex items-center justify-center bg-cover bg-center animate-[changeBackground_16s_infinite]">
    <div class="absolute top-5 left-1/2 transform -translate-x-1/2 text-white text-2xl font-bold z-20">
        <h1>>Drive&Loc</h1>
    </div>

    <div class="w-4/5 max-w-4xl mx-auto flex justify-center">
        <form id="inscription-form" class="bg-white/20 backdrop-blur-lg p-8 rounded-lg shadow-lg w-full max-w-lg z-10" method="post" action="inscription.php">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Inscription</h2>

            <!-- Message de retour -->
            <?php if (!empty($message)): ?>
                <div class="mb-4 text-center text-red-500 font-medium">
                    <?= $message; ?>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="nom" class="block text-gray-700 font-medium">Nom:</label>
                <input type="text" id="nom" name="nom" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="prenom" class="block text-gray-700 font-medium">Prénom:</label>
                <input type="text" id="prenom" name="prenom" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="adresse" class="block text-gray-700 font-medium">Adresse:</label>
                <input type="text" id="adresse" name="adresse" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="telephone" class="block text-gray-700 font-medium">Numéro de téléphone:</label>
                <input type="tel" id="telephone" name="telephone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email:</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label for="mdp" class="block text-gray-700 font-medium">Mot de passe:</label>
                <input type="password" id="mdp" name="mdp" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button type="submit" name="submit_inscription" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition duration-300">
                S'inscrire
            </button>
        </form>
    </div>
</body>
</html>
