<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\TagController;

$db = Database::connection();
$controller = new TagController($db);

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conditions = ['id' => $id];
    if ($controller->delete($conditions)) {
        header('Location: /../tags/index.php');
        exit;
    } else {
        echo "Failed to delete tag.";
    }
}

$tags = $controller->read();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tags</title>
</head>
<body>
    <h1>Tags</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($tags as $tag): ?>
            <tr>
                <td><?php echo htmlspecialchars($tag['id']) ?></td>
                <td><?php echo htmlspecialchars($tag['name']) ?></td>
                <td>
                    <a href="../tags/edit.php?id=<?php echo htmlspecialchars($tag['id']) ?>">Modifier</a>
                    <a href="index.php?action=delete&id=<?php echo htmlspecialchars($tag['id']) ?>" onclick="return confirm('Are you sure you want to delete this tag?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../tags/create.php">Add New Tag</a>
</body>
</html>
