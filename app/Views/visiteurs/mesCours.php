<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\CoursController;

$db = Database::connection();
$controller = new CoursController($db);

$etudiant_id = $_SESSION['id'];

$stmt = $db->prepare("
    SELECT c.id, c.title, c.description, c.featured_image, c.created_at, v.username AS enseignant_name
    FROM cours c
    JOIN cours_etudiants ce ON c.id = ce.cours_id
    JOIN visiteur v ON c.enseignant_id = v.id
    WHERE ce.etudiant_id = :etudiant_id
");
$stmt->bindValue(':etudiant_id', $etudiant_id, PDO::PARAM_INT);
$stmt->execute();
$enrolledCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include __DIR__ . '/../visiteurs/header_home.php'; ?>
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-6">Mes Cours</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($enrolledCourses as $course): ?>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <?php if (!empty($course['featured_image'])): ?>
                        <img src="../../../<?= htmlspecialchars($course['featured_image']) ?>" class="w-full h-48 object-cover rounded-t-lg" alt="Featured Image">
                    <?php endif; ?>
                    <h4 class="text-lg font-semibold mt-4"><?= htmlspecialchars($course['title']) ?></h4>
                    <p class="text-gray-600"><?= htmlspecialchars($course['description']) ?></p>
                    <p class="text-gray-500 text-sm mt-1">Enseignant: <?= htmlspecialchars($course['enseignant_name']) ?></p>
                    <p class="text-gray-500 text-sm mt-1">Créé le: <?= htmlspecialchars(date('d-m-Y', strtotime($course['created_at']))) ?></p>
                    <a href="../cours/index.php?id=<?= $course['id'] ?>" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Voir le cours
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>