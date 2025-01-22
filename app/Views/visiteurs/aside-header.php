<?php

if (! isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
$adminName = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admjin';
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
?>

<div class="flex min-h-screen">
    <aside class="w-64 bg-gradient-to-br from-blue-800 to-blue-600 text-white flex flex-col">
        <div class="p-6 text-2xl font-bold border-b border-blue-700">
            <a href="../visiteurs/Dashboard.php">Admin Dashboard</a>
        </div>
        <nav class="mt-10 flex-1 space-y-2">
            <a href="../tags/index.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Tag
            </a>
            <a href="../categories/index.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Category
            </a>
            <a href="../visiteurs/adminPanelCours.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Courses
            </a>
            <a href="../visiteurs/adminPanelEnseignant.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Enseignants
            </a>
            <a href="../visiteurs/adminPanel.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Users
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
        <header class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-bold">Bienvenue, <?php echo $adminName; ?></h1>
            <div class="flex items-center space-x-4">
                <a href="../auth/logout.php" class="py-2 px-4 rounded bg-red-500 hover:bg-red-600 transition duration-200">Logout</a>
                <img class="w-10 h-10 rounded-full" src="https://via.placeholder.com/40" alt="User Avatar">
            </div>
        </header>