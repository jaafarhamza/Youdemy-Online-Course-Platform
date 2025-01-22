<?php
namespace App\Controllers;

use App\Models\Categorie;
use PDO;

class CategorieController
{
    private $model;

    public function __construct(PDO $db)
    {
        $this->model = new Categorie($db);
    }

    public function create($data)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
            ];
            if ($this->model->create($data)) {
                header('Location: ../categories/index.php');
                exit;
            } else {
                echo "Failed to create category.";
            }
        }
    }

    public function read($conditions = [])
    {
        return $this->model->read($conditions);
    }

    public function update($data, $conditions)
    {
        return $this->model->update($data, $conditions);
    }

    public function delete($conditions)
    {
        return $this->model->delete($conditions);
    }
}
