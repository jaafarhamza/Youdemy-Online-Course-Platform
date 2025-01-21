<?php
namespace App\Controllers;

use App\Models\Tag;
use PDO;

class TagController
{
    private $model;

    public function __construct(PDO $db)
    {
        $this->model = new Tag($db);
    }

    public function create($data)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];

            if (empty($name)) {
                echo "Name cannot be empty.";
                return;
            }
            $data = [
                'name' => $name,
            ];
            if ($this->model->create($data)) {
                header('Location: ../tags/index.php');
                exit;
            } else {
                echo "Failed to create tag.";
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
