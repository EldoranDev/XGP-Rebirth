<?php

namespace App\Repository\Adm;

use App\Core\Model;

class Backup extends Model
{
    public function performBackup(): string
    {
        return $this->db->backupDb();
    }
}
