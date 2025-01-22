<?php
session_start();
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\CoursController;

$db = Database::connection();
$controller = new CoursController($db);
$query = $_GET['query'] ?? '';

if (!empty($query)) {
    $searchResults = $controller->searchCourses($query);
} else {
    $categories = $controller->readCategories();
    $courses = [];

    foreach ($categories as $category) {
        $categoryId = $category['id'];
        $currentPage = isset($_GET["page_$categoryId"]) ? (int)$_GET["page_$categoryId"] : 1;
        $limit = 8;

        $categoryCourses = $controller->getAllCourses($categoryId, $currentPage, $limit);
        $totalCoursesByCategory = $controller->getTotalCourses($categoryId);
        $totalPagesByCategory = ceil($totalCoursesByCategory / $limit);

        $courses[$categoryId] = [
            'name' => $category['name'],
            'items' => $categoryCourses,
            'totalPages' => $totalPagesByCategory,
            'currentPage' => $currentPage
        ];
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .body {
            max-height: 100%;
            overflow-y: auto;
        }

        .form {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include __DIR__ . '/../visiteurs/header_home.php'; ?>
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Partagez votre expertise</span>
                            <span class="block text-indigo-600 xl:inline">technique</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Rejoignez notre communauté de développeurs passionnés. Partagez vos connaissances, découvrez de nouveaux concepts et restez à jour avec les dernières technologies.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <!-- Commencer Button -->
                            <div class="rounded-md shadow">
                                <a href="../auth/singup.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Commencer
                                </a>
                            </div>

                            <!-- Search Bar -->
                            <div class="mt-4 sm:mt-0 sm:ml-3">
                                <form action="" method="GET" class="flex">
                                    <input
                                        type="text"
                                        name="query"
                                        placeholder="Rechercher un cours ou un enseignant"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        value="<?= htmlspecialchars($query) ?>">
                                    <button
                                        type="submit"
                                        class="px-6 py-3 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80" alt="">
        </div>
    </div>
    <div class="bg-white p-8 rounded-lg shadow-lg body w-full">
        <?php if (!empty($query)): ?>
            <!-- Display Search Results -->
            <h2 class="text-2xl font-bold mb-6 text-center">Résultats de recherche pour "<?= htmlspecialchars($query) ?>"</h2>
            <?php if (!empty($searchResults)): ?>
                <div class="flex justify-center flex-wrap gap-6">
                    <?php foreach ($searchResults as $course): ?>
                        <div class="bg-gr p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105 md:w-1/5">
                            <?php if (!empty($course['featured_image'])): ?>
                                <img src="../../../<?= htmlspecialchars($course['featured_image']) ?>" class="w-full h-48 object-cover rounded-t-lg" alt="Featured Image">
                            <?php endif; ?>
                            <h4 class="text-lg font-semibold mt-4"><?= htmlspecialchars($course['title']) ?></h4>
                            <p class="text-gray-600"><?= htmlspecialchars($course['description']) ?></p>
                            <p class="text-gray-500 text-sm mt-1">Enseignant: <?= htmlspecialchars($course['enseignant_name']) ?></p>
                            <p class="text-gray-500 text-sm mt-1">Créé le: <?= htmlspecialchars(date('d-m-Y', strtotime($course['created_at']))) ?></p>
                            <?php if (!empty($course['tags'])): ?>
                                <p class="bg-green-500 text-white px-2 py-2 w-full rounded-2xl"><?= htmlspecialchars($course['tags']) ?></p>
                            <?php endif; ?>
                            <form action="enroll.php" method="POST" class="mt-4">
                                <input type="hidden" name="cours_id" value="<?= $course['course_id'] ?>">
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-2xl hover:bg-blue-600">
                                    S'inscrire
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500">Aucun cours trouvé.</p>
            <?php endif; ?>
        <?php else: ?>
            <!-- Display All Courses -->
            <h2 class="text-2xl font-bold mb-6 text-center">All Courses</h2>
            <?php foreach ($courses as $categoryId => $categoryData): ?>
                <h2 class="text-2xl font-bold mt-6 mb-4"><?= htmlspecialchars($categoryData['name']) ?></h2>
                <div class="flex justify-center flex-wrap gap-6">
                    <?php foreach ($categoryData['items'] as $course): ?>
                        <div class="bg-gr p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105 md:w-1/5">
                            <?php if (!empty($course['featured_image'])): ?>
                                <img src="../../../<?= htmlspecialchars($course['featured_image']) ?>" class="w-full h-48 object-cover rounded-t-lg" alt="Featured Image">
                            <?php endif; ?>
                            <h4 class="text-lg font-semibold mt-4"><?= htmlspecialchars($course['title']) ?></h4>
                            <p class="text-gray-600"><?= htmlspecialchars($course['description']) ?></p>
                            <p class="text-gray-500 text-sm mt-1">Enseignant: <?= htmlspecialchars($course['enseignant_name']) ?></p>
                            <p class="text-gray-500 text-sm mt-1">Créé le: <?= htmlspecialchars(date('d-m-Y', strtotime($course['created_at']))) ?></p>
                            <?php if (!empty($course['tags'])): ?>
                                <p class="bg-green-500 text-white px-2 py-2 w-full rounded-2xl"><?= htmlspecialchars($course['tags']) ?></p>
                            <?php endif; ?>
                            <form action="enroll.php" method="POST" class="mt-4">
                                <input type="hidden" name="cours_id" value="<?= $course['course_id'] ?>">
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-2xl hover:bg-blue-600">
                                    S'inscrire
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination Controls-->
                <div class="flex justify-center mt-6">
                    <?php if ($categoryData['currentPage'] > 1): ?>
                        <a href="?page_<?= $categoryId ?>=<?= $categoryData['currentPage'] - 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Previous</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $categoryData['totalPages']; $i++): ?>
                        <a href="?page_<?= $categoryId ?>=<?= $i ?>" class="bg-gray-200 text-gray-800 px-4 py-2 mx-1 rounded-lg <?= $i == $categoryData['currentPage'] ? 'bg-blue-500 text-white' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <?php if ($categoryData['currentPage'] < $categoryData['totalPages']): ?>
                        <a href="?page_<?= $categoryId ?>=<?= $categoryData['currentPage'] + 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Next</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>