<?php

namespace App\Repository\Adm;

use App\Core\Model;

class Update extends Model
{
    public function runQuery(string $query): string
    {
        return $this->db->query($query);
    }
}
