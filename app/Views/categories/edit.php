<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\CategorieController;

$db = Database::connection();
$controller = new CategorieController($db);

$id = $_GET['id'] ?? null;
if (!$id) {
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
</head>
<body>
    <h1>Update Category</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
