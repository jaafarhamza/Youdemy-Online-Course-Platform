<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
session_start();
if (! isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

use App\Config\Database;
use App\Controllers\CoursController;

$db = Database::connection();
$controller = new CoursController($db);

$totalTags = $controller->getTotalTags();
$totalCategories = $controller->getTotalCategories();
$totalCourses = $controller->getTotalCoursesStatis();
$totalStudents = $controller->getTotalStudents();
$totalEnseignants = $controller->getTotalEnseignants();

$coursesByCategory = $controller->getCoursesByCategory();
$topEnseignants = $controller->getTopEnseignants();
$topCoursesByStudents = $controller->getTopCoursesByStudents();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 121, 114, 1) 35%, rgba(0, 212, 255, 1) 100%);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8fafc;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9fafb;
        }
    </style>
</head>

<body class="bg-gray-300">
    <?php include_once __DIR__ . '/aside-header.php'; ?>
    <!-- Numbers Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
        <div class="bg-gradient-to-r from-green-400 to-blue-500 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
            <i class="fas fa-tags fa-2x"></i>
            <div>
                <h2 class="text-xl font-bold">Tags</h2>
                <p class="text-4xl font-semibold"><?= htmlspecialchars($totalTags) ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
            <i class="fas fa-layer-group fa-2x"></i>
            <div>
                <h2 class="text-xl font-bold">Categories</h2>
                <p class="text-4xl font-semibold"><?= htmlspecialchars($totalCategories) ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
            <i class="fas fa-book fa-2x"></i>
            <div>
                <h2 class="text-xl font-bold">Courses</h2>
                <p class="text-4xl font-semibold"><?= htmlspecialchars($totalCourses) ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-red-400 to-pink-500 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
            <i class="fas fa-user-graduate fa-2x"></i>
            <div>
                <h2 class="text-xl font-bold">Students</h2>
                <p class="text-4xl font-semibold"><?= htmlspecialchars($totalStudents) ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-black to-blue-500 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
            <i class="fas fa-chalkboard-teacher fa-2x"></i>
            <div>
                <h2 class="text-xl font-bold">Enseignant</h2>
                <p class="text-4xl font-semibold"><?= htmlspecialchars($totalEnseignants) ?></p>
            </div>
        </div>
    </section>

    <!-- Chart Sections -->
    <!-- Répartition par catégorie -->
    <section class="bg-white p-6 rounded-lg shadow-md mb-10">
        <h2 class="text-xl font-bold mb-4">Répartition par catégorie</h2>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Catégorie</th>
                    <th class="p-2 border">Nombre de cours</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coursesByCategory as $category): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border"><?= htmlspecialchars($category['category_name']) ?></td>
                        <td class="p-2 border text-center"><?= htmlspecialchars($category['course_count']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Les Top 3 enseignants -->
    <section class="bg-white p-6 rounded-lg shadow-md mb-10">
        <h2 class="text-xl font-bold mb-4">Les Top 3 enseignants</h2>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Enseignant</th>
                    <th class="p-2 border">Nombre de cours</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topEnseignants as $enseignant): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border"><?= htmlspecialchars($enseignant['enseignant_name']) ?></td>
                        <td class="p-2 border text-center"><?= htmlspecialchars($enseignant['course_count']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- 3 cours avec le plus d'étudiants -->
    <section class="bg-white p-6 rounded-lg shadow-md mb-10">
        <h2 class="text-xl font-bold mb-4">3 cours avec le plus d'étudiants</h2>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Cours</th>
                    <th class="p-2 border">Nombre d'étudiants</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topCoursesByStudents as $course): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border"><?= htmlspecialchars($course['course_title']) ?></td>
                        <td class="p-2 border text-center"><?= htmlspecialchars($course['student_count']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    </main>
    </div>

</body>

</html>