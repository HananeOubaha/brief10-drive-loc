<?php
session_start();
include 'DB.php';
$bd = new DB();
$connect = $bd->connect();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idclient'])) {
    echo "<script>alert('Veuillez vous connecter pour ajouter un avis'); 
          window.location.href='indexi.php';</script>";
    exit;
}

// Récupérer l'ID du véhicule depuis l'URL
$id_vehicule = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_vehicule === null) {
    echo "<script>alert('Véhicule non spécifié'); window.location.href='indexi.php';</script>";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contenu = $_POST['contenu'];
    $note = $_POST['note'];
    $date_actuelle = date('Y-m-d');
    $id_client = $_SESSION['idclient'];

    // Vérifier si l'utilisateur a déjà donné un avis pour ce véhicule
    $check_avis = "SELECT id_avis FROM Avis WHERE id_client = ? AND id_vehicule = ?";
    $stmt_check = $connect->prepare($check_avis);
    $stmt_check->bind_param("ii", $id_client, $id_vehicule);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Vous avez déjà donné votre avis pour ce véhicule');</script>";
    } else {
        // Insérer le nouvel avis
        $sql = "INSERT INTO Avis (id_client, id_vehicule, contenu, note, date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("iisis", $id_client, $id_vehicule, $contenu, $note, $date_actuelle);
            if ($stmt->execute()) {
                echo "<script>alert('Votre avis a été ajouté avec succès'); 
                      window.location.href='detail_vehicule.php?id=" . $id_vehicule . "';</script>";
            } else {
                echo "<script>alert('Erreur lors de l'ajout de l'avis : " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
}

// Récupérer les informations du véhicule
$sql = "SELECT marque, modele FROM vehicules WHERE id_vehicule = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $id_vehicule);
$stmt->execute();
$result = $stmt->get_result();
$vehicule = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un avis - <?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cyan-100">
    <?php include 'header.php'; ?>
    <div class="container mx-auto mt-32">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Donner votre avis sur <?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?></h2>
            
            <form method="POST" action="" class="space-y-6">
                <div>
                    <label for="note" class="block text-gray font-medium mb-2">Note (/5)</label>
                    <select name="note" id="note" class="w-full p-2 border border-cyan-500 rounded-md" required>
                        <option value="">Sélectionnez une note</option>
                        <option value="1">1 - Très insatisfait</option>
                        <option value="2">2 - Insatisfait</option>
                        <option value="3">3 - Moyen</option>
                        <option value="4">4 - Satisfait</option>
                        <option value="5">5 - Très satisfait</option>
                    </select>
                </div>

                <div>
                    <label for="contenu" class="block text-gray font-medium mb-2">Votre avis</label>
                    <textarea 
                        name="contenu" 
                        id="contenu" 
                        rows="5" 
                        class="w-full p-2 border border-cyan-500 rounded-md "
                        placeholder="Partagez votre expérience avec ce véhicule..."
                        required
                    ></textarea>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-cyan-900 text-white py-2 px-4 rounded-md hover:bg-cyan-500 transition duration-200"
                >
                    Publier votre avis
                </button>
            </form>
        </div>
    </div>
</body>
</html>


<?php
// Modification de la requête SQL pour inclure le statut de suppression
$sql_avis = "SELECT a.*, c.nom, c.prenom 
            FROM Avis a 
            JOIN clients c ON a.id_client = c.id_client 
            WHERE a.id_vehicule = ? AND a.is_deleted = 0
            ORDER BY a.date DESC";
$stmt_avis = $connect->prepare($sql_avis);
$stmt_avis->bind_param("i", $id_vehicule);
$stmt_avis->execute();
$result_avis = $stmt_avis->get_result();

if ($result_avis->num_rows > 0) {
    while ($avis = $result_avis->fetch_assoc()) {
        ?>
        <div class="bg-gray-50 p-4 rounded-lg mb-4 mt-10 mx-60">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-semibold">
                        <?php echo htmlspecialchars($avis['prenom'] . ' ' . $avis['nom']); ?>
                    </p>
                    <div class="text-yellow-500">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $avis['note']) ? '★' : '☆';
                        }
                        ?>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-500 text-sm">
                        <?php echo date('d/m/Y', strtotime($avis['date'])); ?>
                    </span>
                    <?php if (isset($_SESSION['idclient']) && $_SESSION['idclient'] == $avis['id_client']) : ?>
                        <button 
                            onclick="openEditModal(<?php echo $avis['id_avis']; ?>, '<?php echo htmlspecialchars($avis['contenu'], ENT_QUOTES); ?>', <?php echo $avis['note']; ?>)"
                            class="text-blue-500 hover:text-blue-700"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button 
                            onclick="confirmDelete(<?php echo $avis['id_avis']; ?>)"
                            class="text-red-500 hover:text-red-700"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <p class="mt-2 text-gray-700">
                <?php echo htmlspecialchars($avis['contenu']); ?>
            </p>
        </div>
        <?php
    }
} else {
    echo '<p class="text-gray-500">Aucun avis pour ce véhicule.</p>';
}
?>

<!-- Modal pour modifier l'avis -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Modifier votre avis</h3>
            <form id="editForm" method="POST" action="modifier_avis.php" class="mt-4">
                <input type="hidden" id="edit_id_avis" name="id_avis">
                <div class="mb-4">
                    <label for="edit_note" class="block text-gray-700 font-medium mb-2">Note (/5)</label>
                    <select name="note" id="edit_note" class="w-full p-2 border rounded" required>
                        <option value="1">1 - Très insatisfait</option>
                        <option value="2">2 - Insatisfait</option>
                        <option value="3">3 - Moyen</option>
                        <option value="4">4 - Satisfait</option>
                        <option value="5">5 - Très satisfait</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="edit_contenu" class="block text-gray-700 font-medium mb-2">Votre avis</label>
                    <textarea 
                        name="contenu" 
                        id="edit_contenu" 
                        rows="4" 
                        class="w-full p-2 border rounded"
                        required
                    ></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id_avis, contenu, note) {
    document.getElementById('edit_id_avis').value = id_avis;
    document.getElementById('edit_contenu').value = contenu;
    document.getElementById('edit_note').value = note;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function confirmDelete(id_avis) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')) {
        window.location.href = `supprimer_avis.php?id=${id_avis}`;
    }
}
</script>