<?php
    require_once __DIR__ . '/../../../vendor/autoload.php';
    use App\Config\Database;
    // $test = new Database;
    // $test::connection();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,121,114,1) 35%, rgba(0,212,255,1) 100%);
        }
    </style>
</head>
<body class="bg-gray-300">

<!-- Sidebar -->
<div class="flex min-h-screen">
    <aside class="w-64 bg-gradient-to-br from-blue-800 to-blue-600 text-white flex flex-col">
        <div class="p-6 text-2xl font-bold border-b border-blue-700">Admin Dashboard</div>
        <nav class="mt-10 flex-1 space-y-2">
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Tag
            </a>
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Category
            </a>
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Courses
            </a>
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Enseignants
            </a>
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700">
                Manage Students
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
        <header class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-bold">Bienvenue, Admin</h1>
            <div class="flex items-center space-x-4">
                <span>John Doe</span>
                <button class="py-2 px-4 rounded bg-red-500 hover:bg-red-600 transition duration-200">Logout</button>
                <img class="w-10 h-10 rounded-full" src="https://via.placeholder.com/40" alt="User Avatar">
            </div>
        </header>

        <!-- Numbers Section -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
            <div class="bg-gradient-to-r from-green-400 to-blue-500 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
                <i class="fas fa-tags fa-2x"></i>
                <div>
                    <h2 class="text-xl font-bold">Tags</h2>
                    <p class="text-4xl font-semibold">120</p>
                </div>
            </div>
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
                <i class="fas fa-layer-group fa-2x"></i>
                <div>
                    <h2 class="text-xl font-bold">Categories</h2>
                    <p class="text-4xl font-semibold">10</p>
                </div>
            </div>
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
                <i class="fas fa-book fa-2x"></i>
                <div>
                    <h2 class="text-xl font-bold">Courses</h2>
                    <p class="text-4xl font-semibold">150</p>
                </div>
            </div>
            <div class="bg-gradient-to-r from-red-400 to-pink-500 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
                <i class="fas fa-user-graduate fa-2x"></i>
                <div>
                    <h2 class="text-xl font-bold">Students</h2>
                    <p class="text-4xl font-semibold">2000</p>
                </div>
            </div>
            <div class="bg-gradient-to-r from-black to-blue-500 p-6 rounded-lg shadow-md text-white flex items-center space-x-4">
                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                <div>
                    <h2 class="text-xl font-bold">Enseignant</h2>
                    <p class="text-4xl font-semibold">100</p>
                </div>
            </div>
        </section>

        <!-- Chart Sections -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Répartition par catégorie</h2>
                <canvas id="categoriesChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Les Top 3 enseignants</h2>
                <canvas id="topEnseignantChart"></canvas>
            </div>
        </section>

        <section class="bg-white p-6 rounded-lg shadow-md mb-10">
            <h2 class="text-xl font-bold mb-4">3 cours avec le plus d'étudiants</h2>
            <canvas id="topCoursesChart"></canvas>
        </section>
    </main>
</div>

<!-- Font Awesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- Chart.js Scripts -->
<script>
    // Répartition par catégorie Chart
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    const categoriesChart = new Chart(categoriesCtx, {
        type: 'bar',
        data: {
            labels: ['Science', 'Math', 'History', 'Language'],
            datasets: [{
                label: 'Nombre de cours',
                data: [50, 30, 20, 50],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Top Enseignant Chart
    const topEnseignantCtx = document.getElementById('topEnseignantChart').getContext('2d');
    const topEnseignantChart = new Chart(topEnseignantCtx, {
        type: 'bar',
        data: {
            labels: ['John Smith', 'Jane Doe', 'Michael Brown'],
            datasets: [{
                label: 'Rating',
                data: [4.9, 4.8, 4.7],
                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56']
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Top Courses Chart
    const topCoursesCtx = document.getElementById('topCoursesChart').getContext('2d');
    const topCoursesChart = new Chart(topCoursesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Cours de Science', 'Cours de Math', 'Cours de Language'],
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: [200, 150, 100],
                backgroundColor: ['#FF6384', '#36A2EB', '#4CAF50'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

</body>
</html>
