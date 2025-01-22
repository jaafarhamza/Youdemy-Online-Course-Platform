
<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\TagController;

session_start();
if (! isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$db = Database::connection();
$controller = new TagController($db);

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Tag ID is required.";
    exit;
}

$tag = $controller->read(['id' => $id]);
if (empty($tag)) {
    echo "Tag not found.";
    exit;
}
$tag = $tag[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
    ];
    $conditions = ['id' => $id];
    if ($controller->update($data, $conditions)) {
        header('Location: ../tags/index.php');
        exit;
    } else {
        echo "Failed to update tag.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Tag</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="bg-white rounded-lg shadow-md w-full ">
    <?php include __DIR__ . '/../visiteurs/aside-header.php'; ?>
        <h1 class="text-2xl font-bold mb-6">Update Tag</h1>
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="name" class="block text-gray-700">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($tag['name']) ?>" required class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class=" bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Update</button>
        </form>
    </div>
</body>
</html>
