
<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\TagController;

$db = Database::connection();
$controller = new TagController($db);
$controller->create([]); 

?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Tag</title>
</head>
<body>
    <h1>Create Tag</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">Create</button>
    </form>
</body>
</html>
