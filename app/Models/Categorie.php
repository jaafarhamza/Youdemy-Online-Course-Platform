<?php
namespace App\Models;

use PDO;

class Categorie extends BaseModel
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'categories');
    }
    
}
