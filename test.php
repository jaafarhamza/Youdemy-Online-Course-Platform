<?php
require_once __DIR__ . '/vendor/autoload.php';
// require_once "app/config/Database.php";

use App\Config\Database;

$db = Database::connection();
var_dump($db); 




