<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 ">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50 " x-data="{ isOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="../visiteurs/home.php"><img class="h-8 w-auto" src="../../../uploads/reshot-icon-books-C3DGT7VPHQ.svg" alt="Youdemy"></a>
                    </div>
                </div>

                <!-- Navigation Desktop -->
                <div class="hidden md:flex items-center space-x-4">
                    <?php if (!isset($_SESSION['id'])): ?>
                        <a href="../auth/login.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                            Connexion
                        </a>
                        <a href="../auth/singup.php" class="inline-flex items-center px-4 py-2 border border-indigo-600 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 transition-colors">
                            Inscription
                        </a>
                    <?php else: ?>
                        <a href="../visiteurs/mesCours.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-800 hover:bg-gray-100 transition-colors">
                            Mes Cours
                        </a>
                        <?php if ($_SESSION['role'] === 'enseignant'): ?>
                            <a href="../cours/create.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                ADD Cours
                            </a>
                        <?php endif; ?>
                        <a href="../visiteurs/profile.php"><span class="text-gray-800"><?php echo htmlspecialchars($_SESSION['username']); ?></span></a>
                        <a href="../auth/logout.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                            Logout
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Button Menu Mobile -->
                <div class="md:hidden flex items-center">
                    <button @click="isOpen = !isOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="md:hidden" x-show="isOpen" @click.away="isOpen = false">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <?php if (!isset($_SESSION['id'])): ?>
                    <a href="../Login/login.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Connexion</a>
                    <a href="../Signup/signup.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Inscription</a>
                <?php else: ?>
                    <span class="block px-3 py-2 rounded-md text-base font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="../cours/my_courses.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Mes Cours</a>
                    <?php if ($_SESSION['role'] === 'enseignant'): ?>
                        <a href="../cours/create.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">ADD Cours</a>
                    <?php endif; ?>
                    <a href="../auth/logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</body>

</html>