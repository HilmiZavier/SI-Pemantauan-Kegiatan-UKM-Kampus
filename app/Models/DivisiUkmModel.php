<?php

namespace App\Models;

use CodeIgniter\Model;

class DivisiUkmModel extends Model
{
    protected $table            = 'divisi_ukm';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'ukm_id',
        'nama_divisi'
    ];
}
