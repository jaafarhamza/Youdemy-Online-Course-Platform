<?php
namespace App\Models;

use PDO;

class Tag extends BaseModel
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'tags');
    }
}
