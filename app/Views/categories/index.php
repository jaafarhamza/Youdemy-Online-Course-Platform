<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\CategorieController;

$db = Database::connection();
$controller = new CategorieController($db);

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
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
    <title>Categories</title>
</head>
<body>
    <h1>Categories</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo htmlspecialchars($category['id']) ?></td>
                <td><?php echo htmlspecialchars($category['name']) ?></td>
                <td>
                    <a href="../categories/edit.php?id=<?php echo htmlspecialchars($category['id']) ?>">Modifier</a>
                    <a href="index.php?action=delete&id=<?php echo htmlspecialchars($category['id']) ?>" onclick="return confirm('Are you sure you want to delete this category?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="/../categories/create.php">Add New Category</a>
</body>
</html>
