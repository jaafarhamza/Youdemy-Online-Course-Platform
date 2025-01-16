<?php
namespace App\Models;
require_once __DIR__ . '/../../vendor/autoload.php';

use PDO;

class Visiteur extends BaseModel
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'visiteur');
    }

    public function authenticate($email, $password)
    {
        $visiteur = $this->read(['email' => $email]);
        if (!empty($visiteur) && password_verify($password, $visiteur[0]['password_hash'])) {
            return $visiteur[0];
        }
        return false;
    }
}
