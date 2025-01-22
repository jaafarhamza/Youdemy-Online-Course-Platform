<?php
namespace App\Controllers;

use App\Models\Visiteur;
use PDO;

class EnseignantController
{
    private $model;

    public function __construct(PDO $db)
    {
        $this->model = new Visiteur($db);
    }

    public function readNonValidated()
    {
        return $this->model->read(['enseignant_status' => 'non_validated']);
    }

    public function validate($id)
    {
        return $this->model->update(['role' => 'enseignant', 'enseignant_status' => 'validated'], ['id' => $id]);
    }

    public function reject($id)
    {
        return $this->model->update(['role' => 'etudiant', 'enseignant_status' => 'rejected'], ['id' => $id]);
    }
}
