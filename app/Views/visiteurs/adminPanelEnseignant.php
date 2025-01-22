<?php
    require_once __DIR__ . '/../../../vendor/autoload.php';

    use App\Config\Database;
    use App\Controllers\EnseignantController;

    session_start();
    if (! isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../auth/login.php');
        exit;
    }

    $db         = Database::connection();
    $controller = new EnseignantController($db);

    $enseignants = $controller->readNonValidated();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $enseignantId = $_POST['id'];
        $action       = $_POST['action'];
        if ($action === 'validate') {
            $controller->validate($enseignantId);
        } elseif ($action === 'reject') {
            $controller->reject($enseignantId);
        }
        header('Location: ../visiteurs/adminPanelEnseignant.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Enseignants</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <?php include_once __DIR__ . '/aside-header.php'; ?>
</head>
<body class="bg-gray-100 h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <h1 class="text-3xl font-bold mb-6 text-center">Admin Dashboard - Enseignants</h1>
        <div class="overflow-x-auto">
            <table class="w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enseignants as $enseignant): ?>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($enseignant['id']); ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($enseignant['username']); ?></td>
                            <td class="py-2 px-4 border-b border-gray-200"><?php echo htmlspecialchars($enseignant['email']); ?></td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <form action="" method="POST" class="flex items-center space-x-2">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($enseignant['id']); ?>">
                                    <button type="submit" name="action" value="validate" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Validate</button>
                                    <button type="submit" name="action" value="reject" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Reject</button>
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
