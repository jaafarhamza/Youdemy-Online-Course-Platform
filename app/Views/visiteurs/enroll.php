<?php
session_start();

require_once __DIR__ . '../../../../vendor/autoload.php';
use App\Config\Database;
use App\Controllers\CoursController;

$db = Database::connection();
$controller = new CoursController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cours_id = filter_input(INPUT_POST, 'cours_id', FILTER_VALIDATE_INT);

    if (isset($_SESSION['id'])) {
        // User is logged in as an etudiant
        $etudiant_id = $_SESSION['id'];
        $success = $controller->enrollInCourse($cours_id, $etudiant_id);

        if ($success) {
            header('Location: mesCours.php'); // Redirect to "Mes Cours" page
            exit;
        } else {
            echo "Erreur lors de l'inscription.";
        }
    } else {
        // User is not logged in, redirect to login page
        header('Location: ../auth/login.php');
        exit;
    }
}