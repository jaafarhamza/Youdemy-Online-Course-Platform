<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'enseignant') {
    header('Location: ../visiteurs/home.php?error=Unauthorized access');
    exit;
}

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\CoursController;

$db = Database::connection();
$controller = new CoursController($db);

$coursId = $_GET['id'] ?? null;
if (!$coursId) {
    header('Location: ../cours/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->editCours($coursId, $_POST, $_FILES);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6">Edit Course</h1>
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="title" class="block text-gray-700">Title:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($coursData['title'] ?? '') ?>" required class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="description" class="block text-gray-700">Description:</label>
                <textarea id="description" name="description" required class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($coursData['description'] ?? '') ?></textarea>
            </div>
            <div>
                <label for="category_id" class="block text-gray-700">Category:</label>
                <select id="category_id" name="category_id" required class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <?php
                    $categories = $controller->readCategories();
                    foreach ($categories as $category) {
                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="tags" class="block text-gray-700">Tags:</label>
                <select id="tags" name="tags[]" multiple class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <?php
                    $tags = $controller->readTags();
                    foreach ($tags as $tag) {
                        echo "<option value='{$tag['id']}'>{$tag['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="content_type" class="block text-gray-700">Content Type:</label>
                <select id="content_type" name="content_type" required class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="video" <?= (!empty($coursData['video_url'])) ? 'selected' : '' ?>>Video</option>
                    <option value="text" <?= (!empty($coursData['content'])) ? 'selected' : '' ?>>Text</option>
                </select>
            </div>
            <div id="video_url_field" style="display: <?= (!empty($coursData['video_url'])) ? 'block' : 'none' ?>;">
                <label for="video_url" class="block text-gray-700">Video URL:</label>
                <input type="url" id="video_url" name="video_url" value="<?= htmlspecialchars($coursData['video_url'] ?? '') ?>" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div id="content_field" style="display: <?= (!empty($coursData['content'])) ? 'block' : 'none' ?>;">
                <label for="content" class="block text-gray-700">Content:</label>
                <textarea id="content" name="content" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($coursData['content'] ?? '') ?></textarea>
            </div>
            <div>
                <label for="featured_image" class="block text-gray-700">Featured Image:</label>
                <input type="file" id="featured_image" name="featured_image" class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Update</button>
        </form>
    </div>
    <script>
        document.getElementById('content_type').addEventListener('change', function() {
            var videoUrlField = document.getElementById('video_url_field');
            var contentField = document.getElementById('content_field');
            if (this.value === 'video') {
                videoUrlField.style.display = 'block';
                contentField.style.display = 'none';
            } else {
                videoUrlField.style.display = 'none';
                contentField.style.display = 'block';
            }
        });
    </script>
</body>

</html>