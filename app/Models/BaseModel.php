<?php
namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

use PDO;

class BaseModel
{
    private $conn;
    private $table;

    public function __construct(PDO $db, $table)
    {
        $this->conn  = $db;
        $this->table = $table;
    }

    public function create($data, $files = [])
    {
        if (! empty($files)) {
            foreach ($files as $key => $file) {
                $filePath = $this->uploadFile($file);
                if ($filePath) {
                    $data[$key] = $filePath;
                }
            }
        }
        $columns      = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query        = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        echo $query;
        $stmt         = $this->conn->prepare($query);

        foreach ($data as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }

        if (!$stmt->execute()) {
            print_r($stmt->errorInfo()); 
            return false; 
        }
        return true;
    }

    public function read($conditions = [])
    {
        $query = "SELECT * FROM {$this->table}";
        if (! empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", array_map(function ($key) {
                return "$key = :$key";
            }, array_keys($conditions)));
        }
        $stmt = $this->conn->prepare($query);

        foreach ($conditions as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($data, $conditions, $files = [])
    {
        if (! empty($files)) {
            foreach ($files as $key => $file) {
                $filePath = $this->uploadFile($file);
                if ($filePath) {
                    $data[$key] = $filePath;
                }
            }
        }

        $setPart = implode(", ", array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));

        $conditionPart = implode(" AND ", array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($conditions)));

        $query = "UPDATE {$this->table} SET $setPart WHERE $conditionPart";
        $stmt  = $this->conn->prepare($query);

        foreach ($data as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }

        foreach ($conditions as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }

        return $stmt->execute();
    }

    public function delete($conditions)
    {
        $conditionPart = implode(" AND ", array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($conditions)));

        $query = "DELETE FROM {$this->table} WHERE $conditionPart";
        $stmt  = $this->conn->prepare($query);

        foreach ($conditions as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }

        return $stmt->execute();
    }

    private function uploadFile($file)
    {
        $targetDir  = "uploads/";
        $targetFile = $targetDir . basename($file["name"]);
        $fileType   = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ["jpg", "jpeg", "png", "gif", "pdf", "mp4", "avi", "mov"];
        if (! in_array($fileType, $allowedTypes)) {
            return false;
        }

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        }

        return false;
    }
}
