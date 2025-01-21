<?php
    require_once __DIR__ . '/../../../vendor/autoload.php';

    use App\Config\Database;
    use App\Controllers\VisiteurController;

    session_start();

    $db         = Database::connection();
    $controller = new VisiteurController($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        $visiteur = $controller->login($email, $password);
        if ($visiteur) {
            $_SESSION['id']       = $visiteur['id'];
            $_SESSION['email']    = $visiteur['email'];
            $_SESSION['username'] = $visiteur['username'];
            $_SESSION['role']     = $visiteur['role'];
            $_SESSION['status']   = $visiteur['status'];

            if ($visiteur['role'] === 'admin') {
                header('Location: ../visiteurs/dashboard.php');
            } elseif ($visiteur['status'] === 'active') {
                header('Location: ../visiteurs/home.php');
            } else {
                $_SESSION['login_error'] = "You're banned.";
                header('Location: ../auth/login.php');
            }
            exit;
        } else {
            $_SESSION['login_error'] = "Login failed. Please check your email and password.";
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="text-red-500 mb-4"><?php echo htmlspecialchars($_SESSION['login_error']); ?></div>
            <?php unset($_SESSION['login_error']); ?>
<?php endif; ?>
        <form action="" method="POST" class="space-y-4">
            <div class="form-group">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="form-group">
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" id="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="form-group">
                <button name="login" type="submit" class="w-full py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">S'inscrire</button>
            </div>
            <p class="text-center font-extrabold text-gray-600">Pas encore de compte ?
                <a class="text-indigo-600 hover:text-indigo-800 hover:underline"
                   href="../auth/singup.php">
                    S'inscrire
                </a>
            </p>
        </form>
    </div>
</body>
</html>
