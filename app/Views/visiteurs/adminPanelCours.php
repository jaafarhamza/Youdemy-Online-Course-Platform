<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\CoursController;

session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$db = Database::connection();
$controller = new CoursController($db);

$courses = $controller->getAllCoursesForAdmin(1, 100);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = $_POST['course_id'];
    $newStatus = $_POST['status'];

    if ($controller->updateCourseStatus($courseId, $newStatus)) {
        header('Location: adminPanelCours.php');
        exit;
    } else {
        echo "Failed to update course status.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Manage Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen">
    <?php include __DIR__ . '/../visiteurs/aside-header.php'; ?>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-6">Manage Courses</h1>

        <!-- Course List -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 border">ID</th>
                        <th class="p-2 border">Title</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border"><?= htmlspecialchars($course['course_id']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($course['title']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($course['status']) ?></td>
                            <td class="p-2 border">
                                <form action="" method="POST" class="flex items-center gap-2">
                                    <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">
                                    <select name="status" class="p-2 border border-gray-300 rounded-md">
                                        <option value="draft" <?= $course['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="published" <?= $course['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                        <option value="scheduled" <?= $course['status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>