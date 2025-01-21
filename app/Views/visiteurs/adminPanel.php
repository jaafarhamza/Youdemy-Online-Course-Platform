<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\VisiteurController;

session_start();
if (! isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$db         = Database::connection();
$controller = new VisiteurController($db);

$users = $controller->read();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId  = $_POST['id'];
    $newRole = $_POST['role'];
    $action  = $_POST['action'];

    if ($action === 'delete') {
        $controller->delete($userId);
        header('Location: ../visiteurs/adminPanel.php');
        exit;
    } elseif ($action === 'ban') {
        $controller->ban($userId);
        header('Location: ../visiteurs/adminPanel.php');
        exit;
    } elseif ($action === 'activate') {
        $controller->activate($userId);
        header('Location: ../visiteurs/adminPanel.php');
        exit;
    } elseif ($newRole === 'enseignant') {
        if ($controller->updateRole($userId, $newRole)) {
            $controller->updateStatus($userId, 'non_validated');
            header('Location: ../visiteurs/adminPanelEnseignant.php');
            exit;
        } else {
            echo "Failed to update role.";
        }
    } else {
        if ($controller->updateRole($userId, $newRole)) {
            header('Location: ../visiteurs/adminPanel.php');
            exit;
        } else {
            echo "Failed to update role.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <?php include_once __DIR__ . '/aside-header.php'; ?>
</head>

<body class="bg-gray-300 ">
    <div class="bg-gray-300 rounded-lg shadow-lg ">
        <div class="overflow-x-auto">
            <table class="w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($user['id']) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($user['username']) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($user['email']) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($user['role']) ?></td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <form action="" method="POST" class="flex items-center">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']) ?>">
                                    <select name="role" class="bg-white border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="enseignant"
                                            <?php if ($user['role'] === 'enseignant') {
                                                echo 'selected';
                                            }
                                            ?>>Enseignant</option>
                                        <option value="admin"
                                            <?php if ($user['role'] === 'admin') {
                                                echo 'selected';
                                            }
                                            ?>>Admin</option>
                                        <option value="etudiant"
                                            <?php if ($user['role'] === 'etudiant') {
                                                echo 'selected';
                                            }
                                            ?>>Etudiant</option>
                                    </select>
                                    <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Update Role</button>
                                    <?php if ($user['status'] === 'active'): ?>
                                        <button type="submit" name="action" value="ban" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="return confirm('Are you sure you want to ban this user?');">Ban</button>
                                    <?php else: ?>
                                        <button type="submit" name="action" value="activate" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded" onclick="return confirm('Are you sure you want to activate this user?');">Activate</button>
                                    <?php endif; ?>
                                    <button type="submit" name="action" value="delete" class="ml-2 bg-red-600 hover:bg-red-400 text-white font-bold py-1 px-2 rounded" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>