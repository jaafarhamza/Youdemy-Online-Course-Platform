<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\VisiteurController;
use App\Models\Visiteur;

session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$db = Database::connection();
$controller = new VisiteurController($db);

// $users = $controller->read();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $newRole = $_POST['role'];
    if ($controller->updateRole($userId, $newRole)) {
        echo "Role updated successfully.";
    } else {
        echo "Failed to update role.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']) ?></td>
                <td><?php echo htmlspecialchars($user['username']) ?></td>
                <td><?php echo htmlspecialchars($user['email']) ?></td>
                <td><?php echo htmlspecialchars($user['role']) ?></td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']) ?>">
                        <select name="role" required>
                            <option value="visiteur" <?php if ($user['role'] === 'visiteur') echo 'selected'; ?>>Visiteur</option>
                            <option value="enseignant" <?php if ($user['role'] === 'enseignant') echo 'selected'; ?>>Enseignant</option>
                            <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                            <option value="etudiant" <?php if ($user['role'] === 'etudiant') echo 'selected'; ?>>Etudiant</option>
                        </select>
                        <button type="submit">Update Role</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
