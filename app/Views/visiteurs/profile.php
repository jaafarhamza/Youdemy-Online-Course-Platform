<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\VisiteurController;

session_start();
include_once __DIR__ . '/header_home.php';

if (! isset($_SESSION['id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$db         = Database::connection();
$controller = new VisiteurController($db);

$userId = $_SESSION['id'];
$user   = $controller->read(['id' => $userId]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $data = [
            'username' => $_POST['username'],
            'email'    => $_POST['email'],
            'bio'      => $_POST['bio'],
        ];
        if ($_FILES['profile_picture']['name']) {
            $filePath = $controller->uploadImage($userId, $_FILES['profile_picture']);
            if ($filePath) {
                $data['profile_picture_url'] = $filePath;
            }
        }
        $controller->update($data, ['id' => $userId]);
        header('Location: ../visiteurs/profile.php');
        exit;
    }
}
$user = $user[0];
if ($user['role'] === 'enseignant') {
    $stmt = $db->prepare("
            SELECT 
            c.id, 
            c.title, 
            c.description, 
            c.created_at,
            COUNT(ce.etudiant_id) AS enrolled_students
        FROM 
            cours c
        LEFT JOIN 
            cours_etudiants ce ON c.id = ce.cours_id
        WHERE 
            c.enseignant_id = :enseignant_id
        GROUP BY 
            c.id
        ");
    $stmt->bindValue(':enseignant_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
    if ($course_id) {
        $stmt = $db->prepare("DELETE FROM cours WHERE id = :course_id AND enseignant_id = :enseignant_id");
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindValue(':enseignant_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: profile.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-300 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full  space-y-4 grid grid-cols-1">
        <h1 class="text-3xl font-bold mb-6 text-center">Profile</h1>
        <div class="flex items-center justify-center mb-6">
            <img src="../../../<?php echo htmlspecialchars($user['profile_picture_url'] ?? 'default-image.jpg'); ?>" alt="Profile Image" class="rounded-full w-32 h-32">
        </div>
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="form-group">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="form-group">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="form-group">
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea id="bio" name="bio" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Image</label>
                <input type="file" id="profile_picture" name="profile_picture" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="form-group">
                <button type="submit" name="update_profile" class="w-full py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update Profile</button>
            </div>
        </form>
        <!-- Course Statistics Section -->
        <?php if ($user['role'] === 'enseignant'): ?>
            <h2 class="text-2xl font-bold mt-8 text-center">Your Courses (<?php echo count($courses); ?>)</h2>
            <div class="space-y-4">
                <?php foreach ($courses as $course): ?>
                    <div class="border border-gray-300 p-4 rounded-md flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold"><?php echo htmlspecialchars($course['title']); ?></h3>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <p class="text-sm text-gray-500">Created on: <?php echo htmlspecialchars(date('d-m-Y', strtotime($course['created_at']))); ?></p>
                            <p class="text-sm text-gray-500">Enrolled Students: <?php echo htmlspecialchars($course['enrolled_students']); ?></p>
                        </div>
                        <div class="flex space-x-2">
                            <form action="" method="POST">
                                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['id']); ?>">
                                <button type="submit" name="delete_course" class="text-red-600 hover:underline">Delete</button>
                            </form>
                            <a href="../cours/edit.php?id=<?php echo htmlspecialchars($course['id']); ?>" class="text-blue-600 hover:underline">Modify</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>