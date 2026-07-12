<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaUkmModel extends Model
{
    protected $table            = 'anggota_ukm';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'ukm_id',
        'divisi_id',
        'nama',
        'nim',
        'prodi',
        'jabatan',
        'angkatan',
        'no_hp',
        'email'
    ];
}
