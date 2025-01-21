<?
namespace App\Models;
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Models\Visiteur;
use PDO;

class Administrateur extends Visiteur{
    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->setRole('admin');
    }
}