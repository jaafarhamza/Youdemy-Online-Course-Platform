<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Visiteur;
use PDO;

class VisiteurController
{
    private $model;
    private $db;

    public function __construct(PDO $db)
    {
        $this->model = new Visiteur($db);
        $this->db             = $db;
    }

    public function register($data)
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);
        $data['role'] = 'etudiant';
        return $this->model->create($data);

        if (isset($data['enseignant']) && $data['enseignant'] == 'true') {
            $data['enseignant_status'] = 'non_validated';
        } else {
            $data['enseignant_status'] = 'active';
        }
    }

    public function login($email, $password)
    {
        return $this->model->authenticate($email, $password);
        $user = $this->model->authenticate($email, $password);
        if ($user['status'] === 'active') {

            session_start();
            $_SESSION['id']     = $user['id'];
            $_SESSION['role']   = $user['role'];
            $_SESSION['status'] = $user['status'];
            return true;
        }
        return false;
    }

    public function updateRole($id, $role)
    {
        return $this->model->update(['role' => $role], ['id' => $id]);
    }
    public function read($conditions = [])
    {
        return $this->model->read($conditions);
    }
    public function delete($id)
    {
        return $this->model->delete(['id' => $id]);
    }
    public function ban($id)
    {
        return $this->model->update(['status' => 'banned'], ['id' => $id]);
    }

    public function activate($id)
    {
        return $this->model->update(['status' => 'active'], ['id' => $id]);
    }
    public function update($data, $conditions)
    {
        return $this->model->update($data, $conditions);
    }

    public function uploadImage($id, $file)
    {
        $filePath = $this->model->uploadFile($file);
        if ($filePath) {
            $this->model->update(['profile_picture_url' => $filePath], ['id' => $id]);
            return $filePath;
        }
        return false;
    }

    public function updateStatus($userId, $status) {
        $stmt = $this->db->prepare("UPDATE visiteur SET enseignant_status = ? WHERE id = ?"); 
        return $stmt->execute([$status, $userId]);
    }

}
