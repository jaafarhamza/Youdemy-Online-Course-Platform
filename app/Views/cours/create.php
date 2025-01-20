<?php
session_start();

if (! isset($_SESSION['role']) || $_SESSION['role'] !== 'enseignant') {
    header('Location: ../visiteurs/home.php?error=Unauthorized access');
    exit;
}
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\CoursController;

$db         = Database::connection();
$controller = new CoursController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->createCours($_POST, $_FILES);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/r4e2x3esar55yw2uak6uhfadbk2ypz3xwbivzcnde547354x/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .form-container {
            max-height: 80vh;
            overflow-y: auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>

</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl form-container">
        <h2 class="text-2xl font-bold mb-6 text-center">Create Course</h2>
        <form action="create.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="form-group">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="form-group">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="form-group">
                <label for="image" class="block text-sm font-medium text-gray-700">Image Upload</label>
                <input type="file" id="image" name="featured_image" accept="image/*" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <img id="image_preview" src="" alt="Image Preview" class="mt-2 hidden border border-gray-300" style="max-width: 100%; height: auto;">
            </div>
            <div class="form-group">
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <?php
                    $categories = $controller->readCategories();
                    foreach ($categories as $category) {
                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                <select id="tags" name="tags[]" multiple required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <?php
                    $tags = $controller->readTags();
                    foreach ($tags as $tag) {
                        echo "<option value='{$tag['id']}'>{$tag['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="content_type" class="block text-sm font-medium text-gray-700">Content Type</label>
                <select id="content_type" name="content_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="video">Video URL</option>
                    <option value="text">Text Content</option>
                </select>
            </div>
            <div id="video_url_field" class="form-group hidden">
                <label for="video_url" class="block text-sm font-medium text-gray-700">Video URL</label>
                <input type="url" id="video_url" name="video_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div id="content_field" class="form-group hidden">
                <label for="content" class="block text-sm font-medium text-gray-700">Text Content</label>
                <textarea id="content" name="content" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="w-full py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Course</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('content_type').addEventListener('change', function() {
            const videoUrlField = document.getElementById('video_url_field');
            const contentField = document.getElementById('content_field');
            if (this.value === 'video') {
                videoUrlField.classList.remove('hidden');
                contentField.classList.add('hidden');
            } else {
                videoUrlField.classList.add('hidden');
                contentField.classList.remove('hidden');
            }
        });

        new TomSelect("#tags", {
            plugins: ['remove_button'],
            maxOptions: 10,
        });

        tinymce.init({
            selector: '#content',
            plugins: 'link image code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code',
            menubar: false,
            statusbar: false,
        });

        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image_preview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };

                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
</body>

</html>