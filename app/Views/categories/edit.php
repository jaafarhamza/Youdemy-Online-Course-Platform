<?php
    require_once __DIR__ . '/../../../vendor/autoload.php';

    use App\Config\Database;
    use App\Controllers\CategorieController;

    session_start();
    if (! isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../auth/login.php');
        exit;
    }

    $db         = Database::connection();
    $controller = new CategorieController($db);

    $id = $_GET['id'] ?? null;
    if (! $id) {
        echo "Category ID is required.";
        exit;
    }

    $category = $controller->read(['id' => $id]);
    if (empty($category)) {
        echo "Category not found.";
        exit;
    }
    $category = $category[0];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => $_POST['name'],
        ];
        $conditions = ['id' => $id];
        if ($controller->update($data, $conditions)) {
            header('Location: ../categories/index.php');
            exit;
        } else {
            echo "Failed to update category.";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Category</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="bg-white rounded-lg shadow-md w-full ">
    <?php include __DIR__ . '/../visiteurs/aside-header.php'; ?>
        <h1 class="text-2xl font-bold mb-6">Update Category</h1>
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="name" class="block text-gray-700">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']) ?>" required class="mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class=" bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Update</button>
        </form>
    </div>
</body>
</html>

