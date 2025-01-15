
<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\TagController;

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
        header('Location: /../tags/index.php');
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
</head>
<body>
    <h1>Update Tag</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($tag['name']) ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
