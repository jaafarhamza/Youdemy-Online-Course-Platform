
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
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50" x-data="{ isOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="../../uploads/reshot-icon-books-C3DGT7VPHQ.svg" alt="Youdemy">
                    </div>
                </div>

                <!-- Navigation Desktop -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="../Login/login.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                        Connexion
                    </a>
                    <a href="../Singup/singup.php" class="inline-flex items-center px-4 py-2 border border-indigo-600 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 transition-colors">
                        Inscription
                    </a>
                </div>

                <!-- Bouton Menu Mobile -->
                <div class="md:hidden flex items-center">
                    <button @click="isOpen = !isOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="md:hidden" x-show="isOpen" @click.away="isOpen = false">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/articles" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Cours</a>
                <a href="/categories" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Catégories</a>
                <a href="../auth/login.php" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">Connexion</a>
                <a href="../auth/singup.php" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-600 hover:bg-indigo-50 border border-indigo-600">Inscription</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Partagez votre expertise</span>
                            <span class="block text-indigo-600 xl:inline">technique</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Rejoignez notre communauté de développeurs passionnés. Partagez vos connaissances, découvrez de nouveaux concepts et restez à jour avec les dernières technologies.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="../auth/singup.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Commencer
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80" alt="">
        </div>
    </div>

    <!--Cours section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Cours à la Une
                </h2>
                <h3 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl mt-4 ml-[-12%]">
                    CHAT GPT
                </h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($cours as $cour): ?>
                <article class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if ($cour['featured_image']): ?>
                        <img
                            src="<?php echo htmlspecialchars($cour['featured_image']) ?>"
                            alt="<?php echo htmlspecialchars($cour['title']) ?>"
                            class="w-full h-48 object-cover"
                        >
                    <?php endif; ?>

                    <div class="p-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="<?php echo getStatusClass($cour['status']) ?>">
                                <?php echo ucfirst($cour['status']) ?>
                            </span>
                            <span class="text-sm text-gray-500">
                                <?php echo formatDate($cour['created_at']) ?>
                            </span>
                        </div>

                        <h2 class="text-xl font-semibold mb-2">
                            <?php echo htmlspecialchars($cour['title']) ?>
                        </h2>

                        <p class="text-gray-600 mb-4">
                            <?php echo htmlspecialchars(substr($cour['excerpt'] ?? $cour['content'], 0, 150)) ?>...
                        </p>

                        <div class="flex justify-between items-center">
                            <div class="flex space-x-2">
                                <a href="../cours/edit.php/<?php echo $cour['id'] ?>"
                                   class="text-blue-500 hover:text-blue-600">
                                    Modifier
                                </a>
                                <a href="/cours/view/<?php echo $cour['id'] ?>"
                                   class="text-green-500 hover:text-green-600">
                                    Voir
                                </a>
                            </div>
                            <span class="text-sm text-gray-500">
                                Vues:                                                                                                                                                     <?php echo $cour['views'] ?>
                            </span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Fonctionnalités</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Tout ce dont vous avez besoin pour partager
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Une plateforme complète conçue pour les développeurs par des développeurs
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <!-- Feature 1 -->
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Éditeur Markdown</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Rédigez vos articles avec un éditeur Markdown intuitif et puissant.
                        </dd>
                    </div>

                    <!-- Feature 2 -->
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Personnalisation avancée</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Personnalisez votre profil et vos articles selon vos préférences.
                        </dd>
                    </div>
                    <?php

                        function getStatusClass($status)
                        {
                            return match ($status) {
                                'published' => 'px-2 py-1 rounded text-sm bg-green-100 text-green-800',
                                'draft' => 'px-2 py-1 rounded text-sm bg-gray-100 text-gray-800',
                                'scheduled' => 'px-2 py-1 rounded text-sm bg-yellow-100 text-yellow-800',
                                default => 'px-2 py-1 rounded text-sm bg-gray-100 text-gray-800'
                            };
                        }

                        function formatDate($date)
                        {
                            return date('d/m/Y', strtotime($date));
                        }
                    ?>
                </body>
</html>
