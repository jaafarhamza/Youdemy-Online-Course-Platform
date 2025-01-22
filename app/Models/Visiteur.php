<?php
namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';
use App\Models\BaseModel;
use PDO;

class Visiteur extends BaseModel
{
    protected $role;
    private string $username;
    private string $password;
    private string $email;
    private string $bio;
    private string $profile_picture_url;
    
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'visiteur');
    }

    public function authenticate($email, $password)
    {
        $visiteur = $this->read(['email' => $email]);
        if (! empty($visiteur) && password_verify($password, $visiteur[0]['password_hash'])) {
            return $visiteur[0];
        }
        return false;
    }
    public function uploadFile($file)
    {
        return parent::uploadFile($file);
    }
}
