<?php
include 'DB.php';

$bd = new DB();
$Connecte = $bd->connect();

if ($Connecte->connect_error) { 
    die("Connection error: " . $Connecte->connect_error);
} 
  
require_once 'Client.php';
include 'LogIn.php';

if(isset($_POST['submit_inscription'])){
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $adresse = $_POST["adresse"];
    $numtel = $_POST["telephone"];
    $email = $_POST["email"];
    $mdp = $_POST["mdp"];
    $client = new Client($nom, $prenom, $adresse, $numtel, $email, $mdp, $Connecte);
}

if(isset($_POST['LogIn'])){
    $email = $_POST["loginemail"];
    $mdp = $_POST["loginmdp"];
    $logIn = new LogIn($email, $mdp, $Connecte);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Espace Client</title>
    <style>
        @keyframes changeBackground {
            0% { background-image: url('1.png'); }
            25% { background-image: url('2.png'); }
            50% { background-image: url('3.png'); }
            100% { background-image: url('1.png'); }
        }
    </style>
</head> 
<body class=" h-screen w-screen flex items-center justify-center bg-cover bg-center animate-[changeBackground_16s_infinite] font-sans m-0">
    <div class="absolute top-5 left-1/2 transform -translate-x-1/2 text-cyan-500 text-2xl font-bold z-20">
        <h1>Drive&Loc</h1>
    </div>

    <div class="w-4/5 mx-auto flex justify-center">
        <form method="post" action="" class="w-[45%] relative z-10 bg-white/20 backdrop-blur-lg p-8 rounded-lg shadow-lg text-center">
            <h2 class="text-2xl font-bold text-cyan-900 mb-6">Connexion</h2>

            <div class="mb-4">
                <label for="login-email" class="block text-gray-700 font-medium mb-2">Email:</label>
                <input type="email" id="login-email" name="loginemail" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div class="mb-6">
                <label for="loginmdp" class="block text-gray-700 font-medium mb-2">Mot de passe:</label>
                <input type="password" id="loginmdp" name="loginmdp" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <button type="submit" name="LogIn" 
                class="w-full bg-cyan-900 hover:bg-cyan-500 text-white font-medium py-2 rounded-lg transition duration-300">
                Se connecter
            </button>

            <p class="mt-6">
                Pas encore inscrit ? 
                <a href="inscription.php" class="text-cyan-500 hover:underline">Inscrivez-vous ici</a>
            </p>
        </form>
    </div>
</body>
</html>