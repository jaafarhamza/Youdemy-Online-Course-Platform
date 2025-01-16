<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Visiteur;
use PDO;

class VisiteurController
{
    private $model;

    public function __construct(PDO $db)
    {
        $this->model = new Visiteur($db);
    }

    public function register($data)
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);
        return $this->model->create($data);
    }

    public function login($email, $password)
    {
        return $this->model->authenticate($email, $password);
    }

    public function updateRole($id, $role)
    {
        return $this->model->update(['role' => $role], ['id' => $id]);
    }
}
