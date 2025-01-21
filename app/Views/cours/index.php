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

$course_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$course_id) {
    header('Location: mesCours.php');
    exit;
}

// Fetch course details
$stmt = $db->prepare("
    SELECT 
        c.id, c.title, c.description, c.content, c.video_url, c.featured_image, c.created_at,
        v.username AS enseignant_name,
        cat.name AS category_name
    FROM 
        cours c
    JOIN 
        visiteur v ON c.enseignant_id = v.id
    JOIN 
        categories cat ON c.category_id = cat.id
    WHERE 
        c.id = :course_id
");
$stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    header('Location: mes_cours.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .course-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .course-content iframe {
            width: 100%;
            height: 400px;
            border-radius: 8px;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include __DIR__ . '/../visiteurs/header_home.php'; ?>
    <div class="container mx-auto p-8">
        <!-- Course Header -->
        <div class="bg-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($course['title']) ?></h1>
            <p class="text-gray-600 mb-4"><?= htmlspecialchars($course['description']) ?></p>
            <div class="flex items-center space-x-4">
                <p class="text-gray-500 text-sm">Enseignant: <?= htmlspecialchars($course['enseignant_name']) ?></p>
                <p class="text-gray-500 text-sm">Catégorie: <?= htmlspecialchars($course['category_name']) ?></p>
                <p class="text-gray-500 text-sm">Créé le: <?= htmlspecialchars(date('d-m-Y', strtotime($course['created_at']))) ?></p>
            </div>
        </div>

        <!-- Featured Image -->
        <?php if (!empty($course['featured_image'])): ?>
            <div class="mb-8">
                <img src="../../../<?= htmlspecialchars($course['featured_image']) ?>" alt="Featured Image" class="w-full h-64 object-cover rounded-lg">
            </div>
        <?php endif; ?>

        <!-- Course Content -->
        <div class="bg-white p-8 rounded-lg shadow-lg course-content">
            <?php if (!empty($course['video_url'])): ?>
                <!-- Video Embed -->
                <div class="mb-8">
                    <iframe src="<?= htmlspecialchars($course['video_url']) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            <?php endif; ?>

            <!-- Text Content -->
            <?php if (!empty($course['content'])): ?>
                <div class="prose max-w-none">
                    <?= $course['content'] ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>