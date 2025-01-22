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

    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $id         = $_GET['id'];
        $conditions = ['id' => $id];
        if ($controller->delete($conditions)) {
            header('Location: ../categories/index.php');
            exit;
        } else {
            echo "Failed to delete category.";
        }
    }

    $categories = $controller->read();
?>
<!DOCTYPE html>
<html>
<head>
    <title>categories</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-300 h-screen">
    <?php include __DIR__ . '/../visiteurs/aside-header.php'; ?>
    <div class="bg-white p-8 rounded-lg shadow-md w-full ">
        <h1 class="text-2xl font-bold mb-6">categories</h1>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($category['id']) ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($category['name']) ?></td>
                        <td class="py-2 px-4 border-b">
                            <a href="../categories/edit.php?id=<?php echo htmlspecialchars($category['id']) ?>" class="text-blue-500 hover:underline mr-2">Modifier</a>
                            <a href="index.php?action=delete&id=<?php echo htmlspecialchars($category['id']) ?>" onclick="return confirm('Are you sure you want to delete this category?');" class="text-red-500 hover:underline">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../categories/create.php" class="mt-4 inline-block bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Add New category</a>
    </div>
</body>
</html>

